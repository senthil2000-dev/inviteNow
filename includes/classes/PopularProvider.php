<?php
class PopularProvider {
    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function getInvites() {
        $invites=array();
        $privacy=1;
        $query=$this->con->prepare("SELECT * FROM invites WHERE privacy=:privacy AND uploadDate>=now()-INTERVAL 7 DAY ORDER BY accepted DESC LIMIT 15");
        $query->bindParam(":privacy", $privacy);
        $query->execute();

        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $invite=new Invite($this->con, $row, $this->userLoggedInObj);
            array_push($invites, $invite);
        }

        return $invites;
    }
}
?>