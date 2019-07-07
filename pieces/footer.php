<div class="footer">
	<a href="/about.php">About this page</a>
	<br/>
	<?php
	function secondsToHuman($ss) {
		$s = $ss % 60;
		$m = $ss / 60 % 60;
		$h = $ss / 3600 % 24;
		$d = $ss / 86400 % 60;
		return ($d == 0 ? "" : "${d}d, ") . ($h == 0 ? "" : "${h}h, ") . ($m == 0 ? "" : "${m}m, ") . "${s}s";
	}

	$last_refresh = time() - strtotime(pg_fetch_result(pg_query($conn, "SELECT value FROM config_dates WHERE key='completed_refresh_time';"), 0, 0));
	?>
	Last update completed <?=secondsToHuman($last_refresh)?> ago
	<br/>
	<i style="font-size: 0.9em">I am aware of the consistently failing updates and have looked into the issue. Sadly, this is not a fix I can make in just a few hours.<br/>
	I am working on a full rewrite for the site along with a migration to a paid server, hopefully in 2019. This site has not been forgotten.</i>
	<br/>
	Next update in <?=secondsToHuman($next_refresh)?>
	<br/>
	Page generated in <?=number_format(microtime(true) - $load_start_time, 3)?>s
</div>
</body>
</html>