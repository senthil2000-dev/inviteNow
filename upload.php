<?php 
require_once("includes/header.php");
require_once("includes/classes/InviteDetailsFormProvider.php");
if($userLoggedInObj->getUsername()==""){
  echo "You must be logged in to upload an invite";
  
  exit();
}
elseif(!isset($_POST["content"])) {
  echo "No content drafted";
  exit();
}
 ?>

<div class="column upload">
<?php
$theme=$_POST["theme"];
$content=$_POST['content'];
$formProvider=new InviteDetailsFormProvider($con, $content);
echo $formProvider->createUploadForm($theme);
?>
</div>
<?php require_once("includes/footer.php"); ?>