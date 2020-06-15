<?php
require_once("includes/header.php");
if(!isset($_SESSION["userLoggedIn"]))
header("Location:signIn.php");
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>


<?php
   echo     "<form id='pic' onsubmit='shouldSubmit(event)' method='POST' action='storeImage.php'>
            <div class='row' id='rowPic'>
                <div class='col-md-6'>
                    <div class='camera' id='videoElement'>
                        Your browser not support the video tag
                    </div>
                    <input type=button value='Take Snapshot' onClick='take_snapshot()'>
                    <input type='hidden' name='image' class='image-tag'>
                </div>
                <div class='col-md-6'>
                    <div id='results'><p>Your captured image will appear here...</p></div>
                </div>
                <div class='col-md-12 text-center'>
                    <br/>
                    <button class='btn btn-success'>Update Profile Picture</button>
                </div>";
?>

<script src="assets/js/liveActions.js"></script>
<?php require_once("includes/footer.php"); ?>