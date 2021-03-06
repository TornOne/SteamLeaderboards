<body>
<div class="header">
	<div class="right_corner">
		<div id="login" hidden="hidden">
			<form action="https://steamcommunity.com/openid/login" method="post">
				<button id="login_button"><img alt="Sign in through Steam" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/steamworks_docs/english/sits_small.png"/></button>
				<input name="openid.ns" value="http://specs.openid.net/auth/2.0" type="hidden"/>
				<input name="openid.mode" value="checkid_setup" type="hidden"/>
				<input name="openid.claimed_id" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.identity" value="http://specs.openid.net/auth/2.0/identifier_select" type="hidden"/>
				<input name="openid.return_to" value="http://steamleaderboards.herokuapp.com/login/login.php?return_to=<?=urlencode($_SERVER["REQUEST_URI"])?>" type="hidden"/>
				<input name="openid.realm" value="http://steamleaderboards.herokuapp.com/" type="hidden">
			</form>
		</div>
		
		<div id="logout" hidden="hidden">
			<img alt="" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" title=""/>
			<div class="dropdown">
				<a href="javascript:refresh()">Refresh</a>
				<a href="javascript:logout()">Logout</a>
			</div>
		</div>
	</div>
	
	<div class="title">
		<a href="/">Steam Leaderboards</a>
	</div>
</div>