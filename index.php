<?php require_once("includes/header.php"); ?>

<div class="inviteSection">
    <?php
    $yourInvitationsProvider=new YourInvitationsProvider($con, $userLoggedInObj);
    $invites3=$yourInvitationsProvider->getInvites();

    $inviteGrid3 = new InviteGrid($con, $userLoggedInObj);
    
    if(User::isLoggedIn() && sizeof($invites3)>0) {
        echo $inviteGrid3->create($invites3, "Invitations sent", false);
    }

    $acceptedInvitesProvider = new AcceptedInvitesProvider($con, $userLoggedInObj);
    $invites=$acceptedInvitesProvider->getInvites();
    
    $inviteGrid = new InviteGrid($con, $userLoggedInObj);
    if(User::isLoggedIn() && sizeof($invites)>0) {
        echo $inviteGrid->create($invites, "Invitations that you have accepted", false);
    }

    $rejectedInvitesProvider = new RejectedInvitesProvider($con, $userLoggedInObj);
    $invites2=$rejectedInvitesProvider->getInvites();
    
    $inviteGrid2 = new InviteGrid($con, $userLoggedInObj);
    if(User::isLoggedIn() && sizeof($invites2)>0) {
        echo $inviteGrid2->create($invites2, "Invitations that you have rejected", false);
    }

    $inviteGrid=new InviteGrid($con, $userLoggedInObj);
    echo $inviteGrid->create(null, "Public events", false);
    ?>

</div>

<?php require_once("includes/footer.php"); ?>