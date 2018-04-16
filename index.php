<?php
$conn = pg_connect(getenv("DATABASE_URL"));
$next_refresh = strtotime(pg_fetch_result(pg_query($conn, "SELECT value FROM config_dates WHERE key='attempted_refresh_time';"), 0, 0)) + 86400 - time(); //Add a day to the last refresh
if ($next_refresh < 0) {
    $timeout = 7500; // Dyno timeout in seconds
    $url = "https://api.heroku.com/apps/steamleaderboards/dynos";
    $data = array("command" => "scraper", "size" => "free", "type" => "run", "time_to_live" => $timeout);
    $options = array(
        "http" => array(
            "header" => array(
                "Authorization: Bearer $API_TOKEN",
                "Accept: application/vnd.heroku+json; version=3"
            ),
            "method" => "POST",
            "content" => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        pg_query($conn, 'SELECT update_failed_refresh_time();');
    } else {
        pg_query($conn, 'SELECT update_attempted_refresh_time();');
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
	Updating starts, but the program just abruptly stops midway
	Add a next refresh timer somewhere
	Add a flex div and a tag selector to the right
	Add tags back
	Add a price to the right edge of the name? (being the first to be hidden)
	-->
    <?php include 'pieces/ranking.php'; ?>
</div>

<?php include 'pieces/footer.php'; ?>
