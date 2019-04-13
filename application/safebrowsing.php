<!DOCTYPE html>
<html>

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width-device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="A tool that helps you find out if an external website is known to be malicious or not, by checking whether its address is present in Google's Database of blacklisted web resources."/>
	<!-- Icon obtained from: https://www.isw-online.de/praesident-trump-vs-privacy-shield/  -->
	<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="stylesheet.css" type="text/css"/>
	<link rel="stylesheet" href="aboutstylesheet.css" type="text/css"/>
</head>

<body>
	<?php 
        include("Header.php"); 
        include("Navbar.php");
    ?>

	<main>
		<br>
		<section class="facilities">
			<h2>See if the website you may want to visit is safe!</h2>

			<form>
				Type in the website URL: <br/>
				<input class="searchbar" type="text" name="websiteurl" autofocus/>
				<input class="button" type="submit" value="Check"/> <br/> <br/>
			</form>
		</section>

		<aside>
		</aside>
	</main>

	<?php
		include("Footer.php");
	?>
</body>

</html>