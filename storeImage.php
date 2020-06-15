<?php
    require_once("includes/config.php");
    $img = $_POST['image'];
    $folderPath = "assets/images/profilePictures/";
  
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
  
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . "." . $image_type;
  
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);
        $query=$con->prepare("SELECT profilePic FROM users WHERE username=:username");
        $query->bindParam(":username",$username);
        $username=$_SESSION["userLoggedIn"];
        $query->execute();
        $deleteFile=$query->fetchColumn();
        $query=$con->prepare("UPDATE users SET profilePic=:profilePic WHERE username=:username");
        $query->bindParam(":profilePic",$file);
        $query->bindParam(":username",$username);
        $username=$_SESSION["userLoggedIn"];
        $query->execute();
        if(strpos($deleteFile, "default")==false) {
        unlink($deleteFile);
        }
        header("Location:index.php");
  
?>