<?php
require_once("includes/header.php");
$invites=array();
$query=$con->prepare("SELECT inviteId FROM received WHERE user=:username AND time > (NOW() - INTERVAL :days DAY) ORDER BY id DESC");
$query->bindParam(":username", $username);
$query->bindParam(":days", $days);
if(isset($_GET["rangeValue"])) 
    $days=$_GET["rangeValue"];
else 
    $days=1826;
$username=$userLoggedInObj->getUsername();
$query->execute();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
    $invites[]=new Invite($con, $row["inviteId"], $userLoggedInObj);
}
$inviteGrid = new InviteGrid($con, $userLoggedInObj);
?>
<div class="largeInviteGridContainer">
<?php 
if(sizeof($invites)>0) {
    echo $inviteGrid->createLarge($invites, "Received invites", false, true, $days);
}
else {
    echo $inviteGrid->createLarge($invites, "No invites to show", false, true, $days);;
}
?>
</div>