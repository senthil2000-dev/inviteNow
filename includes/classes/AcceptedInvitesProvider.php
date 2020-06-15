<?php
class AcceptedInvitesProvider {
    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function getInvites() {
        $invites=array();
        $query=$this->con->prepare("SELECT inviteId FROM accepted WHERE username=:username ORDER BY id DESC");
        $query->bindParam(":username", $username);
        $username=$this->userLoggedInObj->getUsername();
        $query->execute();

        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $invites[]=new Invite($this->con, $row["inviteId"], $this->userLoggedInObj);
        }

        return $invites;
    }
}
?>