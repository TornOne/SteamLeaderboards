<?php include 'pieces/head.php';?>
<link href="stats.css" rel="stylesheet" type="text/css"/>
</head>
<?php include 'pieces/header.php';?>

<?php
$conn = pg_connect(getenv("DATABASE_URL"));

$result_browsers = pg_query($conn, 'SELECT * FROM bb_browsers;');
$result_platforms = pg_query($conn, 'SELECT * FROM bb_platforms;');
$result_times = pg_query($conn, 'SELECT * FROM bb_times;');
?>

<div class="stats">
	<div>
		Visitors per country:
		<ol>
			<?php
			while ($row = pg_fetch_row($result_platforms)) {
				echo "\t\t\t<li><span class=\"stats_item\"><span>" . $row[0] . "</span><span>" . $row[1] . "</span></span></li>\n";
			}
			?>
		</ol>
	</div>
	
	<div>
		Visitors per broswer:
		<ol>
			<?php
			while ($row = pg_fetch_row($result_browsers)) {
				echo "\t\t\t<li><span class=\"stats_item\"><span>" . $row[0] . "</span><span>" . $row[1] . "</span></span></li>\n";
			}
			?>
		</ol>
	</div>
	
	<div>
		Visitors per hour:
		<ol>
			<?php
			while ($row = pg_fetch_row($result_times)) {
				echo "\t\t\t<li><span class=\"stats_item\"><span>" . $row[0] . " - " . $row[0] + 1 . "</span><span>" . $row[1] . "</span></span></li>\n";
			}
			?>
		</ol>
	</div>
</div>
<?php pg_close($conn);?>

<?php include 'pieces/footer.php';?>
