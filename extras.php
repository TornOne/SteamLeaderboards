<?php include 'pieces/head.php';?>
<link href="extras.css" rel="stylesheet" type="text/css"/>
</head>
<?php include 'pieces/header.php';?>

<div class="extras">
	<div class="extras_buttons">
		<div class="id_card">
			<a href="">
				<img alt="ID-Kaart" src="http://kultuuriaken.tartu.ee/sites/all/themes/kultuuriaken/assets/imgs/id-kaart.svg"/>
				<span>Enter with an ID-card</span>
			</a>
		</div>
		
		<div class="donate_button">
			<a href="">Donate!</a>
		</div>

        <div class="popup" onclick="activatePopup()"><img src="https://image.flaticon.com/icons/svg/54/54591.svg" alt="question mark symbol">
            <span class="popuptext" id="myPopup">Interactive help information!</span>
        </div>
		
		<div class="newsletter">
			<form>
				<label for="newsletter_email">Email:</label>
				<input type="text" id="newsletter_email" required="required"/>
				<input class="button" type="submit" value="Get the newsletter"/>
			</form>
		</div>
	</div>
</div>

<?php include 'pieces/footer.php';?>
