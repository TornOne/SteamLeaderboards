<body>
<div class="header">
	<div class="right_corner">
		<div class="language">
			<button class="translate" id="et"></button>
			<button class="translate" id="en"></button>
		</div>
		
		<div id="login" hidden="hidden">
			<form action="https://steamcommunity.com/openid/login" method="post">
				<button id="loginbutton">Sign in with Steam</button><!--https://steamcdn-a.akamaihd.net/steamcommunity/public/images/steamworks_docs/english/sits_small.png-->
				<input name="openid.ns" value="http://specs.openid.net/auth/2.0" type="hidden"/>
				<input name="openid.mode" value="checkid_setup" type="hidden"/>
				<input name="openid.claimed_id" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.identity" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.return_to" value="http://steamleaderboards.herokuapp.com/login/login.php?return_to=<?=urlencode($_SERVER["REQUEST_URI"])?>" type="hidden"/>
				<input name="openid.realm" value="http://steamleaderboards.herokuapp.com/" type="hidden">
			</form>
		</div>
		
		<div id="log out" hidden="hidden">
		</div>
	</div>
	
	<div class="title">
		<a href="index.php">Steam Leaderboards</a>
	</div>

    <div class="navigation_bar">
		<a href="index.php" class="trl" data-info="ranking">Ranking</a>
		<a href="stats.php" class="trl" data-info="stats">Stats</a>
        <a href="about.php" class="trl" data-info="about">About</a>
        <a href="extras.php" class="trl" data-info="extra">Extras</a>
    </div>
</div>