<?php
require_once("ProfileData.php");
class ProfileGenerator {
    private $con, $userLoggedInObj, $profileData;

    public function __construct($con, $userLoggedInObj, $profileUsername) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
        $this->profileData=new ProfileData($con, $profileUsername);
    }

    public function create() {
        $profileUsername=$this->profileData->getProfileUsername();
        
        if(!$this->profileData->userExists()) {
            return "User does not exist";
        }

        $coverPhotoSection=$this->createCoverPhotoSection();
        $headerSection=$this->createHeaderSection();
        $tabsSection=$this->createTabsSection();
        $contentSection=$this->createContentSection();

        return "<div class='profileContainer'>
                    $coverPhotoSection
                    $headerSection
                    $tabsSection
                    $contentSection
                </div>";
    }

    public function createCoverPhotoSection() {
        $coverPhotoSrc=$this->profileData->getCoverPhoto();
        $name=$this->profileData->getProfileUserFullName();
        return "<div class='coverPhotoContainer'>
                    <img src='$coverPhotoSrc' class='coverPhoto'>
                    <div>
                    <span class='channelName'>$name</span>
                    </div>
                </div>";
}

    public function createHeaderSection() {
        $profileImage=$this->profileData->getProfilePic();
        $name=$this->profileData->getProfileUserFullName();
        $subCount=$this->profileData->getFriendCount();
        $num=$this->profileData->getFriendlyCount();
        $button=$this->createHeaderButton();

        return "<div class='profileHeader'>
                    <div class='userInfoContainer'>
                        <img class='profileImage' src='$profileImage'>
                        <div class='userInfo'>
                            <span class='title'>$name</span>
                            <span class='friendCount'>He has $subCount friends and  $num people made him as friends</span>
                        </div>
                    </div>

                    <div class='buttonContainer'>
                        <div class='buttonItem'>
                            $button
                        </div>
                    </div>
                </div>";
    }

    public function createTabsSection() {
        return "<ul class='nav nav-tabs' role='tablist'>
                    <li class='nav-item'>
                    <a class='nav-link active' id='invites-tab'>INVITES</a>
                    </li>
                    <li class='nav-item'>
                    <a class='nav-link' id='about-tab'>ABOUT</a>
                    </li>
                </ul>";
    }

    public function createContentSection() {

        $invites=$this->profileData->getUsersInvites();


        if(sizeof($invites)>0) {
            $inviteGrid=new InviteGrid($this->con, $this->userLoggedInObj);
            $inviteGridHtml=$inviteGrid->create($invites, null, false);
        }
        else {
            $inviteGridHtml="<span>This user has no invites</span>";
        }

        $aboutSection=$this->createAboutSection();

        return "<div class='tab-content channelContent'>
                    <div class='tab-pane active' id='invites'>
                        $inviteGridHtml
                    </div>
                    <div class='tab-pane' id='about'>
                        $aboutSection
                    </div>
                </div>";
    }

    private function createHeaderButton() {
        if($this->userLoggedInObj->getUsername() == $this->profileData->getProfileUserName()) {
            return "<form id='file-upload' enctype='multipart/form-data'>
                            <label for='exampleFormControlFile1' class='btn btn-primary' >Change profile pictue</label>
                            <input type='file' class='form-control-file' name='image' onchange='submitForm()' id='exampleFormControlFile1' required hidden>
                            <button type='submit' id= 'press' hidden></button>
                     </form>";
        }
        else {
            return ButtonProvider::createFriendsButton($this->con, $this->profileData->getProfileUserObj(), $this->userLoggedInObj);
        }
     }

    private function createAboutSection() {
        $html="<div class='section'>
                    <div class='title'>
                        <span>Details</span>
                    </div>
                    <div class='values'>";

        $details=$this->profileData->getAllUserDetails();
        foreach($details as $key=>$value) {
            $html.= "<span>$key: $value</span>";
        }

        $html.= "</div></div>";

        return $html;
    }
}
?>