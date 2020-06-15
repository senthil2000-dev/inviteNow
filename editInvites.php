<?php
require_once("includes/header.php");
require_once("includes/config.php");
$username=$_SESSION["userLoggedIn"];
$yourInvitationsProvider=new YourInvitationsProvider($con, $userLoggedInObj);
$invites=$yourInvitationsProvider->getInvites();

$inviteGrid = new InviteGrid($con, $userLoggedInObj);
?>
<div class="largeInviteGridContainer">
<?php 
if(sizeof($invites)>0) {
    echo $inviteGrid->createLarge($invites, "Invitations sent", false);
}
else {
    echo "No invitations sent";
}
?>
</div>