addEventListener("load", function() {
	var games = localStorage.getItem("steamGames").split(",");
	if (games) {
		var matches = document.querySelectorAll(".game_listing");
		for (var i = 0; i < matches.length; i++) {
			var appid = matches[i].getAttribute("href");
			appid = appid.substr(0, appid.length - 1);
			appid = appid.substr(appid.lastIndexOf("/") + 1);
			
			if (games.includes(appid)) {
				matches[i].classList.add("owned");
			}
		}
	}
});