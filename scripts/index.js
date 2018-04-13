addEventListener("load", colorGames);

function colorGames() {
	var games = localStorage.getItem("steamGames");
	if (games) {
		games = games.split(",");
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
}

function loadPage(query, isNewPage) {
	var pairs = query.split("&");
	var params = {};
	for (var i = 0; i < pairs.length; i++) {
		var p = pairs[i].split("=");
		params[decodeURIComponent(p[0])] = decodeURIComponent(p[1]);
	}

	var req = new XMLHttpRequest();

	req.onload = function() {
		document.getElementById("ranking").outerHTML = this.responseText;
		colorGames();
		if (isNewPage) {
			history.pushState(params, "Page " + params["page"], "?" + query);
		}
	};

	req.open("GET", "/pieces/ranking.php?" + query);
	req.send();
}

function parseLoadPage(elem) {
	var href = elem.href;
	var queryIndex = href.indexOf("?");
	var query = queryIndex > 0 ? href.substr(queryIndex + 1) : "";
	loadPage(query, true)
}

addEventListener("popstate", function() {
	loadPage(location.search.substr(1), false);
});