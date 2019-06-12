<!DOCTYPE html>
<html lang="en-US">

<head lang="en-US">
	<title>Security Alerter</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width-device-width, initial-scale=1.0"/>
	<meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru"/>
	<meta name="description" content="The programming paradigm known as 'hackproof' programming is not quite what it sounds to be. On this page we will take a deeper look at what 'hackproof' actually represents and how can a programmer or a group of programmers begin to approach this type of coding."/>
	<!-- Icon obtained from: https://www.isw-online.de/praesident-trump-vs-privacy-shield/  -->
	<link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="stylesheet.css" type="text/css"/>
	<link rel="stylesheet" href="aboutstylesheet.css" type="text/css"/>
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
		<article>
			<section>
				<!-- Image source: https://unsplash.com/search/photos/coding -->
				<img src="assets/images/hackproofpro_img_1.jpg" style="height:100%; width:100%;" alt="An image with some html code"/>
				<section class="facilities">
				<h2>Hackproof Programming</h2>

				<h2>What I mean by "Hackproof" Programming</h2>

				<p>This particular term of "hackproof" programming is in fact misleading - or - <em>rather - would be</em> far more misleading had I not care to stress the <strong>quotes</strong> around "hackproof".</p>

				<p>Therefore, what I actually mean by "hackproof" is not what would one normally consider - that is, being able to engineer software <strong>completely free</strong> of any weaknesses/penetration points that would allow hackers to just swarm in and take advantage of said weakness(es).</p>

				<p>What I <strong>do</strong> mean by "hackproof" is actually much less evident.</p>

				<p>My experience and my own intuition in fact tells me that there is and there <em>never will be</em> any such software system that is absolutely invulnerable to suffering from certain weak points in its design.</p>

				<p>So what <em>am I</em> actually saying here? Am I directly contradicting myself? Am I simply <em>wasting</em> your time as an ambitious reader and consumer of information?</p>

				<p>The answer is: <strong>No.</strong> Let me care to elaborate.</p>
				</section>
			</section>

			<section class="facilities"> 
				<h2>What is "Hackproof" Programming then?</h2>

				<p>To be continued...</p>
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