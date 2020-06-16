<?php 
require_once("includes/config.php");
require_once("includes/classes/ButtonProvider.php");
require_once("includes/classes/User.php");
require_once("includes/classes/Invite.php");
require_once("includes/classes/InviteGrid.php");
require_once("includes/classes/InviteGridItem.php");
require_once("includes/classes/AcceptedInvitesProvider.php");
require_once("includes/classes/RejectedInvitesProvider.php");
require_once("includes/classes/YourInvitationsProvider.php");
require_once("includes/classes/NavigationMenuProvider.php");

$usernameLoggedIn=User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
$userLoggedInObj = new User($con, $usernameLoggedIn);
?>
<!DOCTYPE html>
<html>
<head>
<title>InviteNow</title>
<link rel = "icon" href =  
"assets\images\icons\mailPic.png" 
        type = "image/x-icon"> 
<link rel="stylesheet" type="text/css" href="assets/css/all.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<script src="assets/js/commonActions.js"></script>
<script src="https://kit.fontawesome.com/d1766da268.js" crossorigin="anonymous"></script>
<script src="assets/js/userActions.js"></script>

</head>
<body>
    <div id="pageContainer">
        
            <div id="mastHeadContainer">
            <button class="navShowHide">
            <img src="assets/images/icons/menu.png">
            </button>
            <a class="logoContainer" href="index.php">
                <img src="assets/images/icons/google.png" title="logo" alt="Site logo">
            </a>

            <div class="searchBarContainer">
                <form action="search.php" method="GET" autocomplete="off">
                    <div class="autocomplete" style="max-width:600px;width:100%;">
                        <input type="text" class="searchBar searchInput" name="term" placeholder="Search...">
                    </div>
                    <button class="searchButton">
                    <img src="assets/images/icons/search.png">
                    </button>
                </form>
            </div>

            <div class="rightIcons">
            
            <?php
            if($usernameLoggedIn=="") {
                echo
                "<a onclick='notSignedIn()' class='uploadButton'>";
            }
            else {
                echo
                "<div class='notification_wrap'>
            <a href='#' class='notification'>
                <i class='fa fa-bell-o' id='bell'></i>
                <span class='badge'></span>
            </a>
            <div class='dropdown'>
                
            </div>
        </div><a href='editor.php' class='uploadButton'>";
            }
            ?>
                <img class='upload' src='assets/images/icons/upload.png'>
                </a>
                <?php echo ButtonProvider::createUserProfileNavigationButton($con, $usernameLoggedIn); ?>
            </div>
            </div>

        <div id="sideNavContainer" style="display:none;">
            <?php
            $navigationProvider=new NavigationMenuProvider($con, $userLoggedInObj);
            echo $navigationProvider->create();
            ?>
        </div>
            <script src="assets/js/syncSearch.js"></script>
            <script>
            
        document.addEventListener('click', function(event) {
            console.log(event.target);
            if(!(document.getElementsByClassName("notification_wrap")[0].contains(event.target))) {
                document.querySelector(".dropdown").classList.remove("active");
                clearNotifications();
            } 
            });
        document.addEventListener('DOMContentLoaded', function(){
			document.querySelector(".notification").addEventListener('click', function(){
                if(document.querySelector(".dropdown").classList.contains("active")) {
                    document.querySelector(".dropdown").classList.toggle("active");
                    clearNotifications();
                    console.log(2);
                }
                else {
                    document.querySelector(".dropdown").classList.toggle("active");
                }
				    
            }) 
        });
        if(document.getElementsByClassName("dropdown")[0]) {
            setInterval(() => {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementsByClassName("dropdown")[0].innerHTML=this.responseText;
                        var number=document.getElementsByClassName("dropdown")[0].childElementCount;
                        console.log(number);
                        if(number!=0)
                            document.getElementsByClassName("badge")[0].innerHTML=number;
                        else 
                            document.getElementsByClassName("badge")[0].style.display="none";
                    }
                };
                xhttp.open("POST", "ajax/notifications.php", true);
                xhttp.send();
        }, 1000);
        }
        
        function clearNotifications() {
            var number=document.getElementsByClassName("dropdown")[0].childElementCount;
            var ids="";
            var items=document.getElementsByClassName("notify_item");
            for(var m=0;m<number;m++) {
                ids+=items[m].id;
                if(m!=number-1)
                    ids+=";";   
            }
            if(ids.length!=0) {
                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", "ajax/clearNotify.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("ids="+ids);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                    }
                };
            } 
        }
        </script>
        <div id="mainSectionContainer">
        <div id="mainContentContainer">
