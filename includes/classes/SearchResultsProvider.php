<?php
class SearchResultsProvider {
    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function getInvites($term, $orderBy) {
        $query=$this->con->prepare("SELECT * FROM invites WHERE title LIKE CONCAT('%', :term, '%')
                                    OR uploadedBy LIKE CONCAT('%', :term, '%') ORDER BY $orderBy DESC");
        $query->bindParam(":term", $term);
        $query->execute();

        $invites=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            if($row["privacy"]==1) {
                $invite= new Invite($this->con, $row, $this->userLoggedInObj);
                array_push($invites,$invite);
            }
        }

        return $invites;
    }
}
?>