addEventListener("DOMContentLoaded", checkLoggedIn);

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

		var wishlist = userdata["wishlist"];
		for (i = 0; i < wishlist.length; i++) {
			wishlist[i] = wishlist[i]["appid"];
		}
		localStorage.setItem("steamWishlist", wishlist);
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
	var wishlist = localStorage.getItem("steamWishlist");
	if (games || wishlist) {
		if (games) {
			games = games.split(",");
		}
		if (wishlist) {
			wishlist = wishlist.split(",");
		}
		var matches = document.querySelectorAll(".game_listing");
		for (var i = 0; i < matches.length; i++) {
			var appid = matches[i].getAttribute("href");
			appid = appid.substr(0, appid.length - 1);
			appid = appid.substr(appid.lastIndexOf("/") + 1);

			if (games && games.includes(appid)) {
				matches[i].classList.add("owned");
			} else if (wishlist && wishlist.includes(appid)) {
				matches[i].classList.add("wanted");
			}
		}
	}
}

function unColorGames() {
	var matches = document.querySelectorAll(".owned");
	for (var i = 0; i < matches.length; i++) {
		matches[i].classList.remove("owned");
	}
	matches = document.querySelectorAll(".wanted");
	for (i = 0; i < matches.length; i++) {
		matches[i].classList.remove("wanted");
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
		var searched = document.getElementsByClassName("searched");
		if (searched.length === 1) {
			searched[0].scrollIntoView({});
			setIncorrectNameField(false);
		} else if (params["name"]) {
			setIncorrectNameField(true);
		} else {
			setIncorrectNameField(false);
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

//Search stuff
addEventListener("DOMContentLoaded", fillSearchFields);
addEventListener("DOMContentLoaded", gatherTags);
addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
		search();
	}
});

var useDatePreset = false;
var allTags = [];
var selectedTags = [];

function search() {
	if (!(isSearchBottom || isSearchHidden)) {
		toggleSearchVisibility();
	}

	var query = {};

	var gameName = document.getElementById("name_field").value;
	if (gameName.length > 1) {
		query["name"] = gameName;
	}

	var tagElements = document.getElementsByClassName("search_tag");
	var tags = [];
	if (tagElements.length > 0) {
		for (var i = 0; i < tagElements.length; i++) {
			tags[i] = tagElements[i].innerHTML;
		}
		query["tags"] = tags.join();
	}

	var price = document.getElementById("price_field").value;
	if (price && /^\d{1,4}([.,]\d{1,2})?$/.test(price)) {
		query["price"] = price;
	}

	if (useDatePreset) {
		var datePresetOptions = document.getElementById("date_preset");
		query["date"] = datePresetOptions[datePresetOptions.selectedIndex].value;
	} else {
		var fromYear = document.getElementById("from_year").value;
		if (fromYear && /^\d{4}$/.test(fromYear)) {
			var fromMonth = document.getElementById("from_month").value;
			if (fromMonth && /^(0?[1-9]|1[0-2])$/.test(fromMonth)) {
				var fromDay = document.getElementById("from_day").value;
				if (!(fromDay && /^([0-2]?[1-9]|[1-3]0|31)$/.test(fromDay))) {
					fromDay = "01";
				}
			} else {
				fromMonth = "01";
				fromDay = "01";
			}
			query["from"] = fromYear + "-" + fromMonth + "-" + fromDay;
		}

		var toYear = document.getElementById("to_year").value;
		if (toYear && /^\d{4}$/.test(toYear)) {
			var toMonth = document.getElementById("to_month").value;
			if (toMonth && /^(0?[1-9]|1[0-2])$/.test(toMonth)) {
				var toDay = document.getElementById("to_day").value;
				if (!(toDay && /^([0-2]?[1-9]|[1-3]0|31)$/.test(toDay))) {
					toDay = getMonthDays(toMonth, toYear);
				}
			} else {
				toMonth = "12";
				toDay = "31";
			}
			query["to"] = toYear + "-" + toMonth + "-" + toDay;
		}
	}

	if (document.getElementById("vr_checkbox").checked) {
		query["vr"] = "t";
	}
	if (document.getElementById("windows_checkbox").checked) {
		query["win"] = "t";
	}
	if (document.getElementById("mac_checkbox").checked) {
		query["mac"] = "t";
	}
	if (document.getElementById("linux_checkbox").checked) {
		query["linux"] = "t";
	}

	var queryString = "";
	for (var key in query) {
		queryString += key + "=" + query[key] + "&";
	}
	if (queryString !== "") {
		queryString = queryString.substring(0, queryString.length - 1);
	}

	loadPage(queryString, true);
}

function fillSearchFields() {
	var params = new URLSearchParams(location.search);

	var name = params.get("name");
	if (name) {
		document.getElementById("name_field").value = name;
	}

	var tags = params.get("tags");
	if (tags) {
		tags.split(",").forEach(addTag);
	}

	var price = params.get("price");
	if (price) {
		setPrice(price);
	}

	var from = params.get("from");
	if (from) {
		setFrom(from);
	}

	var to = params.get("to");
	if (to) {
		setTo(to);
	}

	var date = params.get("date");
	if (date) {
		document.getElementById("date_preset").options.selectedIndex = ["week", "week2", "month", "season", "year"].indexOf(date) + 1;
	}

	if (params.get("vr")) {
		document.getElementById("vr_checkbox").checked = true;
	}
	if (params.get("win")) {
		document.getElementById("windows_checkbox").checked = true;
	}
	if (params.get("mac")) {
		document.getElementById("mac_checkbox").checked = true;
	}
	if (params.get("linux")) {
		document.getElementById("linux_checkbox").checked = true;
	}
}

function gatherTags() {
	allTags = document.getElementById("raw_tag_list").innerHTML.split(",");
}

function updateTagList(input) {
	if (input.value.length < 2) {
		showTagSearch(false);
		return;
	} else {
		showTagSearch(true);
	}

	var suitableTags = [];
	for (var i = 0; i < allTags.length; i++) {
		if (allTags[i].toLowerCase().includes(input.value.toLowerCase()) && !selectedTags.includes(allTags[i])) {
			suitableTags.push(allTags[i]);
			if (suitableTags.length === 5) {
				break;
			}
		}
	}

	var elementString = "";
	for (i = 0; i < suitableTags.length; i++) {
		elementString += '<div onmousedown="selectTag(this)">' + suitableTags[i] + '</div>'
	}
	document.getElementById("tag_list").innerHTML = elementString;
}

var tagSearchShown = false;
function showTagSearch(bool) {
	if (tagSearchShown && !bool) {
		document.getElementById("tag_list").setAttribute("hidden", "hidden");
		tagSearchShown = false;
	} else if (!tagSearchShown && bool) {
		document.getElementById("tag_list").removeAttribute("hidden");
		tagSearchShown = true;
	}
}

function selectTag(element) {
	addTag(element.innerHTML);
	document.getElementById("tag_field").value = "";
}

function addTag(name) {
	document.getElementById("tags").innerHTML += '<div class="search_tag" onclick="remove(this)">' + name + '</div>';
}

function remove(element) {
	element.outerHTML = "";
}

function setPrice(price) {
	document.getElementById("price_field").value = price;
}

function setFrom(date) {
	date = date.split("-");
	document.getElementById("from_year").value = date[0];
	document.getElementById("from_month").value = date[1];
	document.getElementById("from_day").value = date[2];
}

function setTo(date) {
	date = date.split("-");
	document.getElementById("to_year").value = date[0];
	document.getElementById("to_month").value = date[1];
	document.getElementById("to_day").value = date[2];
}

function setDatePreset(bool) {
	useDatePreset = bool && document.getElementById("date_preset").options.selectedIndex !== 0;
}

function getMonthDays(m, y) {
	m = parseInt(m);
	y = parseInt(y);
	if (m in [1, 3, 5, 7, 8, 10, 12]) {
		return 31;
	} else if (m === 2) {
		if (y % 4 === 0 && (y % 100 !== 0 || y % 400 === 0)) {
			return 29;
		} else {
			return 28;
		}
	} else {
		return 30;
	}
}

var isNameIncorrect = false;
function setIncorrectNameField(bool) {
	if (bool) {
		var name_e = document.getElementById("name_field");
		name_e.value = "";
		if (!isNameIncorrect) {
			isNameIncorrect = true;
			name_e.placeholder = "No such game found";
			name_e.classList.add("error");
		}
	} else {
		if (isNameIncorrect) {
			isNameIncorrect = false;
			name_e = document.getElementById("name_field");
			name_e.placeholder = "Find a game's ranking by name";
			name_e.classList.remove("error");
		}
	}
}

//Search box visibility on window resize
addEventListener("DOMContentLoaded", updateSearchVisibility);
addEventListener("resize", updateSearchVisibility);

var isSearchBottom = false;
var isSearchHidden = true;

function updateSearchVisibility() {
	if (window.innerWidth / parseFloat(getComputedStyle(document.body).fontSize) <= 55) {
		if (isSearchBottom) {
			isSearchBottom = false;
			if (isSearchHidden) {
				document.getElementById("search").setAttribute("hidden", "hidden");
			}
		}
	} else {
		if (!isSearchBottom) {
			isSearchBottom = true;
			if (isSearchHidden) {
				document.getElementById("search").removeAttribute("hidden");
			}
		}
	}
}

function toggleSearchVisibility() {
	if (isSearchHidden) {
		isSearchHidden = false;
		document.getElementById("search").removeAttribute("hidden");
	} else {
		isSearchHidden = true;
		document.getElementById("search").setAttribute("hidden", "hidden");
	}
}