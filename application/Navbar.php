<?php
    class Navbar {

        /*
         * URL of current page
         */
        private $currPage;
        /*
         * URLs present on the navbar
         */
        private $navURLs = array("index.php", "about.php", "contact.php", "security.php", "softvuln.php", "hackproofpro.php", "securitytools.php", "apiguide.php", "docs/api/index.html");
        /* 
         * name of the URLs on the navbar (e.g for "index.php" the name is "Home")
         */ 
        private $navURLName = array("Home", "About", "Contact", "Security", "Software Vulnerabilities", "Hackproof Programming", "Security Tools", "API Guide", "App Doc");

        public function __construct() {
            if(isset($_SESSION['id']) && isset($_SESSION['user'])){
                $this->navURLs[] = 'user.php';
                $this->navURLs[] = 'logout.php';
                $this->navURLName[] = $_SESSION['user'];
                $this->navURLName[] = 'Log Out';
            }
            else{
                $this->navURLs[] = 'login.php';
                $this->navURLs[] = 'makeaccount.php';
                $this->navURLName[] = 'Login';
                $this->navURLName[] = 'Create Account';   
            }
            $this->currPage = basename($_SERVER['PHP_SELF']);

            echo "<nav>";
            echo "<ul>";

            for ($i = 0; $i < sizeof($this->navURLs); $i++) {
                if ($this->navURLs[$i] == $this->currPage) {
                    echo "<li>" . $this->navURLName[$i] . "</li>";
                }
                else {
                    echo "<li><a href='" . $this->navURLs[$i] . "'>" . $this->navURLName[$i] . "</a></li>";
                }
            }

            echo "</ul>";
            echo "</nav>";
        }

    }

    new Navbar();

?>
