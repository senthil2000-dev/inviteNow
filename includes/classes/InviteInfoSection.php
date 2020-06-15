<?php
require_once("includes/classes/InviteInfoControls.php");
class  InviteInfoSection {
    private $con, $invite, $userLoggedInObj;

    public function __construct($con, $invite, $userLoggedInObj) {
            $this->invite=$invite;
            $this->con=$con;
            $this->userLoggedInObj=$userLoggedInObj;
    }

    public function create() {
        return $this->createPrimaryInfo() . $this->createSecondaryInfo();
    }

    private function createPrimaryInfo() {
        $title=$this->invite->getTitle();
        $accepted=$this->invite->getAcceptedNo();
        $eventDate=$this->invite->getEventDate();
        $deadline=$this->invite->getDeadlineFormatted();
        $inviteInfoControls=new InviteInfoControls($this->invite, $this->userLoggedInObj);
        $controls=$inviteInfoControls->create();

        return "<div class='inviteInfo'>
                    <h1>$title</h1>
                    <h4>Event scheduled on $eventDate and deadline to accept invitation is $deadline</h4>
                    <div class='bottomSection'>
                        <span class='acceptedCount'>$accepted people accepted this invite</span>
                        $controls
                    </div>
                </div>";
    }

    private function createSecondaryInfo() {

        $description=$this->invite->getDescription();
        $uploadDate=$this->invite->getUploadDate();
        $uploadedBy=$this->invite->getUploadedBy();
        $profileButton=ButtonProvider::createUserProfileButton($this->con, $uploadedBy);

        if($uploadedBy == $this->userLoggedInObj->getUsername()) {
            $actionButton=ButtonProvider::createEditInviteButton($this->invite->getId());
        }
        else {
            $userToObj=new User($this->con, $uploadedBy);
            $actionButton=ButtonProvider::createFriendsButton($this->con, $userToObj, $this->userLoggedInObj);
            //$actionButton="";
        }
        $btn="btn";
        $speak="<a class='iconMargin' id='speaker'>
                    <i class='fas fa-2x fa-microphone'></i>
                </a>";
        $share="<a class='iconMargin' id='$btn'>
                    <i class='fas fa-2x fa-share-square'></i>
                </a>";
        return "<div class='secondaryInfo'>
                    <div class='topRow'>
                    $profileButton

                    <div class='uploadInfo'>
                        <span class='owner'>
                            <a href='profile.php?username=$uploadedBy'>
                                $uploadedBy
                            </a>
                        </span>
                        <span class='date'>Sent on $uploadDate</span>
                    </div>
                    $speak
                    $share
                    $actionButton
                    </div>

                    <div class='descriptionContainer'>
                        $description
                    </div>
        
                </div>";
    }
}
?>