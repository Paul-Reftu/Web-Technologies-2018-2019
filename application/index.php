<!DOCTYPE html>
<html lang="en-US">

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="The Web App Security Alerter is meant to serve as a guide to achieving better security inside and outside of the Internet, to protect user's private data across all domains and to instruct developers on how to engineer their own secure applications."/>
	<!-- Icon obtained from: https://www.isw-online.de/praesident-trump-vs-privacy-shield/  -->
	<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="stylesheet.css" type="text/css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<?php
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
		<section class="slideshow-container">
			<!-- Image source: https://www.telindus.nl/mircosegmentatie-software-defined-datacenter/ -->
			<div id="slide">
				<h2>Protect what is yours.</h2>
				<img src="assets/images/slide_1.jpg" alt="First image of the slideshow: a lock sitting on a keyboard"/>
			</div>
		</section>

		<aside>
		</aside>
	</main>

	<?php
		include("Footer.php");
	?>
	
	<!-- Page ought to load fully before we attempt any dynamic modifications -->
	<script src="script.js" type="text/javascript"></script>
</body>

</html>