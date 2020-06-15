<?php
class  ReplySection {
    private $con, $invite, $userLoggedInObj;

    public function __construct($con, $invite, $userLoggedInObj) {
            $this->invite=$invite;
            $this->con=$con;
            $this->userLoggedInObj=$userLoggedInObj;
    }

    public function create() {
        return $this->createReplySection();
    }

    private function createReplySection() {
        $numReplies=$this->invite->getNumberOfReplies();
        $postedBy = $this->userLoggedInObj->getUsername();
        $inviteId=$this->invite->getId();
        
        $profilePic=ButtonProvider::createUserProfileButton($this->con, $postedBy);
        $replyAction="postReply(this, \"$postedBy\", $inviteId, null, \"replies\")";
        $replyButton=ButtonProvider::createButton("REPLY", null, $replyAction, "postReply");

        $replies=$this->invite->getReplies();
        $replyItems="";
        foreach($replies as $reply) {
            $replyItems.=$reply->create();
        }

        return "<div class='replySection'>

                    <div class='header'>
                        <span class='replyCount'>$numReplies chats</span>

                        <div class='replyForm'>
                            $profilePic
                            <textarea class='replyBodyClass' placeholder='Add a public reply'></textarea>
                            $replyButton
                        </div>
                    </div>

                    <div class='replies'>
                        $replyItems
                    </div>

                </div>";
    }
 }
?>