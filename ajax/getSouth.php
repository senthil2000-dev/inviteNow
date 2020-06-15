<?php
require_once("../includes/config.php");
$inviteId = $_POST["id"];
$south=0;
$arr=array();
$query=$con->prepare("SELECT * FROM accepted WHERE south=:south AND inviteId=:inviteId");
$query->bindParam(":south", $south);
$query->bindParam(":inviteId", $inviteId);
$query->execute();
array_push($arr, $query->rowCount());
$south=1;
$query=$con->prepare("SELECT * FROM accepted WHERE south=:south AND inviteId=:inviteId");
$query->bindParam(":south", $south);
$query->bindParam(":inviteId", $inviteId);
$query->execute();
array_push($arr, $query->rowCount());
echo json_encode($arr);
?>