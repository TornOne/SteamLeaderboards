<?php
$conn = pg_connect(getenv("DATABASE_URL"));
$next_refresh = strtotime(pg_fetch_result(pg_query($conn, "SELECT value FROM config_dates WHERE key='attempted_refresh_time';"), 0, 0)) + 86400 - time(); //Add a day to the last refresh
if ($next_refresh < 0) {
	$return_code = exec("python scraper/Start.py");
	if ($return_code == "201 Created") {
		pg_query($conn, 'SELECT update_attempted_refresh_time();');
	} else {
		pg_query($conn, 'SELECT update_failed_refresh_time();');
	}
}
?>
<?php include 'pieces/head.php'; ?>
<script src="/scripts/index.js"></script>
<link href="index.css" rel="stylesheet" type="text/css"/>
</head>
<?php include 'pieces/header.php'; ?>

<div class="main">
    <!--TODO:
	Add a flex div and a tag selector to the right
	Splitting platforms into boolean fields + adding a VR option?
	-->
    <?php include 'pieces/ranking.php'; ?>
</div>

<?php include 'pieces/footer.php'; ?>
