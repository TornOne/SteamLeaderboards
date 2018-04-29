window.onload = function() {
	var pairs = location.search.substr(1).split("&");
	var params = {};
	
	for (var i = 0; i < pairs.length; i++) {
		var p = pairs[i].split("=");
		params[p[0]] = p[1];
	}
	
	if ("openid.identity" in params) {
		var req = new XMLHttpRequest();
		
		req.onload = function() {
			var userdata = JSON.parse(this.responseText);

			localStorage.setItem("refreshTime", Date.UTC(2000));
			localStorage.setItem("steamId", userdata["user"]["response"]["players"]["0"]["steamid"]);
			localStorage.setItem("steamName", userdata["user"]["response"]["players"]["0"]["personaname"]);
			localStorage.setItem("steamAvatar", userdata["user"]["response"]["players"]["0"]["avatarmedium"]);

			var games = userdata["games"]["response"]["games"];
			for (var i = 0; i < games.length; i++) {
				games[i] = games[i]["appid"];
			}
			localStorage.setItem("steamGames", games);

			var wishlist = userdata["wishlist"];
			for (var i = 0; i < wishlist.length; i++) {
				wishlist[i] = wishlist[i]["appid"];
			}
			localStorage.setItem("steamWishlist", wishlist);

			location.replace(decodeURIComponent(params["return_to"]));
		};
		
		req.open("GET", "http://steamleaderboards.herokuapp.com/login/fetchUserData.php?id=" + params["openid.identity"].substr(params["openid.identity"].lastIndexOf("%2F") + 3));
		req.send();
	} else {
		if ("return_to" in params) {
			location.replace(decodeURIComponent(params["return_to"]));
		} else {
			location.replace("/");
		}
	}
};