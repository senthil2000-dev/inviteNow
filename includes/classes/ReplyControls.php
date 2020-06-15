<?php
require_once("ButtonProvider.php");

class ReplyControls{
    private $con, $reply, $userLoggedInObj;

    public function __construct($con, $reply, $userLoggedInObj) {
        $this->con=$con;
        $this->reply=$reply;
        $this->userLoggedInObj=$userLoggedInObj;
}

public function create() {

    $responseButton=$this->createResponseButton();
    $responseSection=$this->createResponseSection();

    return "<div class='controls'>
                $responseButton
            </div>
            $responseSection";
}

private function createResponseButton() {
    $text="CHAT";
    $action="toggleResponse(this)";
    return ButtonProvider::createButton($text, null, $action, null);
}

private function createResponseSection() {
        $postedBy = $this->userLoggedInObj->getUsername();
        $inviteId=$this->reply->getInviteId();
        $replyId=$this->reply->getId();
        
        $profilePic=ButtonProvider::createUserProfileButton($this->con, $postedBy);

        $cancelButtonAction="toggleResponse(this)";
        $cancelButton=ButtonProvider::createButton("Cancel", null, $cancelButtonAction, "cancelReply");

        $postButtonAction="postReply(this, \"$postedBy\", $inviteId, $replyId, \"responsesSection\")";
        $postButton=ButtonProvider::createButton("Send", null, $postButtonAction, "postReply");

        return "<div class='replyForm responseForm hidden'>
                    $profilePic
                    <textarea class='replyBodyClass' placeholder='Add a public response'></textarea>
                    $cancelButton
                    $postButton
                </div>";
}

}
?>