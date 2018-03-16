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

$conn = pg_connect(getenv("DATABASE_URL"));
pg_query_params($conn, 'SELECT insert_bb($1, $2, $3);', array($browser, $requestTime, $platform));
pg_close($conn);
?>