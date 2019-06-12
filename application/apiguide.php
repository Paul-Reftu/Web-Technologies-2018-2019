<!DOCTYPE html>
<html lang="en-US">

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="A guide for Security Alerter's Developer API."/>
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
		<section class="facilities">
			<h2>Security Alerter API v1 Guide</h2>

			<h3>Currently supported HTTP requests:</h3>
			<ul>
				<li>GET requests:

					<section>
						<p> 
							The response consists of all exploits identified by given description, along with the total number of results. 

							<br/> <br/>

							GET /api/v1.php/exploits?description={<span style='color: aqua'>exploit_description</span>}[&page={<span style='color: aqua'>page_no</span>}][&format={<span style='color: aqua'>response_format</span>}]

						</p>

						<br />

						<p>
							The response is a single object representing the exploit identified by given CVE id.

							<br/> <br/>

							GET /api/v1.php/exploits/{<span style='color: aqua'>exploit_cve_id</span>}[?format={response_format}]

						</p>

						<br/>

						<p>Items within square brackets are optional. Items found in curly braces should be introduced (if their corresponding key is compulsory - i.e, not in square brackets).</p>

						<p>Currently supported response formats: json, html</p>

						<p>If no format is specified, the default provided is json.</p>
					</section>

				</li>

				<li>POST requests: (none)</li>
				<li>PUT requests: (none)</li>
				<li>DELETE requests: (none)</li>
				

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