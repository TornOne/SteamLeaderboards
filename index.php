<?php include 'pieces/head.php';?>
<link href="index.css" rel="stylesheet" type="text/css"/>
</head>
<?php include 'pieces/header.php';?>

<div class="main">
	<div class="ranking">
		<!--TODO:
		Make elements scale down as browser gets narrower to always fit them.
		Name and tags should be properly clipped before the other columns start to be affected
		Maybe? add a color to the weighted review%
		Add a flex div and a tag selector to the right
		-->
		<?php
		$conn = pg_connect(getenv("DATABASE_URL"));
		$games = pg_query($conn, 'SELECT * FROM top_games LIMIT 25 OFFSET 0;');
		while ($row = pg_fetch_row($games)) {
			//appid, name, rating, votes, score, platforms, release, price, row_number
			echo
		"<a class=\"game_listing\" href=\"http://store.steampowered.com/app/", $row[0], "/\">
			<img alt=\"", $row[1], "\" src=\"http://cdn.akamai.steamstatic.com/steam/apps/", $row[0], "/capsule_sm_120.jpg\"/>
			<span class=\"game_ranking\">", $row[8], ".</span>
			
			<div class=\"game_title_tags\">
				<span class=\"game_title\">", $row[1], "</span>
				<p class=\"game_tags\">
					<!--<span></span>
					<span></span>
					<span></span>-->
				</p>
			</div>
			
			<div class=\"game_release_platforms\">
				<span class=\"game_release\">", $row[6], "</span>
				<p class=\"game_platforms\">";
			if ($row[5] & 1 == 1)
				echo "<span class=\"win_icon\"></span>";
			if ($row[5] & 2 == 2)
				echo "<span class=\"mac_icon\"></span>";
			if ($row[5] & 4 == 4)
				echo "<span class=\"linux_icon\"></span>";
			echo
				"</p>
			</div>
			
			<div class=\"game_score_reviews\">
				<p class=\"game_score\">
					<span>", round($row[4] * 100, 1), "%</span>
					(", round($row[2] * 100, 1), "%)
				</p>
				<span>", $row[3], " reviews</span>
			</div>
		</a>";
		}
		?>
	</div>
</div>

<?php include 'pieces/footer.php';?>

<?php include 'bigbrother.php';?>
