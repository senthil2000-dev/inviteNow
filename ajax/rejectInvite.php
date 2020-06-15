<?php
require_once("../includes/config.php");
require_once("../includes/classes/Invite.php");
require_once("../includes/classes/User.php");

$inviteId = $_POST["inviteId"];
$username=$_SESSION["userLoggedIn"];

$userLoggedInObj = new User($con, $username);
$invite = new Invite($con, $inviteId, $userLoggedInObj);
if(!($invite->wasRejectedBy())) {
    $action="rejected";
}
else {
    $action="wants to rethink upon rejecting";
}
$query=$con->prepare("INSERT INTO notifications(postedBy, invite_replyId, action) VALUES(:user, :invite_replyId, :action)");
    $query->bindParam(":user", $username);
    $query->bindParam(":invite_replyId", $inviteId);
    $query->bindParam(":action", $action);
    $query->execute();
echo $invite->reject();
?>