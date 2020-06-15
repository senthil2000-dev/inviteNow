<?php
require_once("../includes/config.php");
require_once("../includes/classes/Reply.php");
require_once("../includes/classes/User.php");
$ids = ($_POST["ids"]!="")?(explode(",", $_POST["ids"])):[];

$inviteId = $_POST["inviteId"];
$username=isset($_SESSION["userLoggedIn"])?$_SESSION["userLoggedIn"]:"";
$replyId=$_POST["replyId"];

$userLoggedInObj = new User($con, $username);
$reply = new Reply($con, $replyId, $userLoggedInObj, $inviteId);

echo $reply->getResponses($ids);
?>