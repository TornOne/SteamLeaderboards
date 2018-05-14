<div id="ranking">
	<?php
	if (!isset($conn)) {
		$conn = pg_connect(getenv("DATABASE_URL"));
	}

	$page_count = ceil(pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM games;"), 0, 0) / 25);
	if (isset($_GET["page"]) && $_GET["page"] > 0) {
		$pagenr = $_GET["page"];
		if ($pagenr > $page_count) {
			$pagenr = $page_count;
		}
	} else {
		$pagenr = 1;
	}

	$offset = ($pagenr - 1) * 25;
	$games = pg_query_params($conn, 'SELECT * FROM top_games LIMIT 25 OFFSET $1;', array($offset));
	while ($row = pg_fetch_row($games)) {
		$offset++;
		//appid, name, rating, votes, score, windows, mac, linux, vr, release, price, tags
		//  0  ,  1  ,   2   ,   3  ,   4  ,    5   ,  6 ,   7  , 8 ,    9   ,  10  ,  11
		?>
		<a class="game_listing" href="http://store.steampowered.com/app/<?=$row[0]?>/">
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
				<?php if ($row[10] == 0): ?>
					<span class="game_price">Free</span>
				<?php elseif ($row[10] == -1): ?>
					<span class="game_price_na">N/A</span>
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

	<div class="page_selector">
		<?php if ($pagenr != 1) { ?>
			<a class="page_button" href="/index.php?page=<?=$pagenr - 1?>" onclick="parseLoadPage(this); return false;"><</a><a href="/index.php?page=1" onclick="parseLoadPage(this); return false;">1</a>
		<?php }

		if ($pagenr > 4) {
			echo " . . . ";
		}

		for ($i = max(2, $pagenr - 2); $i < $pagenr; $i++) { ?>
			<a href="/index.php?page=<?=$i?>" onclick="parseLoadPage(this); return false;"><?=$i?></a>
		<?php }
		echo "<span>$pagenr</span>";
		for ($i = $pagenr + 1; $i < min($pagenr + 3, $page_count); $i++) { ?>
			<a href="/index.php?page=<?=$i?>" onclick="parseLoadPage(this); return false;"><?=$i?></a>
		<?php }

		if ($pagenr < $page_count - 3) {
			echo " . . . ";
		}

		if ($pagenr != $page_count) { ?>
			<a href="/index.php?page=<?=$page_count?>" onclick="parseLoadPage(this); return false;"><?=$page_count?></a><a class="page_button" href="/index.php?page=<?=$pagenr + 1?>" onclick="parseLoadPage(this); return false;">></a>
		<?php } ?>
	</div>
</div>