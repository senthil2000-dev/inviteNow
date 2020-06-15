<?php
require_once("../includes/config.php");
$inviteId = $_POST["id"];
$username=$_SESSION["userLoggedIn"];
$num=$_POST["num"];
$query=$con->prepare("SELECT * FROM accepted WHERE south IS NOT NULL AND inviteId=:inviteId AND username=:username");
$query->bindParam(":username", $username);
$query->bindParam(":inviteId", $inviteId);
$query->execute();
$count=$query->rowCount();
    if($count==0) {
        $query=$con->prepare("UPDATE accepted SET south=:south WHERE inviteId=:inviteId AND username=:username");
        $query->bindParam(":username", $username);
        $query->bindParam(":inviteId", $inviteId);
        $query->bindParam(":south", $num);
        $query->execute();
    }

echo $count;
?>