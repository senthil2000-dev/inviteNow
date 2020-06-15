<?php
require_once("includes/header.php");
require_once("includes/classes/PopularProvider.php");

$popularProvider = new PopularProvider($con, $userLoggedInObj);
$invites=$popularProvider->getInvites();

$inviteGrid = new InviteGrid($con, $userLoggedInObj);
?>
<div class="largeInviteGridContainer">
<?php 
if(sizeof($invites)>0) {
    echo $inviteGrid->createLarge($invites, "Popular public invitations uploaded in the last week", false);
}
else {
    echo "No public invitations to show";
}
?>
</div>