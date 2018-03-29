<?php
$key = getenv("STEAM_WEBAPI_KEY");
if (isset($_GET["id"])) {
	echo "{ \"user\": ", file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $key . "&steamids=" . $_GET["id"]), ", \"games\": ", file_get_contents("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" . $key . "&steamid=" . $_GET["id"] . "&include_played_free_games=true"), "}";
}
