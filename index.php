<?php
$conn = pg_connect(getenv("DATABASE_URL"));
$next_refresh = strtotime(pg_fetch_result(pg_query($conn, 'SELECT * FROM last_refresh;'), 0, 0)) + 86400 - time(); //Add a day to the last refresh
if ($next_refresh < 0) {
    $output = array();
    $exit_code = 1;
	exec("python scraper/Scraper.py" . " > /dev/null &",$output,$exit_code);
	if ($exit_code == 0) {
		pg_query($conn, 'SELECT reset_refresh_time();');
	}
}
?>
<?php include 'pieces/head.php';?>
<script src="/scripts/colorGames.js"></script>
<link href="index.css" rel="stylesheet" type="text/css"/>
</head>
<?php include 'pieces/header.php';?>

<div class="main" itemscope itemtype="http://schema.org/WebPage">
	<div class="ranking" itemscope itemtype="http://schema.org/VideoGame">
		<!--TODO:
		Database config thing, error messages, at least two refresh timers...
		Add a next refresh timer somewhere
		Add a flex div and a tag selector to the right
		Add tags back
		-->
		<?php
		$offset = 0;
		$games = pg_query_params($conn, 'SELECT * FROM top_games LIMIT 25 OFFSET $1;', array($offset));
		while ($row = pg_fetch_row($games)) {
		    $offset++;
		    //appid, name, rating, votes, score, platforms, release, price [, tags]
		?>
		<a class="game_listing" href="http://store.steampowered.com/app/<?=$row[0]?>/" itemprop="gameLocation">
			<img alt="<?=$row[1]?>" src="http://cdn.akamai.steamstatic.com/steam/apps/<?=$row[0]?>/capsule_sm_120.jpg" itemprop="thumbnailUrl"/>
			<span class="game_ranking" itemprop="position"><?=$offset?>.</span>
			
			<div class="game_title_tags">
				<span class="game_title" itemprop="name"><?=$row[1]?></span>
				<span class="game_tags">
					<span>Placeholder</span>
					<span>Puzzle</span>
					<span>Co-op</span>
					<span>First Person</span>
					<span>Placeholder 2</span>
				</span>
			</div>
			
			<div class="game_release_platforms">
				<span class="game_release" itemprop="dateCreated"><?=date("j M, Y", strtotime($row[6]))?></span>
				<span class="game_platforms" itemprop="operatingSystem">
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
				<span class="game_score" itemprop="position">
					<span><?=number_format($row[4] * 100, 1)?>%</span>
					(<?=number_format($row[2] * 100, 1)?>%)
				</span>
				<span class="game_reviews" itemprop="commentCount"><?=number_format($row[3], 0, '.', ' ')?> reviews</span>
			</div>
		</a>
		<?php } ?>
	</div>
</div>

<?php include 'pieces/footer.php';?>
