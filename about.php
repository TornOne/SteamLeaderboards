<?php include 'pieces/head.php';?>
<link href="about.css" rel="stylesheet" type="text/css"/>
</head>
<?php include 'pieces/header.php';?>

<div class="about">
	<h2>What is this site?</h2>
	<p>Steam Leaderboards keeps a constantly updated ranking of all* games on Steam. I created this site due to my desire to keep finding good games that come out. Because Steam gets thousands of new games every year, it has become very difficult to track everything worth playing. This tool lists the tags, prices, release dates, platforms, and most importantly, user review scores, of all the games.</p>
	<p>While all the other attributes can be used to filter games according to your preferences, the ranking of the games is decided by a custom algorithm I specifically made for the purpose of ranking Steam's games. You can read more about the algorithm in <a href="https://steamdb.info/blog/steamdb-rating/" rel="noopener noreferrer" target="_blank">the post I wrote to SteamDB</a> about it. But the gist of it is that the score of a game is higher the larger the percentage of positive reviews on the game, and the more overall reviews the game has.</p>

	<h2>Known problems</h2>
	<ul>
		<li>The ranking is limited to 9750 games instead of all the games on Steam.
			<p>This is due to database limitations on the free server I'm hosting my site on. Rest assured - the 9750 games are the best ones out of Steam's ~15K, so no good games will be missed in the near future. This number is growing fast though, so I might have to find a new server in a few years' time.</p></li>
		<li>When filtering by tags, there are some games that do not belong.
			<p>This is an inherent problem of the tags being decided by users. There will always be people going against the system and applying joke-tags. Sadly, I have no way to automatically understand which tags don't belong. Overall, the filtering results should, for the most part, be the same as on Steam, imperfect as they are.</p></li>
		<li>Some games have a different amount of reviews on Steam.
			<p>There's a small fraction of games (less than 1%) that, for some reason, show different data in the API, where I get the review counts from, and on the store page. Luckily, this mostly only happens for games with an undefined price, so you should be unlikely to encounter the problem on games you might be interested in.</p></li>
		<li>Tags, prices, scores, or even all the games on the ranking are gone.
			<p>This is a very unfortunate issue that might arise very infrequently in some state. The reason is a combination of Steam's servers being uncooperative and not reporting some data or reporting the wrong data, and my software's less-than-professional error recognition. The issue usually fixes itself the day after, when the next update happens.</p></li>
		<li>The last successful update was very long ago.
			<p>Again, due to server limitations, I can only trigger an update when someone visits the site. If no one has been here for a long while, the rankings will be outdated for about 15 minutes while they are updated. Alternatively, it is possible that the update failed, in which case it should probably try again and succeed the next day. Failing multiple days in a row is an indication that Steam's undergone changes and the updater might need to be reworked. Hopefully this does not happen.</p></li>
		<li>The page is down.
			<p>While you could not see this answer, were the page down, I can anticipate the page will be down on the last day or few of every month. This is a free server, and I have a limited amount of hours I can keep it running every month. The page has been increasing in popularity over the years, and it's reaching the point where it's visited on enough hours of the day that I'm starting to run out of free hours towards the end of the month. But this is a good thing, showing there is interest for game rankings. I would be glad to upgrade out of this necessity, but I first need to find the time to do so. Hopefully I will, soon.<p></li>
	</ul>

	<h2>What does logging in do?</h2>
	<p>Logging in through Steam allows me to fetch the games in your library and on your wishlist and display that info in the ranking for you. This only works if your profile is set to public though, which you can edit from <a href="https://steamcommunity.com/my/profile" rel="noopener noreferrer" target="_blank">your profile</a> > Edit Profile > My Privacy Settings. All your info is actually stored on your computer, and everything is deleted if you choose to log out. You can refresh manually once a day, or automatically once a week.</p>

	<h2>Can I contact you somehow?</h2>
	<p>First of all, it's worth noting that I am not affiliated with Steam or Valve. Secondly, since this is just a small site made as a hobby, I'm only going to disclose my online persona at this time. Feel free to add me through <a href="https://steamcommunity.com/id/tornone/" rel="noopener noreferrer" target="_blank">my Steam page</a> if you have something worthwhile to discuss. Questions are also okay.</p>

	<h2>I would really like this new feature added</h2>
	<p>I'm sure there's a lot of things this site <i>could</i> be, but I have limited time, resources, and desires, so there's only so much the site <i>is going to</i> be. I'm afraid most feature requests would be denied as I'm not really looking to expand the functionality of the site. Perhaps the only planned feature (provided I get a new server someday) would be a history of the scores of all the games.</p>
</div>

<div class="footer"></div>
</body>
</html>
