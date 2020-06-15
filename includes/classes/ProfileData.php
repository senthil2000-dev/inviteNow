<?php

class ProfileData {
    private $con, $profileUserObj;

    public function __construct($con, $profileUsername) {
        $this->con=$con;
        $this->profileUserObj=new User($con, $profileUsername);
    }

    public function getProfileUserObj() {
        return $this->profileUserObj;
    }

    public function getProfileUsername() {
        return $this->profileUserObj->getUsername();
    }

    public function userExists() {
        $query=$this->con->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindParam(":username", $profileUsername);
        $profileUsername=$this->getProfileUsername();
        $query->execute();

        return $query->rowCount() != 0;
    }

    public function getCoverPhoto() {
        return "assets/images/coverPhotos/default-cover-photo.jpg";
    }

    public function getProfileUserFullName() {
        return $this->profileUserObj->getName();
    }

    public function getProfilePic() {
        return $this->profileUserObj->getProfilePic();
    }

    public function getFriendCount() {
        return $this->profileUserObj->getFriendCount();
    }

    public function getFriendlyCount() {
        return sizeof($this->profileUserObj->getFriendly());
    }

    public function getUsersInvites() {
        $query=$this->con->prepare("SELECT * FROM invites WHERE uploadedBy=:uploadedBy ORDER BY uploadDate DESC");
        $query->bindParam(":uploadedBy", $username);
        $username=$this->getProfileUsername();
        $query->execute();

        $invites=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $invites[]=new Invite($this->con, $row, $this->profileUserObj->getUsername());
        }

        return $invites;
    }

    public function getAllUserDetails() {
        return array(
          "Name"=>$this->getProfileUserFullName(),
          "Username"=>$this->getProfileUsername(),  
          "Friends"=>$this->getFriendCount(),
          "Total events"=>$this->getTotalEvents(),
          "Sign up date"=>$this->getSignUpDate()
        );
    }

    private function getTotalEvents() {
        $query=$this->con->prepare("SELECT count(*) FROM invites WHERE uploadedBy=:uploadedBy");
        $query->bindParam(":uploadedBy", $username);
        $username=$this->getProfileUsername();
        $query->execute();

        return $query->fetchColumn();
    }

    private function getSignUpDate() {
        $date=$this->profileUserObj->getSignUpDate();
        return date("F jS, Y", strtotime($date));
    }
}
?>