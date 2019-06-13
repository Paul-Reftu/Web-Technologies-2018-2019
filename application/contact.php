<!DOCTYPE html>
<html lang="en-US">

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="Detailed information about this site."/>
	<!-- Icon obtained from: https://www.isw-online.de/praesident-trump-vs-privacy-shield/  -->
	<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="stylesheet.css" type="text/css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<?php
		session_start();
		if(isset($_SESSION['id'])){
			echo '<script src="notifications.js"></script>';
		}
	?>
</head>

<body>
	<?php 
        include("Header.php"); 
        include("Navbar.php");
    ?>

	<main>
		<section class="facilities">
		Don't hesitate to contact us at:
		<ul>
		<li><i>paul.reftu@outlook.de</i>, +40747026299 - <b>Reftu Paul Alexandru</b></li>
		<li><i>ruse.daniel.stefan@gmail.com</i>, +40756479764 - <b>Ruse Daniel Stefan</b></li>
		<li><i>flavius_petru@yahoo.com</i>, +40731266778 - <b>Popescu Flavius Petru</b></li>
		</ul>
		</section>

		<aside>
		</aside>
	</main>
	

	<?php
		include("Footer.php");
	?>
</body>

</html>