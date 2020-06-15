<?php 
require_once("includes/header.php");
require_once("includes/classes/InviteUploadData.php");
require_once("includes/classes/InviteProcessor.php");
if(!isset($_POST["inviteText"])){
    echo "No content sent to page.";
    exit();
}
$theme=$_POST["template"];
$inviteUploadData= new InviteUploadData(
                            $_FILES["fileInput"],
                            $_POST["inviteText"],
                            $_POST["titleInput"],
                            $_POST["descriptionInput"],
                            $_POST["privacyInput"],
                            $_POST["categoryInput"],
                            $_POST["members"],
                            $_POST["eventDateInput"],
                            $_POST["deadlineInput"],
                            $userLoggedInObj->getUsername()
                        );
$inviteProcessor= new InviteProcessor($con);
$wasSuccessful=$inviteProcessor->upload($inviteUploadData, $theme);

if($wasSuccessful){
    echo "Upload successful</div>";
}
 ?>