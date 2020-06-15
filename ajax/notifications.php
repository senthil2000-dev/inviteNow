<?php
require_once("../includes/config.php");
require_once("../includes/classes/User.php");
$seen=0;
$query=$con->prepare("SELECT * FROM notifications WHERE seen=:seen");
$query->bindParam(":seen", $seen);
$query->execute();
$username=$_SESSION["userLoggedIn"];
$verb="";
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
    
    if($row["action"]=="replied on") {
        $query1=$con->prepare("SELECT uploadedBy,title FROM invites WHERE id=:inviteId");
        $query1->bindParam(":inviteId", $row["invite_replyId"]);
        $query1->execute();
        $user=$query1->fetchColumn();
        $url="read.php?id=".$row["invite_replyId"];
        $sentence=$row["postedBy"]." replied to your <a href='$url' target='_blank'>invite</a>";
    }
    elseif($row["action"]=="responded to your reply on"){
        $query2=$con->prepare("SELECT postedBy FROM replies WHERE id=:replyId");
        $query2->bindParam(":replyId", $row["invite_replyId"]);
        $query2->execute();
        $user=$query2->fetchColumn();
        $sentence=$row["postedBy"]." responded to your chat.";
    }
    elseif($row["action"]=="has seen"){
        $query3=$con->prepare("SELECT uploadedBy,title FROM invites WHERE id=:inviteId");
        $query3->bindParam(":inviteId", $row["invite_replyId"]);
        $query3->execute();
        $user=$query3->fetchColumn();
        $url="read.php?id=".$row["invite_replyId"];
        $sentence=$row["postedBy"]." has seen your <a href='$url' target='_blank'>invite</a>";
    }
    elseif($row["action"]=="added you as a friend"){
        $sentence=$row["postedBy"]." added you as a friend";
        $user=$row["friends"];
    }
    elseif($row["action"]=="sent you an invitation"){
        $url="read.php?id=".$row["invite_replyId"];
        $sentence=$row["postedBy"]." sent you an <a href='$url' target='_blank'>invitation</a>";
        $user=$row["friends"];
    }
    elseif($row["action"]=="edited details of the invitation"){
        $url="read.php?id=".$row["invite_replyId"];
        $sentence=$row["postedBy"]." edited details of this <a href='$url' target='_blank'>invitation</a>";
        $user=$row["friends"];
    }
    elseif(strpos($row["action"], "cancelled the")!==false){
        $sentence=$row["postedBy"]." ".$row["action"];
        $user=$row["friends"];
    }
    elseif($row["action"]=="accepted"){
        $query4=$con->prepare("SELECT uploadedBy,title FROM invites WHERE id=:inviteId");
        $query4->bindParam(":inviteId", $row["invite_replyId"]);
        $query4->execute();
        $user=$query4->fetchColumn();
        $url="read.php?id=".$row["invite_replyId"];
        $sentence=$row["postedBy"]." accepted your <a href='$url' target='_blank'>invite</a>";
    }
    elseif($row["action"]=="rejected"){
        $query5=$con->prepare("SELECT uploadedBy,title FROM invites WHERE id=:inviteId");
        $query5->bindParam(":inviteId", $row["invite_replyId"]);
        $query5->execute();
        $user=$query5->fetchColumn();
        $url="read.php?id=".$row["invite_replyId"];
        $sentence=$row["postedBy"]." rejected your <a href='$url' target='_blank'>invite</a>";
    }
    elseif($row["action"]=="wants to rethink upon accepting"){
        $query6=$con->prepare("SELECT uploadedBy,title FROM invites WHERE id=:inviteId");
        $query6->bindParam(":inviteId", $row["invite_replyId"]);
        $query6->execute();
        $user=$query6->fetchColumn();
        $url="read.php?id=".$row["invite_replyId"];
        $sentence=$row["postedBy"]." wants to rethink upon accepting the <a href='$url' target='_blank'>invite</a>";
    }
    
    elseif($row["action"]=="wants to rethink upon rejecting"){
        $query7=$con->prepare("SELECT uploadedBy,title FROM invites WHERE id=:inviteId");
        $query7->bindParam(":inviteId", $row["invite_replyId"]);
        $query7->execute();
        $user=$query7->fetchColumn();
        $url="read.php?id=".$row["invite_replyId"];
        $sentence=$row["postedBy"]." wants to rethink upon rejecting the <a href='$url' target='_blank'>invite</a>";
    }
    if($user==$username && $user!=$row["postedBy"] && $row["postedBy"]!="") {
        $postedBy=$row["postedBy"];
        $time=$row["datePosted"];
        $idVal=$row["id"];
        $notifyTime=time_elapsed_string($time);
        $userLoggedInObj = new User($con, $postedBy);
        $src=$userLoggedInObj->getProfilePic();
        $verb.="<div id='$idVal' class='notify_item'><div class='notify_img'><img src='$src' alt='profile_pic' style='width: 50px'></div><div class='notify_info'><p>$sentence</p><span class='notify_time'>$notifyTime</span></div></div>";
    }
}
echo $verb;


function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>