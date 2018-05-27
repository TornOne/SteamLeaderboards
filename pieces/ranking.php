<div id="ranking">
	<?php
	if (!isset($conn)) {
		$conn = pg_connect(getenv("DATABASE_URL"));
	}
	$where_fields = array();
	$where_params = array();

	//Tags
	if (isset($_GET["tags"])) {
		$where_fields[] = "tags @>";
		$where_params[] = "{" . $_GET["tags"] . "}";
	}
	//Price
	if (isset($_GET["price"])) {
		$where_fields[] = "price <=";
		$where_params[] = $_GET["price"];
	}
	//Date
	if (isset($_GET["date"])) {
		$date_preset = $_GET["date"];
		if ($date_preset === "week") {
			$from = date("Y-m-d", time() - 604800);
			$to = date("Y-m-d");
		} else if ($date_preset === "week2") {
			$from = date("Y-m-d", time() - 1209600);
			$to = date("Y-m-d", time() - 604800);
		} else if ($date_preset === "month") {
			$from = date("Y-m-d", time() - 2592000);
			$to = date("Y-m-d");
		} else if ($date_preset === "season") {
			$from = date("Y-m-d", time() - 7862400);
			$to = date("Y-m-d");
		} else if ($date_preset === "year") {
			$from = date("Y-m-d", time() - 31536000);
			$to = date("Y-m-d");
		}
		if (isset($from)) {
			$where_fields[] = "release >=";
			$where_fields[] = "release <=";
			$where_params[] = $from;
			$where_params[] = $to;
		}
	} else {
		if (isset($_GET["from"])) {
			$where_fields[] = "release >=";
			$where_params[] = $_GET["from"];
		}
		if (isset($_GET["to"])) {
			$where_fields[] = "release <=";
			$where_params[] = $_GET["to"];
		}
	}
	//Platforms
	if (isset($_GET["vr"])) {
		$where_fields[] = "vr =";
		$where_params[] = "t";
	}
	if (isset($_GET["win"])) {
		$where_fields[] = "windows =";
		$where_params[] = "t";
	}
	if (isset($_GET["mac"])) {
		$where_fields[] = "mac =";
		$where_params[] = "t";
	}
	if (isset($_GET["linux"])) {
		$where_fields[] = "linux =";
		$where_params[] = "t";
	}

	$where_query = "";
	for ($i = 0, $c = count($where_fields); $i < $c; $i++) {
		if ($i == 0) {
			$where_query .= " WHERE ";
		} else {
			$where_query .= " AND ";
		}
		$where_query .= $where_fields[$i] . " $" . ($i + 1);
	}

	$page_count = ceil(pg_fetch_result(pg_query_params($conn, "SELECT COUNT(*) FROM games" . $where_query . ";", $where_params), 0, 0) / 25);
	if ($page_count == 0) { ?>
		<div class="game_listing">
			<div class="empty_listing">No games found</div>
		</div>
		<?php $page_count = 1;
	}
	if (isset($_GET["page"]) && $_GET["page"] > 0) {
		$pagenr = (int) $_GET["page"];
		if ($pagenr > $page_count) {
			$pagenr = $page_count;
		}
	} else {
		$pagenr = 1;
	}

	$where_params_length = count($where_params);
	if (isset($_GET["name"])) {
		$where_params[] = "%" . $_GET["name"] . "%";
		$query_result = pg_query_params($conn, "SELECT row_number FROM (SELECT name, row_number() OVER() FROM top_games" . $where_query . ") AS win WHERE name ILIKE $" . count($where_params) . " LIMIT 1;", $where_params);
		if ($query_result = pg_fetch_row($query_result)) {
			$game_index = $query_result[0];
			$pagenr = ceil($query_result[0] / 25);
		}
	}

	$offset = ($pagenr - 1) * 25;
	$where_params[$where_params_length] = $offset;
	$games = pg_query_params($conn, "SELECT * FROM top_games" . $where_query . " LIMIT 25 OFFSET $" . count($where_params) . ";", $where_params);

	while ($row = pg_fetch_row($games)) {
		$offset++;
		//appid, name, rating, votes, score, windows, mac, linux, vr, release, price, tags
		//  0  ,  1  ,   2   ,   3  ,   4  ,    5   ,  6 ,   7  , 8 ,    9   ,  10  ,  11
		?>
		<a class="game_listing<?php if (isset($game_index) && $offset == $game_index) echo " searched"; ?>" href="http://store.steampowered.com/app/<?=$row[0]?>/">
			<img alt="<?=$row[1]?>" src="http://cdn.akamai.steamstatic.com/steam/apps/<?=$row[0]?>/capsule_231x87.jpg"/>
			<span class="<?php
			if ($offset < 10):
				echo "game_ranking_big";
			elseif ($offset < 100):
				echo "game_ranking_med";
			elseif ($offset < 1000):
				echo "game_ranking_small";
			else:
				echo "game_ranking_tiny";
			endif;
			?>"><?=$offset?>.</span>

			<div class="game_title_tags">
				<span class="game_title"><?=$row[1]?></span>
				<span class="game_tags">
					<?php
					$tags = explode(",", substr($row[11], 1, strlen($row[11]) - 2));
					for ($i = 0, $tag_count = count($tags); $i < min($tag_count, 5); $i++) {
						?>
						<span><?=trim($tags[$i], "\"")?></span>
					<?php } ?>
				</span>
			</div>

			<div class="game_price_vr">
				<?php if ($row[10] === null): ?>
					<span class="game_price_na">N/A</span>
				<?php elseif ($row[10] == 0): ?>
					<span class="game_price">Free</span>
				<?php else: ?>
					<span class="game_price"><?=number_format($row[10], 2), "$"?></span>
				<?php endif; ?>
				<span class="vr_<?=$row[8] == "t" ? "icon" : "placeholder"?>"></span>
			</div>

			<div class="game_release_platforms">
				<span class="game_release"><?=date("j M, Y", strtotime($row[9]))?></span>
				<span class="game_platforms">
					<?php if ($row[5] == "t"): ?>
						<span class="win_icon"></span>
					<?php endif; if ($row[6] == "t"): ?>
						<span class="mac_icon"></span>
					<?php endif; if ($row[7] == "t"): ?>
						<span class="linux_icon"></span>
					<?php endif; ?>
				</span>
			</div>

			<div class="game_score_reviews">
				<span class="game_score">
					<span><?=number_format($row[4] * 100, 1)?>%</span>
					(<?=number_format($row[2] * 100, 1)?>%)
				</span>
				<span class="game_reviews"><?=number_format($row[3], 0, '.', ' ')?> reviews</span>
			</div>
		</a>
	<?php } ?>

	<?php
	unset($_GET["name"]);
	unset($_GET["page"]);
	$query = http_build_query($_GET);
	?>

	<div class="page_selector">
		<?php if ($pagenr != 1) { ?>
			<a class="page_button" href="/?<?=$query?>&page=<?=$pagenr - 1?>" onclick="parseLoadPage(this); return false;"><</a><a href="/?<?=$query?>&page=1" onclick="parseLoadPage(this); return false;">1</a>
		<?php }

		if ($pagenr > 4) {
			echo " . . . ";
		}

		for ($i = max(2, $pagenr - 2); $i < $pagenr; $i++) { ?>
			<a href="/?<?=$query?>&page=<?=$i?>" onclick="parseLoadPage(this); return false;"><?=$i?></a>
		<?php }
		echo "<span>$pagenr</span>";
		for ($i = $pagenr + 1; $i < min($pagenr + 3, $page_count); $i++) { ?>
			<a href="/?<?=$query?>&page=<?=$i?>" onclick="parseLoadPage(this); return false;"><?=$i?></a>
		<?php }

		if ($pagenr < $page_count - 3) {
			echo " . . . ";
		}

		if ($pagenr != $page_count) { ?>
			<a href="/?<?=$query?>&page=<?=$page_count?>" onclick="parseLoadPage(this); return false;"><?=$page_count?></a><a class="page_button" href="/?<?=$query?>&page=<?=$pagenr + 1?>" onclick="parseLoadPage(this); return false;">></a>
		<?php } ?>
	</div>
</div>