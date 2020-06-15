<?php
require_once("../includes/config.php");

$query=$con->prepare("SELECT statusPaused FROM users WHERE username=:user");
$query->bindParam(":user", $username);
$username=$_SESSION["userLoggedIn"];
$query->execute();
$status=($query->fetchColumn())?0:1;

$query=$con->prepare("UPDATE users SET statusPaused=:status WHERE username=:username");
$query->bindParam(":status", $status);
$query->bindParam(":username", $username);
$query->execute();
?>