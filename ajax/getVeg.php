<?php
require_once("../includes/config.php");
$inviteId = $_POST["id"];
$veg=0;
$arr=array();
$query=$con->prepare("SELECT * FROM accepted WHERE veg=:veg AND inviteId=:inviteId");
$query->bindParam(":veg", $veg);
$query->bindParam(":inviteId", $inviteId);
$query->execute();
array_push($arr, $query->rowCount());
$veg=1;
$query=$con->prepare("SELECT * FROM accepted WHERE veg=:veg AND inviteId=:inviteId");
$query->bindParam(":veg", $veg);
$query->bindParam(":inviteId", $inviteId);
$query->execute();
array_push($arr, $query->rowCount());
echo json_encode($arr);
?>