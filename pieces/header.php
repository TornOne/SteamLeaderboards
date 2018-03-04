<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SteamLeaderboards</title>
    <link href="pieces/main.css" rel="stylesheet" type="text/css">
	<link href="index.css" rel="stylesheet" type="text/css">
	<link href="about.css" rel="stylesheet" type="text/css">
	<link href="extras.css" rel="stylesheet" type="text/css">
	<!--Potentially omit the head from the header to define each page's css, title, etc. separately.-->
</head>

<body>
<div class="header">
	<div class="right_corner">
		<div class="language">
			<a href=""><img src="http://icons.iconarchive.com/icons/hopstarter/flag-borderless/256/Estonia-icon.png"></a>
			<a href=""><img src="http://icons.iconarchive.com/icons/hopstarter/flag-borderless/256/United-Kingdom-icon.png"></a>
		</div>

		<div id="login">
			<form action="https://steamcommunity.com/openid/login" method="post">
				<button id="loginbutton">Sign in with Steam</button>
				<input name="openid.identity" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden">
				<input name="openid.claimed_id" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden">
				<input name="openid.ns" value="http://specs.openid.net/auth/2.0" type="hidden">
				<input name="openid.mode" value="checkid_setup" type="hidden">
				<input name="openid.realm" value="" type="hidden"> <!--Our page link?-->
				<input name="openid.return_to" value="" type="hidden"> <!--Our page link?-->
			</form>
		</div>
	</div>
	
	<div class="title">
		<a href="index.php">Steam Leaderboards</a>
	</div>

    <div class="navigation_bar">
		<a href="index.php">Ranking</a>
        <a href="about.php">About</a>
        <a href="extras.php">Extras</a>
    </div>
</div>