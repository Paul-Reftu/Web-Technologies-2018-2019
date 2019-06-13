<!DOCTYPE html>
<html lang="en-US">

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width-device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="Detailed information about this site."/>
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
		
		<section class="facilities">
		<p>This site is supposed to properly educate its users about software security and software vulnerabilities through a set of tools and tutorials.</p>
		
		These are the features this site is going to provide for you:
		<ul>
		<li><i>Security</i> section: a list of popular articles about security, the option to search for certain articles</li>
		<li><i>Software Vulnerabilities</i> section: a list of the currently known vulnerabilities, the option to search by keywords (company's name, the year when the exploit was descovered etc.)</li>
		<li><i>'Hackproof' Programming</i> section: tutorials on how to program in order to efficiently avoid software weaknesses</li>
		<li>
			<em>Security Tools</em> section:
			<ol>
			<li>Safe browsing tool - it permits verifying if a site is safe</li>
			<li>HaveIBeenPwned tool - it allows the user to check if a certain e-mail address was hacked</li>
			</ol>
		</li>
		</ul>
		<p>This application was realised as a student project for the "Web Tehnologies" Course at the University "Alexandru Ioan Cuza" of Iasi, Faculty of Computer Science.</p>
		<p>Authors: Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius Petru</p>
		
		</section>

		<aside>
		</aside>
	</main>

	<?php
		include("Footer.php");
	?>
</body>

</html>