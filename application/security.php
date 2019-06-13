<!DOCTYPE html>
<html>

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width-device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="The Web App Security Alerter is meant to serve as a guide to achieving better security inside and outside of the Internet, to protect user's private data across all domains and to instruct developers on how to engineer their own secure applications."/>
	<!-- Icon obtained from: https://www.isw-online.de/praesident-trump-vs-privacy-shield/  -->
	<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="stylesheet.css" type="text/css"/>
	<link rel="stylesheet" href="aboutstylesheet.css" type="text/css"/>
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
	
		<img src="assets/images/security.jpg" alt= "security" style ="width:100%; height:100%;">
		
		<section class="facilities">
			<h2>
			<b>Find information that is most relevant to you!</b>
			</h2>
			<!-- <form> -->
			Search for articles (you can use keywords like title, author's name, the date when the article was published etc.):<br>
			<br>
			
			<?php

				require_once("security_search/SecuritySearch.php");

				new SecuritySearch();

			?>

			<!--
			<input class="searchbar" type="text" name="search"> <input class="button" type="button" value="Search">
			</form>
			-->
		</section>

		<aside>
		</aside>
	</main>
	
	<?php
		include("Footer.php");
	?>
</body>

</html>
