<?php include 'pieces/head.php';?>
<link href="extras.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" defer="defer" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0nOA_TjOTHEdszqjnwS1DcyKFOOxX9Cg&callback=initMap"></script>
<script src="/scripts/googlemaps.js"></script>
</head>
<?php include 'pieces/header.php';?>

<div class="extras">
	<div class="extras_buttons">
		<div class="id_card">
			<a href="">
				<img alt="ID-Kaart" src="http://kultuuriaken.tartu.ee/sites/all/themes/kultuuriaken/assets/imgs/id-kaart.svg"/>
				<span>Sisene ID-kaardiga</span>
			</a>
		</div>
		
		<div class="donate_button">
			<a href="">Donate!</a>
		</div>
		
		<div class="newsletter">
			<form>
				<input type="text" placeholder="email" required="required"/>
				<input class="button" type="submit" value="Liitu uudiskirjaga"/>
			</form>
		</div>
	</div>
	
	<div id="google_maps"></div>
</div>

<?php include 'pieces/footer.php';?>
