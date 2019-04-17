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
    ?>

    <main class="lmain">
        <div id="loginMain">
        <!-- https://www.iconfinder.com/icons/480741/account_avatar_contact_guest_login_man_user_icon -->
        <img id="loginPhoto" src="assets/images/login.png" alt="Login Photo">
        <h1>Sign in</h1>
        <form class="login" action=/login> <div id=loginUsername>
            <label >Username:</label>
            <input type="text" required placeholder="Enter your username" />
            </div>
            <div id=loginPassword>
                <label >Password:</label>
                <input type="password" required placeholder="Enter your password" />
            </div>
            <p><a href="forgotpass.html">Forgot password?</a></p>
            <a href="makeaccount.html" id="register">Register</a>
            <button type="submit" id="login">Login</button>
        </form>
    </div>
    </main>

    <?php
        include("Footer.php");
    ?>
</body>
</html>
