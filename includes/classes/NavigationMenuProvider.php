<?php
class NavigationMenuProvider {

    private $con, $userLoggedInObj;
    
    public function __construct($con, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function create() {
        $menuHtml=$this->createNavItem("Dashboard", "assets/images/icons/home.png", "index.php");
        $menuHtml.=$this->createNavItem("Popular public events", "assets/images/icons/popular.png", "popular.php");
        $menuHtml.=$this->createNavItem("Accepted", "assets/images/icons/thumb-up.png", "accepted.php");

        if(User::isLoggedIn()) {
            $menuHtml.=$this->createNavItem("Settings", "assets/images/icons/settings.png", "settings2.php");
            $menuHtml.=$this->createNavItem("Your invites", "assets/images/icons/search.png", "editInvites.php");
            $menuHtml.=$this->createNavItem("Friendly invites", "assets/images/icons/friendly.png", "friendly.php");
            $menuHtml.=$this->createNavItem("Inbox", "assets/images/icons/received.png", "received.php");
            $menuHtml.=$this->createNavItem("Profilepic with webcam", "assets/images/profilePictures/default.png", "live.php");
            $menuHtml.=$this->createNavItem("Authenticate with google", "assets/images/icons/status.png", "google-login.php");
            $menuHtml.=$this->createNavItem("Log Out", "assets/images/icons/logout.png", "logout.php");
        }

        

        return "<div class='navigationItems'>
                    $menuHtml
                </div>";
    }

    private function createNavItem($text, $icon, $link) {
        return "<div class='navigationItem'>
                    <a href='$link'>
                        <img src='$icon'>
                        <span>$text</span>
                    </a>
                </div>";
    }
}
?>