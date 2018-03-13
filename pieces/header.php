<body>
<div class="header">
	<div class="right_corner">
		<div class="language">
			<button class="translate" id="et"><img src="http://icons.iconarchive.com/icons/hopstarter/flag-borderless/256/Estonia-icon.png"/></button>
			<button class="translate" id="en"><img src="http://icons.iconarchive.com/icons/hopstarter/flag-borderless/256/United-Kingdom-icon.png"/></button>
		</div>

		<div id="login">
			<form action="https://steamcommunity.com/openid/login" method="post">
				<button id="loginbutton">Sign in with Steam</button>
				<input name="openid.identity" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.claimed_id" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.ns" value="http://specs.openid.net/auth/2.0" type="hidden"/>
				<input name="openid.mode" value="checkid_setup" type="hidden"/>
				<input name="openid.realm" value="" type="hidden"/> <!--Our page link?-->
				<input name="openid.return_to" value="" type="hidden"/> <!--Our page link?-->
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