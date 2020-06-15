<?php
class YourInvitationsProvider {
    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function getInvites() {
        $invites=array();
        $username=$this->userLoggedInObj->getUsername();
        $query=$this->con->prepare("SELECT id FROM invites WHERE uploadedBy=:username");
        $query->bindParam(":username", $username);
        $query->execute();

        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $invites[]=new Invite($this->con, $row["id"], $this->userLoggedInObj);
        }
        return $invites;
    }
}
?>
