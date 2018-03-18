addEventListener("load", function() {
	if (localStorage.getItem("steamName") && localStorage.getItem("steamAvatar")) {
		var logout = document.getElementById("log out")
		logout.removeAttribute("hidden");
		logout.innerHTML = "<img alt=\"" + localStorage.getItem("steamName") + "\" src=\"" + localStorage.getItem("steamAvatar") + "\" title=\"" + localStorage.getItem("steamName") + "\"/>";
	} else {
		document.getElementById("login").removeAttribute("hidden");
	}
});