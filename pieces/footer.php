<div class="footer">
	<a href="/about.php">About this page</a>
	<br/>
    Page generated in <?=number_format(microtime(true) - $load_start_time, 3)?>s
	<br/>
	<?php
	function secondsToHuman($ss) {
		$s = $ss % 60;
		$m = $ss / 60 % 60;
		$h = $ss / 3600 % 60;
		$d = $ss / 86400 % 60;
		return ($d == 0 ? "" : "${d}d, ") . ($h == 0 ? "" : "${h}h, ") . ($m == 0 ? "" : "${m}m, ") . "${s}s";
	}

	$last_refresh = time() - strtotime(pg_fetch_result(pg_query($conn, "SELECT value FROM config_dates WHERE key='completed_refresh_time';"), 0, 0));
	?>
	Last update completed <?=secondsToHuman($last_refresh)?> ago
	<br/>
	Next update in <?=secondsToHuman($next_refresh)?>
</div>
</body>
</html>