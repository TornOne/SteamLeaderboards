<body>
<div class="header" itemscope itemtype="http://schema.org/WebPage">
	<div class="right_corner">
		<div id="login" hidden="hidden">
			<form action="https://steamcommunity.com/openid/login" method="post">
				<button id="loginbutton" itemprop="url">Sign in with Steam</button><!--https://steamcdn-a.akamaihd.net/steamcommunity/public/images/steamworks_docs/english/sits_small.png-->
				<input name="openid.ns" value="http://specs.openid.net/auth/2.0" type="hidden"/>
				<input name="openid.mode" value="checkid_setup" type="hidden"/>
				<input name="openid.claimed_id" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.identity" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.return_to" value="http://steamleaderboards.herokuapp.com/login/login.php?return_to=<?=urlencode($_SERVER["REQUEST_URI"])?>" type="hidden"/>
				<input name="openid.realm" value="http://steamleaderboards.herokuapp.com/" type="hidden">
			</form>
		</div>
		
		<div id="logout" hidden="hidden">
		</div>
	</div>
	
	<div class="title">
		<a href="/index.php" rel="next" itemprop="headline">Steam Leaderboards</a>
	</div>

    <div class="navigation_bar">
		<a href="/index.php" rel="next" itemprop="url">Ranking</a>
        <a href="/about.php" rel="next" itemprop="url">About</a>
        <a href="/extras.php" rel="next" itemprop="url">Extras</a>
    </div>
</div>