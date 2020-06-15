<?php
require_once("../includes/config.php");
$username=$_SESSION["userLoggedIn"];
$query=$con->prepare("DELETE FROM received WHERE user=:username");
$query->bindParam(":username", $username);
$query->execute();
?>