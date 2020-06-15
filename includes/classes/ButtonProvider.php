<?php
class ButtonProvider {

    public static $signInFunction="notSignedIn()";

    public static function createLink($link) {
        return User::isLoggedIn() ? $link: ButtonProvider::$signInFunction;
    }

    public static function createButton($text, $imageSrc, $action, $class) {
       
       $image=($imageSrc==null)?"":"<img src='$imageSrc'>";

       $action=ButtonProvider::createLink($action);
       
        return "<button class='$class' onclick='$action' >
                    $image
                    <span class='text'>$text</span>
                </button>";
    }

    public static function createHyperlinkButton($text, $imageSrc, $href, $class) {
       
        $image=($imageSrc==null)?"":"<img src='$imageSrc'>";
        
         return "<a href='$href'>
                    <button class='$class' >
                        $image
                        <span class='text'>$text</span>
                    </button>
                 </a>";
     }

    public static function createUserProfileButton($con, $username) {
        $userObj=new User($con, $username);
        $profilePic=$userObj->getProfilePic();
        $link="profile.php?username=$username";
        if($username=="") {
            $userProfile="<a onclick='notSignedIn2(this)'>";
        }
        else {
            $userProfile="<a href='$link'>";
        }
        return "$userProfile
                    <img src='$profilePic' class='profilePicture'>
                </a>";
    }

    public static function createEditInviteButton($inviteId) {
        $href="editInvite.php?inviteId=$inviteId";

        $button=ButtonProvider::createHyperlinkButton("EDIT INVITE", null, $href, "edit button");

        return "<div class=editInviteButtonContainer>
            $button
        </div>";
    }

    public static function createFriendsButton($con, $userToObj, $userLoggedInObj) {
        $userTo=$userToObj->getUsername();
        $userLoggedIn=$userLoggedInObj->getUsername();

        $isFriend=$userLoggedInObj->isFriend($userTo);
        $buttonText = $isFriend ? "UNFRIEND" : "FRIEND";
        $buttonClass=$isFriend?"unfriend button":"friend button";
        $action="friend(\"$userTo\", \"$userLoggedIn\", this)";

        $button=ButtonProvider::createButton($buttonText, null, $action, $buttonClass);

        return "<div class='friendButtonContainer'>
                    $button
                </div>";
    }

    public static function createUserProfileNavigationButton($con, $username) {
        if(User::isLoggedIn()) {
            return ButtonProvider::createUserProfileButton($con, $username);
        }
        else {
            return "<a href='signIn.php'>
                        <span class='signInLink'>SIGN IN</span>
                    </a>";
        }
    }
}
?>