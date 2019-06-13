<?php

	$conn = new mysqli("localhost","root","","proiect");
	if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['password']) and isset($_POST['id'])){
		$conn->query('delete from reset where id = ' .$_POST['id']);
		$conn->query('update user set password = \'' .$_POST['password']. "' where id = " .$_POST['id']);
	}
    header("Location: http://localhost/Web-Tehnologies-2018-2019/application/login.php");
	exit();


?>
