<?php
class User{

    private $con, $sqlData, $username;

    public function __construct($con, $username) {
        $this->con=$con;

        $query=$this->con->prepare("SELECT * FROM users WHERE username = :un");
        $query->bindParam(":un", $username);
        $query->execute();

        $this->sqlData=$query->fetch(PDO::FETCH_ASSOC);
        $this->username=$username;
    }

    public static function isLoggedIn() {
        return isset($_SESSION["userLoggedIn"]);
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function getName() {
        return $this->sqlData["firstName"] . " " . $this->sqlData["lastName"];
    }

    public function getFirstName() {
        return $this->sqlData["firstName"];
    }

    public function getLastName() {
        return $this->sqlData["lastName"];
    }

    public function getEmail() {
        return $this->sqlData["email"];
    }

    public function getProfilePic() {
        if($this->sqlData)
            return $this->sqlData["profilePic"];
        else
            return "assets/images/profilePictures/default.png";
    }

    public function getSignUpDate() {
        return $this->sqlData["signUpDate"];
    }
    
    public function isFriend($userTo) {
        $query=$this->con->prepare("SELECT * FROM friends WHERE userTo=:userTo and userFrom=:userFrom");
        $query->bindParam(":userTo", $userTo);
        $query->bindParam(":userFrom", $username);
        $username = $this->getUsername();
        $query->execute();
        return $query->rowCount() > 0;        
    }

    public function getFriendCount() {
        $query=$this->con->prepare("SELECT * FROM friends WHERE userFrom=:userFrom");
        $query->bindParam(":userFrom", $username);
        $username = $this->getUsername();
        $query->execute();
        return $query->rowCount();     
    }

    public function getFriends() {
        $query=$this->con->prepare("SELECT userTo FROM friends WHERE userFrom=:userFrom");
        $username=$this->getUsername();
        $query->bindParam(":userFrom", $username);
        $query->execute();

        $subs=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $user=new User($this->con, $row["userTo"]);
            array_push($subs, $user);
        }
        return $subs;
    }

    public function getFriendly() {
        $query=$this->con->prepare("SELECT userFrom FROM friends WHERE userTo=:userTo");
        $username=$this->getUsername();
        $query->bindParam(":userTo", $username);
        $query->execute();

        $subs=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $user=new User($this->con, $row["userFrom"]);
            array_push($subs, $user);
        }
        return $subs;
    }
}
?>
