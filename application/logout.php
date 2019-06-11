<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['user']);
header("Location: http://localhost/Web-Tehnologies-2018-2019/application/index.php");
exit();


?>