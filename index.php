<?php
$conn = pg_connect(getenv("DATABASE_URL"));
$last_refresh = strtotime(pg_fetch_result(pg_query($conn, 'SELECT * FROM last_refresh;'), 0, 0));
if (time() - $last_refresh > 86400) { //If the last refresh was more than a day ago
	//pg_query($conn, 'SELECT reset_refresh_time();');
	echo exec("python scraper/Scraper.py");// . " > /dev/null &");
    echo exec("find /app/scraper/ -name \"games.txt\"");
	echo exec("find /app/scraper/ -name \"Scraper.py\"");
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
		Make elements scale down as browser gets narrower to always fit them
		Name and tags should be properly clipped before the other columns start to be affected
		Maybe? add a color to the weighted review%
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
				<p class="game_tags">
					<!--
					<span>Puzzle</span>
					<span>Co-op</span>
					<span>First Person</span>
					-->
				</p>
			</div>
			
			<div class="game_release_platforms">
				<span class="game_release" itemprop="dateCreated"><?=date("j M, Y", strtotime($row[6]))?></span>
				<p class="game_platforms" itemprop="operatingSystem">
					<?php if ($row[5] & 1): ?>
					<span class="win_icon"></span>
					<?php endif; if ($row[5] & 2): ?>
					<span class="mac_icon"></span>
					<?php endif; if ($row[5] & 4): ?>
					<span class="linux_icon"></span>
					<?php endif; ?>
				</p>
			</div>
			
			<div class="game_score_reviews">
				<p class="game_score" itemprop="position">
					<span><?=number_format($row[4] * 100, 1)?>%</span>
					(<?=number_format($row[2] * 100, 1)?>%)
				</p>
				<span itemprop="commentCount"><?=number_format($row[3], 0, '.', ' ')?> reviews</span>
			</div>
		</a>
		<?php } ?>
	</div>
</div>

<?php include 'pieces/footer.php';?>
