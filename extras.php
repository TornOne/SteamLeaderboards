<?php include 'pieces/head.php';?>
<link href="extras.css" rel="stylesheet" type="text/css"/>
<script defer="defer" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0nOA_TjOTHEdszqjnwS1DcyKFOOxX9Cg&callback=initMap"></script>
<script src="/scripts/googlemaps.js"></script>
</head>
<?php include 'pieces/header.php';?>

<div class="extras">
	<div class="extras_buttons">
		<div class="id_card">
			<a href="">
				<img alt="ID-Kaart" src="http://kultuuriaken.tartu.ee/sites/all/themes/kultuuriaken/assets/imgs/id-kaart.svg"/>
				<span>Enter with an ID-card</span> <!--Sisene ID-kaardiga-->
			</a>
		</div>
		
		<div class="donate_button">
			<a href="">Donate!</a>
		</div>
		
		<div class="newsletter">
			<form>
				<label for="email">Email:</label>
				<input type="text" name="email" required="required"/>
				<input class="button" type="submit" value="Get the newsletter"/> <!--Liitu uuskirjaga-->
			</form>
		</div>
	</div>
	
	<div id="google_maps"></div>
</div>

<?php include 'pieces/footer.php';?>
