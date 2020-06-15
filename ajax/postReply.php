<?php
require_once("../includes/config.php");
require_once("../includes/classes/User.php");
require_once("../includes/classes/Reply.php");

if(isset($_POST['replyText']) && isset($_POST['postedBy']) && isset($_POST['inviteId'])) {
    $userLoggedInObj=new User($con, $_SESSION["userLoggedIn"]);
    
    $query=$con->prepare("INSERT INTO replies(postedBy, inviteId, responseTo, body)
                          VALUES(:postedBy,:inviteId,:responseTo,:body)");
    $query->bindParam(":postedBy", $postedBy);
    $query->bindParam(":inviteId", $inviteId);
    $query->bindParam(":responseTo", $responseTo);
    $query->bindParam(":body", $replyText);

    $postedBy=$_POST['postedBy'];
    $inviteId=$_POST['inviteId'];
    $responseTo=isset($_POST['responseTo']) ? $_POST['responseTo'] : 0;
    $replyText=$_POST['replyText'];

    $query->execute();

    
    $newReply = new Reply($con, $con->lastInsertId(), $userLoggedInObj, $inviteId);
    if($responseTo==0) {
        $action="replied on";
        $replyId=$inviteId;
    }
    else {
        $action="responded to your reply on";
        $replyId=$responseTo;
    }
    
    $query=$con->prepare("INSERT INTO notifications(postedBy, invite_replyId, action) VALUES(:user, :invite_replyId, :action)");
        $query->bindParam(":user", $postedBy);
        $query->bindParam(":invite_replyId", $replyId);
        $query->bindParam(":action", $action);
        $query->execute();
    echo $newReply->create();
}
else {
    echo "One or more parameters are not passed into the postReply.php file";
}

?>