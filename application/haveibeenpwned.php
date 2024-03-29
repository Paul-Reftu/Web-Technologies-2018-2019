<!DOCTYPE html>
<html lang="en-US">

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width-device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="This tool helps you find out which of these web services has had a security breach since the time you first signed up for them, to give you an idea whether you ought to seriously consider changing certain sensitive data such as your password or not."/>
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

	<main class="main_pwnd">
		<section class="facilities">
			<br>
			<h2>See if "you have been pwned" or not!</h2>
			
			<p>Type in your e-mail address: <br></p>

			<form action=<?php echo $_SERVER['PHP_SELF']; ?> method="get">
				<input class="searchbar" type="text" name="email" autofocus/> <!--<input class="button" type="submit" value="Check"/> <br/> <br/>-->
				<!-- <input type="submit" class="button" /> -->
			    <button class="button" type="submit">Check</button>
			</form>
			
			
			<?php 
			require("mail_site_MVC.php");	
				if (isset($_GET["email"]))
			 {
				$view = new View($_GET['email']);
				$model = new Model();
				$controller = new Controller($view, $model);
				$controller->doHaveIBeenPwned();
			 }
			?>
		</section>

		<aside>
		</aside>
	</main>

	<?php
		include("Footer.php");
	?>
</body>

</html>