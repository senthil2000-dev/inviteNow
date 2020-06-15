<?php
require_once("ButtonProvider.php");
require_once("ReplyControls.php");
class  Reply{

    private $con, $sqlData, $userLoggedInObj, $inviteId;

    public function __construct($con, $input, $userLoggedInObj, $inviteId) {

        if(!is_array($input)) {
            $query = $con->prepare("SELECT * FROM replies WHERE id=:id");
            $query->bindParam(":id", $input);
            $query->execute();

            $input=$query->fetch(PDO::FETCH_ASSOC);
        }
        $this->sqlData=$input;
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
        $this->inviteId=$inviteId;

    }

    public function create() {
        $id=$this->sqlData["id"];
        $inviteId=$this->getInviteId();
        $body=$this->sqlData["body"];
        $postedBy=$this->sqlData["postedBy"];

        $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);
        $timespan=$this->time_elapsed_string($this->sqlData["datePosted"]);

        $replyControlsObj=new ReplyControls($this->con, $this, $this->userLoggedInObj);
        $replyControls=$replyControlsObj->create();

        $numResponses=$this->getNumberOfResponses();

        if($numResponses>0) {
            $viewResponsesText="<span class='responsesSection viewResponses'><span onClick='getResponses($id, this, $inviteId)'>
                                    View all $numResponses chats</span></span>";
        }
        else {
            $viewResponsesText="<div class='responsesSection'></div>";
        }

        return "<div class='itemContainer' id='$id'>
                    <div class='reply'>
                        $profileButton

                        <div class='mainContainer'>
                            <div class='replyHeader'>
                                <a href='profile.php?username=$postedBy'>
                                    <span class='username'>$postedBy</span>
                                </a>
                                <span class='timestamp'>$timespan</span>
                            </div>

                            <div class='body'>
                                $body
                            </div>

                        </div>
                    </div>

                    $replyControls
                    $viewResponsesText
                </div>";
    }

    public function getNumberOfResponses() {
        $query=$this->con->prepare("SELECT count(*) FROM replies WHERE responseTo=:responseTo");
        $query->bindParam(":responseTo", $id);
        $id=$this->sqlData["id"];
        $query->execute();

        return $query->fetchColumn();
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function getId() {
        return $this->sqlData["id"];
    }

    public function getInviteId() {
        return $this->inviteId;
    }

    public function getResponses($ids) {
                $query=$this->con->prepare("SELECT * FROM replies WHERE responseTo=:replyId ORDER by datePosted ASC");
                $query->bindParam(":replyId", $id);

                $id=$this->getId();
                $query->execute();

                $replies="";
                $inviteId=$this->getInviteId();
                while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    if(!(in_array($row["id"], $ids))) {
                        $reply=new Reply($this->con, $row, $this->userLoggedInObj, $inviteId);
                        $replies.=$reply->create();
                    }
                }

                return $replies;
    }

}

?>