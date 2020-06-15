<?php 
require_once("includes/config.php"); 
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");

if(strpos($_SERVER['HTTP_REFERER'], "resetPassword")==true) {
    $message = "<div class='alert alert-success'>Password Updated</div>";
}
else {
    $message="";
}

$account=new Account($con);

if(isset($_POST["submitButton"])){
    
    $username=FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password=FormSanitizer::sanitizeFormPassword($_POST["password"]);

    $wasSuccessful=$account->login($username, $password);

    if($wasSuccessful) {
        $_SESSION["userLoggedIn"] = $username;
        header("Location: index.php");
    }
}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
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
        <h3>Sign In</h3>
        <span>to continue to InviteNow</span>
        </div>

        <div class="logInFormr">
        
            <form action="signIn.php" method="POST">
            <?php echo $message?>
            <?php echo $account->getError(Constants::$loginFailed); ?>
            <input type="text" name="username" placeholder="Username" value="<?php getInputValue('username'); ?>" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="submitButton" value="SUBMIT">

            
            </form>



        </div>
        <a class="signInMessage" href="requestReset.php">Forgot password?</a>
        <a class="signInMessage" href="signUp.php">Need an account? Sign up</a>
        
        
    </div>

    </div>
</body>
</html>