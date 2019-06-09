<?php
session_start();

$conn = new mysqli("localhost","root","","proiect");
$conn->query('delete from user where id =' .$_SESSION['id']);
$conn->query('delete from subs where id_client = '.$_SESSION['id']);
unset($_SESSION['id']);
unset($_SESSION['user']);
header("Location: http://localhost/TW/application/index.php");
exit();
?>