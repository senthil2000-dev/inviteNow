<?php
$web="assets/images/inviteImages/";
$targetdir=$web.uniqid().basename($_FILES["file"]["name"]);
$tempFilePath="../".$targetdir;
$tempFilePath=str_replace(" ","_",$tempFilePath);
move_uploaded_file($_FILES["file"]["tmp_name"], $tempFilePath);
echo $targetdir;
?>