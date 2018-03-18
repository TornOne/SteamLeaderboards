<?php
if (isset($_GET["openid.identity"])) {
	$id = strrchr($_GET["openid.identity"], "/");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>SteamLeaderboards</title>
</head>
<body>
<?=$id?>
</body>
</html>