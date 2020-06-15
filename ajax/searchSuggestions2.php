<?php
require_once("../includes/config.php");
require_once("../includes/classes/User.php");
$username=$_SESSION["userLoggedIn"];
$userObj=new User($con, $username);
$friends=$userObj->getFriends();
array_push($friends, $userObj);
$results=[];
for($k=0;$k<sizeof($friends);$k++) {
    array_push($results, $friends[$k]->getUsername());
}
$query=$con->prepare("SELECT * FROM users");
$query->execute();
$suggested=array();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
    array_push($suggested, $row["username"]);
}
echo json_encode(array_values(array_diff($suggested, $results)));
?>