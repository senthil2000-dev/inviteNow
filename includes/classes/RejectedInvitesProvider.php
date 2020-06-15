<?php
class RejectedInvitesProvider {
    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function getInvites() {
        $invites=array();
        $query=$this->con->prepare("SELECT * FROM rejected WHERE username=:user ORDER BY id DESC");
        $query->bindParam(":user", $user);
        $user=$this->userLoggedInObj->getUsername();
        $query->execute();

        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $invite=new Invite($this->con, $row["inviteid"], $this->userLoggedInObj);
            array_push($invites, $invite);
        }

        return $invites;
    }
}
?>