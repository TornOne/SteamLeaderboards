<div class="ranking">
	<?php
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
		//appid, name, rating, votes, score, platforms, release, price [, tags]
		?>
		<a class="game_listing" href="http://store.steampowered.com/app/<?=$row[0]?>/">
			<img alt="<?=$row[1]?>" src="http://cdn.akamai.steamstatic.com/steam/apps/<?=$row[0]?>/capsule_231x87.jpg"/>
			<span class="game_ranking"><?=$offset?>.</span>

			<div class="game_title_tags">
				<span class="game_title"><?=$row[1]?></span>
				<span class="game_tags">
					<span>Placeholder</span>
					<span>Puzzle</span>
					<span>Co-op</span>
					<span>First Person</span>
					<span>Placeholder 2</span>
				</span>
			</div>

			<div class="game_release_platforms">
				<span class="game_release"><?=date("j M, Y", strtotime($row[6]))?></span>
				<span class="game_platforms">
					<?php if ($row[5] & 1): ?>
						<span class="win_icon"></span>
					<?php endif; if ($row[5] & 2): ?>
						<span class="mac_icon"></span>
					<?php endif; if ($row[5] & 4): ?>
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
			<a class="page_button" href="/index.php?page=<?=$pagenr - 1?>"><</a><a href="/index.php?page=1">1</a>
		<?php }

		if ($pagenr > 4) {
			echo " . . . ";
		}

		for ($i = max(2, $pagenr - 2); $i < $pagenr; $i++) { ?>
			<a href="/index.php?page=<?=$i?>"><?=$i?></a>
		<?php }
		echo "<span>$pagenr</span>";
		for ($i = $pagenr + 1; $i < min($pagenr + 3, $page_count); $i++) { ?>
			<a href="/index.php?page=<?=$i?>"><?=$i?></a>
		<?php }

		if ($pagenr < $page_count - 3) {
			echo " . . . ";
		}

		if ($pagenr != $page_count) { ?>
			<a href="/index.php?page=<?=$page_count?>"><?=$page_count?></a><a class="page_button" href="/index.php?page=<?=$pagenr + 1?>">></a>
		<?php } ?>
	</div>
</div>