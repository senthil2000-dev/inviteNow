<?php
require_once("includes/header.php");
require_once("includes/classes/FriendlyProvider.php");
if(!User::isLoggedIn()) {
    header("Location:signIn.php");
}

$friendlyProvider = new FriendlyProvider($con, $userLoggedInObj);
$invites=$friendlyProvider->getInvites();

$inviteGrid = new InviteGrid($con, $userLoggedInObj);
?>
<div class="largeInviteGridContainer">
<?php 
if(sizeof($invites)>0) {
    echo $inviteGrid->createLarge($invites, "Friendly Invitations", false);
}
else {
    echo "No Friendly Invitations to show";
}
?>
</div>