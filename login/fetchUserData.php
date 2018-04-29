<?php
$key = getenv("STEAM_WEBAPI_KEY");
if (isset($_GET["id"])) {
	$user = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$key&steamids=" . $_GET["id"]);

	$games = file_get_contents("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=$key&steamid=" . $_GET["id"] . "&include_played_free_games=true");

	$wishlist = file_get_contents("https://store.steampowered.com/wishlist/profiles/" . $_GET["id"]);
	$wishlist = substr($wishlist,strpos($wishlist, "var g_rgWishlistData = [") + 23);
	$wishlist = substr($wishlist, 0, strpos($wishlist, "]") + 1);

	echo "{ \"user\": $user, \"games\": $games, \"wishlist\": $wishlist }";
}
