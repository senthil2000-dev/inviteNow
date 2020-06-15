<?php
require_once("../includes/config.php");
require_once("../includes/classes/Invite.php");
require_once("../includes/classes/User.php");
$id=$_POST["id"];
$username=$_SESSION["userLoggedIn"];
$userLoggedInObj=new User($con, $username);
$invite=new Invite($con, $id, $userLoggedInObj);
$title=$invite->getTitle();
$category=$invite->getCategoryName();
$action="cancelled the ".$category." invitation, <strong>$title</strong>";
$uploader=$invite->getUploadedBy();
$members=$invite->getMembers();
        for($k=0;$k<sizeof($members);$k++) {
          $user=$members[$k];
          $query=$con->prepare("INSERT INTO notifications(postedBy, invite_replyId, action, friends) VALUES(:user, :invite_replyId, :action, :sentTo)");
          $query->bindParam(":user", $uploader);
          $query->bindParam(":invite_replyId", $id);
          $query->bindParam(":action", $action);
          $query->bindParam(":sentTo", $user);
          $query->execute();
        }
$query=$con->prepare("DELETE FROM invites WHERE id=:id");
$query->bindParam(":id", $id);
$query->execute();
$query=$con->prepare("DELETE FROM received WHERE inviteId=:id");
$query->bindParam(":id", $id);
$query->execute();
$query=$con->prepare("DELETE FROM accepted WHERE inviteId=:id");
$query->bindParam(":id", $id);
$query->execute();
$query=$con->prepare("DELETE FROM rejected WHERE inviteid=:id");
$query->bindParam(":id", $id);
$query->execute();
$query=$con->prepare("SELECT count(*) as 'count' FROM invites WHERE uploadedBy=:username");
$query->bindParam(":username", $username);
$query->execute();
$data=$query->fetch(PDO::FETCH_ASSOC);
echo $data["count"];
?>