<?php
class FriendlyProvider {
    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function getInvites() {
        $invites=array();
        $friendly=$this->userLoggedInObj->getFriendly();

        if(sizeof($friendly)>0) {
            
            $condition="";
            $i=0;
            while($i<sizeof($friendly)) {
                
                if($i==0) {
                    $condition.= "WHERE uploadedBy=?";
                }
                else {
                    $condition.= " OR uploadedBy=?";
                }
                $i++;
            }

            $inviteSql="SELECT * FROM received $condition ORDER BY time DESC";
            $inviteQuery=$this->con->prepare($inviteSql);

            $i=1;
            foreach($friendly as $user) {
                 $subUsername=$user->getUsername();
                 $inviteQuery->bindValue($i, $subUsername);
                 $i++;
            }

            $inviteQuery->execute();
            while($row=$inviteQuery->fetch(PDO::FETCH_ASSOC)) {
                $invite=new Invite($this->con, $row["inviteId"], $this->userLoggedInObj);
                array_push($invites, $invite);
            }


        }
        
        return $invites;
    }
}
?>