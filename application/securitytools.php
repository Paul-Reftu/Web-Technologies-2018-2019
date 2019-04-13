<!DOCTYPE html>
<html>

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width-device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="Sometimes plain information is not enough to secure your experience overall on the Internet and beyond. That is where certain security tools come into play - tools which put that information to good use in order to offer us a certain service that enhances our cybersafety."/>
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
		<article>
			<!-- Image source: https://www.matrix42.com/blog/de/2018/03/09/automated-endpoint-security-vermeiden-sie-wunde-punkte-2/ -->
			<img src="assets/images/securitytools_img_1.jpg"/ height=100% width=100%>
			<section class="facilities">	
				<h2>Security Tools</h2>

				<p>Sometimes plain information is not enough to secure your experience overall on the Internet and beyond. That is where certain security tools come into play - tools which put that information to good use in order to offer us a certain service that enhances our cybersafety.</p>
			</section>

			<section class="facilities">
				<h3>Safe Browsing Tool</h3>

				<p>A tool that helps you find out if an external website is known to be malicious or not, by checking whether its address is present in Google's Database of blacklisted web resources.</p>

				<p><a href="safebrowsing.php">Try out Safe Browsing now!</a></p>

				<h3>HaveIBeenPwned Tool</h3>

				<p>The World Wide Web - and - more specifically - certain vital parts of it such as Online Bank Services, Online Stores and so on...are always under constant threat of security breaches. What this means is that your personal data - if you decided to use that specific web service - could always be exposed to cyber criminals.</p>

				<p>This tool helps you find out which of these web services has had a security breach since the time you first signed up for them, to give you an idea whether you ought to seriously consider changing certain sensitive data such as your password or not.</p>

				<p><a href="haveibeenpwned.php">Try out HaveIBeenPwned now!</a></p>
			</section>

		</article>

		<aside>
		</aside>
	</main>

	<?php
		include("Footer.php");
	?>
</body>

</html>