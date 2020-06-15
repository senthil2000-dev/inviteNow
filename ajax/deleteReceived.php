<?php
require_once("../includes/config.php");
$id=$_POST["id"];
$username=$_SESSION["userLoggedIn"];
$query=$con->prepare("DELETE FROM received WHERE inviteId=:id AND user=:user");
$query->bindParam(":id", $id);
$query->bindParam(":user", $username);
$query->execute();
$query=$con->prepare("SELECT count(*) as'count' FROM received WHERE user=:username");
$query->bindParam(":username", $username);
$query->execute();
$data=$query->fetch(PDO::FETCH_ASSOC);
echo $data["count"];
?>