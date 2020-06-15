<?php 
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");

$account= new Account($con);


if(isset($_POST["submitButton"])){
    $firstName=FormSanitizer::sanitizeFormstring($_POST["firstName"]);
    $lastName=FormSanitizer::sanitizeFormstring($_POST["lastName"]);

    $username=FormSanitizer::sanitizeFormUsername($_POST["username"]);

    $email=FormSanitizer::sanitizeFormEmail($_POST["email"]);
    $email2=FormSanitizer::sanitizeFormEmail($_POST["email2"]);

    $password=FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $password2=FormSanitizer::sanitizeFormPassword($_POST["password2"]);


    $wasSuccessful = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2);

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
        <h3>Sign up</h3>
        <span>to continue to InviteNow</span>
        </div>

        <div class="logInForm">
        
            <form action="signUp.php" method="POST">

            <?php echo $account->getError(Constants::$firstNameCharacters); ?>
             <input type="text" name="firstName" placeholder="First name" value="<?php getInputValue('firstName'); ?>" autocomplete="off" required>

            <?php echo $account->getError(Constants::$lastNameCharacters); ?>
            <input type="text" name="lastName" placeholder="Last name" value="<?php getInputValue('lastName'); ?>" autocomplete="off" required>

            <?php echo $account->getError(Constants::$usernameCharacters); ?>
            <?php echo $account->getError(Constants::$usernameTaken); ?>
            <input type="text" name="username" placeholder="Username" value="<?php getInputValue('username'); ?>" autocomplete="off" required>

            <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
            <?php echo $account->getError(Constants::$emailInvalid); ?>
            <?php echo $account->getError(Constants::$emailTaken); ?>
            <input type="email" name="email" placeholder="Email" value="<?php getInputValue('email'); ?>" autocomplete="off" required>
            <input type="email" name="email2" placeholder="Confirm email" value="<?php getInputValue('email2'); ?>" autocomplete="off" required>

            <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
            <?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
            <?php echo $account->getError(Constants::$passwordLength); ?>
            <input type="password" name="password" placeholder="Password" autocomplete="off" required>
            <input type="password" name="password2" placeholder="Confirm Password" autocomplete="off" required>

            <input type="submit" name="submitButton" value="SUBMIT">

            </form>



        </div>

        <a class="signInMessage" href="signIn.php">Already have an account? Sign in here!</a>
        
    </div>

    </div>
</body>
</html>