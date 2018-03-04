<!DOCTYPE html>
<html>
<head>
    <title>PHP test</title>
</head>
<body>

<?php
require_once 'vendor/autoload.php';

use BrowscapPHP\Browscap;
$cacheDir = 'vendor/browscap/browscap-php/resources';
$fileCache = new \Doctrine\Common\Cache\FilesystemCache($cacheDir);
$cache = new \Roave\DoctrineSimpleCache\SimpleCacheAdapter($fileCache);

$logger = new \Monolog\Logger('logger');

$bc = new Browscap($cache, $logger);
$result = $bc->getBrowser();
var_dump($result);
?>
<br><br>
<?php
echo $result->browser;
?>

</body>
</html>