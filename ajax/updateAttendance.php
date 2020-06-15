<?php
require_once("../includes/config.php");
$inviteId = $_POST["id"];
$username=$_SESSION["userLoggedIn"];
$num=$_POST["num"];
$query=$con->prepare("UPDATE accepted SET num=:num WHERE inviteId=:inviteId AND username=:username");
$query->bindParam(":username", $username);
$query->bindParam(":inviteId", $inviteId);
$query->bindParam(":num", $num);
$query->execute();
?>