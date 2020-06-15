<?php
require_once("../includes/config.php");

        $targetdir="../assets/images/profilePictures/";
        $tempFilePath=$targetdir.uniqid().basename($_FILES["image"]["name"]);
        $tempFilePath=str_replace(" ","_",$tempFilePath);
        move_uploaded_file($_FILES["image"]["tmp_name"], $tempFilePath);
        $query=$con->prepare("SELECT profilePic FROM users WHERE username=:username");
        $query->bindParam(":username",$username);
        $username=$_SESSION["userLoggedIn"];
        $query->execute();
        $deleteFile=$query->fetchColumn();
        $query=$con->prepare("UPDATE users SET profilePic=:profilePic WHERE username=:username");
        $query->bindParam(":profilePic",$profilePic);
        $query->bindParam(":username",$username);
        $profilePic=str_replace("../","",$tempFilePath);
        $username=$_SESSION["userLoggedIn"];
        $query->execute();
        if(strpos($deleteFile, "default")==false) {
        unlink("../" . $deleteFile);
        }
        echo $profilePic;
?>