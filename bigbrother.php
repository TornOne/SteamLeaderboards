<body>

<?php
require_once 'vendor/autoload.php';

use BrowscapPHP\Browscap;
$cacheDir = 'resources/browscap-php';
$fileCache = new \Doctrine\Common\Cache\FilesystemCache($cacheDir);
$cache = new \Roave\DoctrineSimpleCache\SimpleCacheAdapter($fileCache);
$logger = new \Monolog\Logger('logger');

$bc = new Browscap($cache, $logger);
$result = $bc->getBrowser();

$browser = $result->browser;
$platform = $result->platform;
date_default_timezone_set('Europe/Tallinn');
$requestTime = date('H:i:s', time());
echo nl2br($browser . "\n" . $requestTime . "\n" . $platform. "\n\n\n");

$conn = pg_connect(getenv("DATABASE_URL"));
pg_prepare($conn, "insert_bb", 'INSERT INTO bigbrother (browser, requestTime, platform) VALUES ($1, $2, $3);');
pg_execute($conn, "insert_bb", array($browser, $requestTime, $platform));

pg_prepare($conn, "get_browsers", 'SELECT * FROM bb_browsers;');
$result_browsers = pg_execute($conn, "get_browsers", array());

pg_prepare($conn, "get_platforms", 'SELECT * FROM bb_platforms');
$result_platforms = pg_execute($conn, "get_platforms", array());

pg_prepare($conn, "get_times", 'SELECT * FROM bb_times');
$result_times = pg_execute($conn, "get_times", array());

while ($row = pg_fetch_row($result_browsers)) {
    echo nl2br("$row[0]:  $row[1]\n");
}
echo nl2br("\n");
while ($row = pg_fetch_row($result_platforms)) {
    echo nl2br("$row[0]:  $row[1]\n");
}
echo nl2br("\n");
while ($row = pg_fetch_row($result_times)) {
    echo nl2br("$row[0]:  $row[1]\n");
}
echo nl2br("\n\n");
var_dump($_SERVER["SSL_CLIENT_CERT"]);
print_r($_SERVER["SSL_CLIENT_CERT"]);

pg_free_result($result_browsers);
pg_free_result($result_platforms);
pg_free_result($result_times);
pg_close($conn);

?>
</body>