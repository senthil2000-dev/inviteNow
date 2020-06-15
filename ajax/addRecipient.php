<?php
require_once("../includes/config.php");
$query=$con->prepare("SELECT count(*) FROM users WHERE username=:username");
$query->bindParam(":username", $_POST["rec"]);
$query->execute();
if($_POST["rec"]==$_SESSION["userLoggedIn"])
    echo "same";
else
    echo $query->fetchColumn();
?>