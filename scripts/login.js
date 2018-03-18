window.onload = function() {
	var parts = location.search.substr(1).split("&");
	var params = {};
	
	for (i = 0; i < parts.length; i++) {
		p = parts[i].split("=");
		params[p[0]] = p[1];
	}
	
	if ("openid.identity" in params) {
		var req = new XMLHttpRequest();
		
		req.onload = function() {
			userdata = JSON.parse(this.responseText);
			localStorage.setItem("steamName", userdata["user"]["response"]["players"]["0"]["personaname"]);
			localStorage.setItem("steamAvatar", userdata["user"]["response"]["players"]["0"]["avatar"]);
			var games = userdata["games"]["response"]["games"];
			for (i = 0; i < games.length; i++) {
				games[i] = games[i]["appid"];
			}
			localStorage.setItem("steamGames", games);
			location.replace(params["return_to"]);
		}
		
		req.open("GET", "http://steamleaderboards.herokuapp.com/login/fetchUserData.php?id=" + params["openid.identity"].substr(params["openid.identity"].lastIndexOf("%2F") + 3));
		req.send();
	} else {
		if ("return_to" in params) {
			location.replace(params["return_to"]);
		} else {
			location.replace("/");
		}
	}
}