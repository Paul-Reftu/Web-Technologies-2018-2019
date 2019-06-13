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
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            register();
        }

    ?>

    <main class="rmain">
    <div class="register">
            <!-- https://fandi-conference.com/register-icon/ -->
            <img id="registerPhoto" src="assets/images/register.png" alt="Register Photo"> 
        <h1>Register</h1>
        <form action="makeaccount.php" method="post"> <div>
            <label>Username*:</label>
            <input type="text" required id="registerUsername" placeholder="Enter your username" name="user" />
    </div>
    <div>
        <label >Password*:</label>
        <input type="password" required class="registerPassword" placeholder="Enter your password" name="password1"/>
    </div>
    <div>
        <label>Repeat Password*:</label>
        <input type="password" required class="registerPassword" placeholder="Repeat password" name="password2"/>
    </div>
    <div>
        <label>E-mail*:</label>
        <input type="text" required id="registerEmail" placeholder="Enter your E-mail" name="email" />
    </div>
    <div>
        <label>Age:</label>
        <input type="number" id="registerAge" placeholder="Enter your age" name="age"/>
    </div>
    <div>
        <label>Sex:</label>
        <input type="text" id="registerSex" placeholder="Enter your sex" name="sex"/>
    </div>
    <p id="required">All fields with * are required for register.</p>
    <button type="submit">Register</button>
    </form>
    </div>


</main>


    <?php
        function register(){
            $conn = new mysqli("localhost","root","","proiect");
            $username = $_POST['user'];
            $email = $_POST['email'];
            $result = $conn->query("select id from user where username='".$username."'");
            if ($result->num_rows > 0){
                echo "Username exists!";
                return;
            }
            $result = $conn->query("select id from user where email='".$email."'");
            if ($result->num_rows > 0){
                echo "Email exists!";
                return;
            }
            $password1 = $_POST['password1'];
            $password2 = $_POST['password2'];
            if ($password1 != $password2){
                echo "Passwords does not match!";
                return;
            }
            if (isset($_POST['age'])){
                $age = $_POST['age'];
            }
            else{
                $age = 'NULL';
            }
            if (isset($_POST['sex'])){
                $sex = $_POST['sex'];
            }
            else{
                $sex = 'NULL';
            }
            $result = $conn->query("select max(id) from user");
            $id = $result->fetch_assoc();
            $id = $id['max(id)'] + 1;
            $values = $id.",'".$username."','".$password1."','".$email."',".$age.",'".$sex."')";
            $conn->query("insert into user values (" .$values);
            echo '<div style="text-align: center;
                    margin-top: 5%;font-weight: bold;font-size:30px;">
                    Registered succesfully!</div>';
        }



        include("Footer.php");
    ?>
</body>
</html>
