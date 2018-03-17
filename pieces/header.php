<?php
require "steamauth/steamauth.php";
require "steamauth/userInfo.php";

if (isset($_SESSION["steamid"])) {
	$id = $_SESSION["steamid"];
} else {
	//Not logged in
}
?>
<body>
<div class="header">
	<div class="right_corner">
		<div class="language">
			<button class="translate" id="et"/>
			<button class="translate" id="en"/>
		</div>

		<?php if (isset($_SESSION["steamid"])):?>
		<?=$steamprofile["personaname"]?>
		<?php endif; ?>
		<?=loginbutton()?>
		<div id="login">
			<form action="https://steamcommunity.com/openid/login" method="post">
				<button id="loginbutton">Sign in with Steam</button><!--https://steamcdn-a.akamaihd.net/steamcommunity/public/images/steamworks_docs/english/sits_small.png-->
				<input name="openid.identity" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.claimed_id" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.ns" value="http://specs.openid.net/auth/2.0" type="hidden"/>
				<input name="openid.mode" value="checkid_setup" type="hidden"/>
				<input name="openid.return_to" value="http://steamleaderboards.herokuapp.com/" type="hidden"/> <!--Our page link?-->
			</form>
		</div>
	</div>
	
	<div class="title">
		<a href="index.php">Steam Leaderboards</a>
	</div>

    <div class="navigation_bar">
		<a href="index.php" class="trl" key="ranking">Ranking</a>
		<a href="stats.php" class="trl" key="stats">Stats</a>
        <a href="about.php" class="trl" key="about">About</a>
        <a href="extras.php" class="trl" key="extra">Extras</a>
    </div>
</div>