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
    <link rel="stylesheet" href="stylesheet2.css" type="text/css" />
    <link rel="stylesheet" href="stylesheet.css" type="text/css" />
</head>

<body>
<?php 
    include("Header.php"); 
    include("Navbar.php");
    echo '<main>';
	$conn = new mysqli("localhost","root","","proiect");
	$result = $conn->query('select id from reset where id = ' .$_GET['id']. ' and cheie = ' .$_GET['key']);
	if ($result->num_rows > 0){
		echo '<form class="info" action="doreset.php" method="post">';
		echo '<div id=rPassword>';
        echo '<label >Enter new Password:</label>';
        echo '<input type="hidden" value="' .$_GET['id']. '" name="id" />';
        echo '<input type="password" placeholder="Enter your password" name="password"/>';
        echo '</div>';
        echo '<button type="submit">Reset</button>';
        echo '</form>';

	}
	else {
		echo 'Reset failed! Please try again <a href="forgotpass.php>here</a> !';
	}
	echo '</main>';
    include("Footer.php");
    ?>
</body>
</html>
