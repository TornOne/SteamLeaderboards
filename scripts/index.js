addEventListener("load", checkLoggedIn);

function checkLoggedIn() {
	if (localStorage.getItem("refreshTime") && localStorage.getItem("steamId") && localStorage.getItem("steamName") && localStorage.getItem("steamAvatar")) {
		document.getElementById("logout").removeAttribute("hidden");
		updateAvatar();
		colorGames();
		if (Date.now() - localStorage.getItem("refreshTime") > 604800000) { //Automatic refresh after a week
			refresh();
		}
	} else {
		document.getElementById("login").removeAttribute("hidden");
	}
}

function updateAvatar() {
	var avatar = document.querySelector("#logout img");
	avatar.alt = localStorage.getItem("steamName");
	avatar.src = localStorage.getItem("steamAvatar");
	avatar.title = localStorage.getItem("steamName");
}

function refresh() {
	if (Date.now() - localStorage.getItem("refreshTime") > 86400000) { //At most one refresh per day
		login();
		updateAvatar();
		unColorGames();
		colorGames();
	}
}

function login() {
	var req = new XMLHttpRequest();

	req.onload = function() {
		var userdata = JSON.parse(this.responseText);
		localStorage.setItem("refreshTime", Date.now());
		localStorage.setItem("steamName", userdata["user"]["response"]["players"]["0"]["personaname"]);
		localStorage.setItem("steamAvatar", userdata["user"]["response"]["players"]["0"]["avatarmedium"]);
		var games = userdata["games"]["response"]["games"];
		for (var i = 0; i < games.length; i++) {
			games[i] = games[i]["appid"];
		}
		localStorage.setItem("steamGames", games);
	};

	req.open("GET", "http://steamleaderboards.herokuapp.com/login/fetchUserData.php?id=" + localStorage.getItem("steamId"));
	req.send();
}

function logout() {
	localStorage.clear();
	unColorGames();
	document.getElementById("logout").setAttribute("hidden", "hidden");
	document.getElementById("login").removeAttribute("hidden");
}

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

function unColorGames() {
	var matches = document.querySelectorAll(".owned");
	for (var i = 0; i < matches.length; i++) {
		matches[i].classList.remove("owned");
	}
}

//Page switching stuff

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