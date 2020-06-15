<?php
require_once("includes/classes/ButtonProvider.php");

class InviteInfoControls{
    private $invite, $userLoggedInObj;

    public function __construct($invite, $userLoggedInObj) {
        $this->invite=$invite;
        $this->userLoggedInObj=$userLoggedInObj;
}

public function create() {
    $today = date("Y-m-d H:i:s");
    $date=$this->invite->getDeadline();
    $deadlinePassed=($today>$date)? true: false;
    $acceptButton=$this->createAcceptButton();
    $rejectButton=$this->createRejectButton();
    $button=ButtonProvider::createButton("DEADLINE PASSED", null, '', "friend button");
    if($deadlinePassed) {
        return "<div class='controls'>
                $button
            </div>";
    }
    else {
        return "<div class='controls'>
                $acceptButton
                $rejectButton
            </div>";
    }
    
}

private function createAcceptButton(){
    $acceptText=$this->invite->wasAcceptedBy()?"ACCEPTED ":"ACCEPT ";
    $text=$acceptText.$this->invite->getAcceptedNo();
    $inviteId=$this->invite->getId();
    $action="acceptInvite(this, $inviteId)";
    $class="acceptButton";

    $imageSrc="assets/images/icons/thumb-up.png";

    if($this->invite->wasAcceptedBy()) {
        $imageSrc="assets/images/icons/thumb-up-active.png";
    }

    return ButtonProvider::createButton($text, $imageSrc, $action, $class);
}

private function createRejectButton(){
    $rejectText=$this->invite->wasRejectedBy()?"REJECTED ":"DECLINE ";
    $text=$rejectText.$this->invite->getRejectedNo();
    $inviteId=$this->invite->getId();
    $action="rejectInvite(this, $inviteId)";
    $class="rejectButton";

    $imageSrc="assets/images/icons/thumb-down.png";

    if($this->invite->wasRejectedBy()) {
        $imageSrc="assets/images/icons/thumb-down-active.png";
    }

    return ButtonProvider::createButton($text, $imageSrc, $action, $class);
}

}
?>