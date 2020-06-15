<?php
require_once("includes/config.php");
if(!isset($_GET["code"])) {
    exit("Can't find page");
}

$code=$_GET["code"];

$query=$con->prepare("SELECT email FROM resetpasswords where code=:code");
$query->bindParam(":code", $code);
$query->execute();
if($query->rowCount()==0) {
    exit("Can't find page");
}

if(isset($_POST["password"])) {
    $pw=$_POST["password"];
    $pw=hash("sha512", $pw);

    $email=$query->fetchColumn();

    $query=$con->prepare("UPDATE users SET password=:pw WHERE email=:email");
    $query->bindParam(":pw", $pw);
    $query->bindParam(":email", $email);

    if($query->execute()) {
        $query=$con->prepare("DELETE FROM resetpasswords WHERE code=:code");
        $query->bindParam(":code", $code);
        $query->execute();
           
        header("Location: signIn.php");
    }
    else {
        exit("Something went wrong");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>InviteNow</title>
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>


<div class="signInContainer">

    <div class="column">

        <div class="header">
        <img src="assets/images/icons/google.png" title="logo" alt="Site logo">
        <h3>Reset password</h3>
        <span>Enter your new password</span>
        </div>

        <div class="logInForm">
        <form method="POST">
            <input type="password" name="password" placeholder="New Password">
            <input type="submit" id= "wide" name="submit" value="Update password">
        </form>
        </div>
    </div>

</div>

</body>
</html>