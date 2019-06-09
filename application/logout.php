<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['user']);
header("Location: http://localhost/TW/application/index.php");
exit();


?>