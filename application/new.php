<!DOCTYPE html>
<html>

<head lang="en-US">
    <title>Security Alerter</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width-device-width, initial-scale=1.0" />
    <meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru" />
    <meta name="description"
        content="The Web App Security Alerter is meant to serve as a guide to achieving better security inside and outside of the Internet, to protect user's private data across all domains and to instruct developers on how to engineer their own secure applications." />
    <!-- Icon obtained from: https://www.isw-online.de/praesident-trump-vs-privacy-shield/  -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="stylesheet.css" type="text/css" />
</head>
<body>

<?php
include("Header.php"); 
include("Navbar.php");
        

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$buff = html_entity_decode($_POST['data']);
	$buff = substr($buff, 0, -2);
	$data = json_decode($buff, true);
	$data = json_decode($data, true);
	echo '<div style="text-align: center;
			margin-top: 10%;">';
	foreach ($data as $key => $value) {
		if(is_array($value)){
			echo $key. ' : ';
			$elems = '';
			foreach ($value as $elem) {
				$elems .= $elem .', ';
			}
			echo substr($elems, 0, -2);
			echo '<br>';
		}
		else{
			echo $key. ' : ' .$value .'<br>';
		}
	}
	echo '</div>';
}	

include("Footer.php");
?>
</body>
</html>
