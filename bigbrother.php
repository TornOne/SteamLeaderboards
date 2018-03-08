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

pg_prepare($conn, "get_data", 'SELECT * FROM $1;');
$result_browsers = pg_execute($conn, "get_data", array("bb_browsers"));

var_dump(pg_fetch_all($result_browsers));
echo nl2br("\n\n");
print_r(pg_fetch_all($result_browsers));

pg_free_result($result_browsers);
pg_close($conn);

?>
</body>