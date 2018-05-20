<?php
$conn = pg_connect(getenv("DATABASE_URL"));
$next_refresh = strtotime(pg_fetch_result(pg_query($conn, "SELECT value FROM config_dates WHERE key='attempted_refresh_time';"), 0, 0)) + 86400 - time(); //Add a day to the last refresh
if ($next_refresh < 0) {
	$return_code = exec("python scraper/Start.py");
	if ($return_code == "201 Created") {
		pg_query($conn, 'SELECT update_attempted_refresh_time();');
	} else {
		pg_query($conn, 'SELECT update_failed_refresh_time();');
	}
}
?>
<?php include 'pieces/head.php'; ?>
<script src="/scripts/index.js"></script>
<link href="index.css" rel="stylesheet" type="text/css"/>
</head>
<?php include 'pieces/header.php'; ?>

<div class="main">
	<div id="search">
		<div class="search_header">
			<input id="name_field" placeholder="Search for a game"/>
			<input id="search_button" type="button" value="Search" onclick="search()"/>
		</div>

		<div class="search_block_header">Filter by tags</div>
		<div class="search_block">
			<div id="tags"></div>
			<div class="tag_field_class"><input id="tag_field" placeholder="Search for tags"/></div>
		</div>

		<div class="search_block_header">Filter by price</div>
		<div class="search_block">
			<input id="price_field" type="text" placeholder="Max" pattern="\d{1,4}([\.,]\d{1,2})?"/>
			<input id="price_0_button" type="button" value="Free" onclick="setPrice(0)"/>
			<input id="price_10_button" type="button" value="10$" onclick="setPrice(10)"/>
			<input id="price_20_button" type="button" value="20$" onclick="setPrice(20)"/>
		</div>

		<div class="search_block_header">Filter by release date</div>
		<div class="search_block">
			<span class="date_text">From:</span>
			<input id="from_day" type="text" placeholder="dd" pattern="([0-2]?[1-9])|([1-3]0)|31" onchange="setDatePreset(false);"/>
			<input id="from_month" type="text" placeholder="mm" pattern="(0?[1-9])|(1[0-2])" onchange="setDatePreset(false);"/>
			<input id="from_year" type="text" placeholder="yyyy" pattern="\d{1,4}" onchange="setDatePreset(false);"/>
			<br/>
			<span class="date_text">To:</span>
			<input id="to_day" type="text" placeholder="dd" pattern="([0-2]?[1-9])|([1-3]0)|31" onchange="setDatePreset(false);"/>
			<input id="to_month" type="text" placeholder="mm" pattern="(0?[1-9])|(1[0-2])" onchange="setDatePreset(false);"/>
			<input id="to_year" type="text" placeholder="yyyy" pattern="\d{1,4}" onchange="setDatePreset(false);"  />
			<span class="date_text"></span>
			<select id="date_preset" onchange="setDatePreset(true);">
				<option value="placeholder">Select a preset</option>
				<option value="week">Past week</option>
				<option value="week2">A week old</option>
				<option value="month">Past month</option>
				<option value="season">Past season</option>
				<option value="year">Past year</option>
			</select>
		</div>

		<div class="search_block_header">Filter by platform</div>
		<div class="search_block">
			<label class="checkbox">
				<input id="vr_checkbox" type="checkbox"/>VR
				<span class="check_mark"></span>
			</label>
			<label class="checkbox">
				<input id="windows_checkbox" type="checkbox"/>Windows
				<span class="check_mark"></span>
			</label>
			<label class="checkbox">
				<input id="mac_checkbox" type="checkbox"/>Mac
				<span class="check_mark"></span>
			</label>
			<label class="checkbox">
				<input id="linux_checkbox" type="checkbox"/>Linux
				<span class="check_mark"></span>
			</label>
		</div>
	</div>
    <?php include 'pieces/ranking.php'; ?>
</div>

<?php include 'pieces/footer.php'; ?>
