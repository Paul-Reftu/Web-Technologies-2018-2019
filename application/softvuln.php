<!DOCTYPE html>
<html>

<head lang="en-US">
    <title>Security Alerter</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Reftu Paul Alexandru, Ruse Daniel Stefan, Popescu Flavius-Petru" />
    <meta name="description"
        content="The Web App Security Alerter is meant to serve as a guide to achieving better security inside and outside of the Internet, to protect user's private data across all domains and to instruct developers on how to engineer their own secure applications." />
    <!-- Icon obtained from: https://www.isw-online.de/praesident-trump-vs-privacy-shield/  -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="stylesheet.css" type="text/css" />
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
        require_once("Header.php"); 
        require_once("Navbar.php");
    ?>
<main id="softvuln">
        <div id="filter">
            <p>Filter type 1</p>
            <ul>
                <li><input type="checkbox"> filter 1</li>
                <li><input type="checkbox"> filter 2</li>
                <li><input type="checkbox"> filter 3</li>
                <li><input type="checkbox"> filter 4</li>
            </ul>
            <p>Filter type 2</p>
            <ul>
                <li><input type="checkbox"> filter 1</li>
                <li><input type="checkbox"> filter 2</li>
                <li><input type="checkbox"> filter 3</li>
                <li><input type="checkbox"> filter 4</li>
            </ul>

        </div>
        <div id="vuln">
        <div class="search">
             <form action=<?php echo $_SERVER['PHP_SELF']; ?> method="get">
                <img src="assets/images/search.png" alt="Search Photo">
                <input type="text" name="description" class="search" placeholder="Search for what you want here" />
                <button type="submit">Search</button>
            </form>
        </div>
        <div id="vulninf">
            <div class="vulnerabilitate">
                <div>Date</div>
                <div><abbr title="Downloadable">D</abbr></div>
                <div><abbr title="Vulnerable Aplication">A</abbr></div>
                <div><abbr title="Verified">V</abbr></div>
                <div>Title</div>
                <div>Type</div>
                <div>Platform</div>
            </div>
        </div>
    </div>
    </main>

    <?php
        require_once("soft_vuln/SoftVulnSearch.php"); 

        if (isset($_GET["description"])) {

            new SoftVulnSearch();

        }

        require_once("Footer.php");
    ?>
</body>
</html>
