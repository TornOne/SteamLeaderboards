<?php include 'pieces/head.php';?>
<link href="extras.css" rel="stylesheet" type="text/css"/>
</head>
<?php include 'pieces/header.php';?>
<?php include 'banklink/pay.php';?>



<div class="extras">
	<div class="extras_buttons">
		<div class="id_card">
			<a href="">
				<img alt="ID-Kaart" src="http://kultuuriaken.tartu.ee/sites/all/themes/kultuuriaken/assets/imgs/id-kaart.svg"/>
				<span>Enter with an ID-card</span>
			</a>
		</div>

        <div class="donate_button">
		<form method="post" action="http://localhost:3480/banklink/swedbank" id="banklink" class="donate_button">
            <?php foreach($fields as $key => $val):?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($val); ?>" />
            <?php endforeach; ?>
			<button type="submit" form="banklink">Donate!</button>
		</form>
        </div>

        <?php
            if (!isset($_GET['payment_action'])) {
                echo "Donation works if pangalink.net web server has been activated.";
            } else if ($_GET["payment_action"] == "success") {
                echo "Your donation has been received. Thank you!";
            } else if ($_GET["payment_action"] == "cancel") {
                echo "Bank transfer failed.";
            }
        ?>

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
