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
			<!-- malware.testing.google.test/testing/malware/ -->

			<form action="safebrowsing.php" method="post">
				Type in the website URL: <br/>
				<input class="searchbar" type="text" name="websiteurl" autofocus/>
				<input class="button" type="submit" value="Check"/> <br/> <br/>
			</form>
		</section>

		<aside>
		</aside>
	</main>

	<?php
	if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['websiteurl']))
	{
		check();
	}

	function is_obj_empty($obj){
		if( is_null($obj) ){
			return true;
		}
		foreach( $obj as $key => $val ){
			return false;
		}
		return true;
	}


	function check(){
		$url = $_POST['websiteurl'];

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyAN9Nc3okzr3_1rDHn5oSMj86bneLoCzl0",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "  {\"client\": {
				\"clientId\":      \"tehnologiiweb2019\",
				\"clientVersion\": \"1.5.2\"
				},
				\"threatInfo\": {
					\"threatTypes\":      [\"MALWARE\", \"SOCIAL_ENGINEERING\"],
					\"platformTypes\":    [\"WINDOWS\"],
					\"threatEntryTypes\": [\"URL\"],
					\"threatEntries\": [
					{\"url\": \"" . $url . "\"}
					]
				}
			}",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json",
				"postman-token: b05b8d34-85f2-49cf-0f8e-03686a71e4e9"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			$json = json_decode($response);
			$matches = array();
			$matches = $json->matches;
			$matches_no = count($matches);
			echo 'Found ' . $matches_no . ' matches. <br>';
			echo '<br>';
			if($matches_no != 0){
				foreach ($matches as $match) {	
					echo '<p>';
					echo 'Threat Type : ' . $match->threatType;
					echo '<br>';
					echo 'Platform Type : ' . $match->platformType;
					echo '<br>';
					echo 'Cache Druration : ' . $match->cacheDuration;
					echo '<br>';
					echo 'Threat Entry Type : ' . $match->threatEntryType;
					echo '<br>';
					echo '</p>';
				}
			}
		}
	}

	include("Footer.php");
	?>
</body>

</html>