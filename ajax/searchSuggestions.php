<?php
require_once("../includes/config.php");
$privacy=1;
$query=$con->prepare("SELECT * FROM invites WHERE privacy=:privacy");
$query->bindParam(":privacy", $privacy);
$query->execute();
$suggested=array();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
    array_push($suggested, $row["uploadedBy"]);
    array_push($suggested, $row["title"]);
}
$suggested=array_unique($suggested, SORT_REGULAR);
$result=array_values($suggested);
echo json_encode($result);
?>