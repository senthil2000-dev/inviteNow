<?php
class Invite{

    private $con, $sqlData, $userLoggedInObj;

    public function __construct($con, $input, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;

        if(is_array($input)){
            $this->sqlData=$input;
        }
        else{
            $query=$this->con->prepare("SELECT * FROM invites WHERE id = :id");
            $query->bindParam(":id", $input);
            $query->execute();

            $this->sqlData=$query->fetch(PDO::FETCH_ASSOC);
        }
        
    }

    public function getId() {
        return $this->sqlData["id"];
    }

    public function getUploadedBy() {
        return $this->sqlData["uploadedBy"];
    }

    public function getTitle() {
        return $this->sqlData["title"];
    }

    public function getDescription() {
        return $this->sqlData["description"];
    }

    public function getPrivacy() {
        return $this->sqlData["privacy"];
    }

    public function getTemplate() {
        return $this->sqlData["theme"];
    }

    public function getEventDate() {
        return date("M j, Y", strtotime($this->sqlData["dateEvent"]));
    }

    public function getDeadlineFormatted() {
        return date("M j, Y h:i a", strtotime($this->sqlData["deadlineInvite"]));
    }
    
    public function getDeadline() {
        return $this->sqlData["deadlineInvite"];
    }

    public function getDate() {
        return $this->sqlData["dateEvent"];
    }

    public function getContent() {
        return $this->sqlData["content"];
    }
    
    public function getMembers() {
        $members=$this->sqlData["members"];
        $invitees=explode(";", $members);
        return $invitees;
    }

    public function getCategory() {
        return $this->sqlData["category"];
    }

    public function isAdded() {
        $id=$this->getId();
        $user=$this->userLoggedInObj->getUsername();
        $query=$this->con->prepare("SELECT added FROM accepted WHERE username=:username and inviteId=:inviteId");
        $query->bindParam(":username", $user);
        $query->bindParam(":inviteId", $id);
        $query->execute();
        return $query->fetchColumn();
    }

    public function getCategoryName() {
        $categoryId = $this->sqlData["category"];
        $query=$this->con->prepare("SELECT name FROM categories WHERE id = :id");
            $query->bindParam(":id", $categoryId);
            $query->execute();
        return $query->fetchColumn();
    }

    public function getUploadDate() {
        $date=$this->sqlData["uploadDate"];
        return date("M j, Y", strtotime($date));
    }

    public function getTimeStamp() {
        $date=$this->sqlData["uploadDate"];
        return date("M jS, Y", strtotime($date));
    }

    public function getAttendance() {
        $query=$this->con->prepare("SELECT SUM(num) as'num' FROM accepted WHERE inviteId=:inviteId");
        $query->bindParam(":inviteId", $inviteId);
        $inviteId=$this->getId();
        $query->execute();
        $data=$query->fetch(PDO::FETCH_ASSOC);
        return $data["num"];
    }

    public function getVeg() {
        $query=$this->con->prepare("SELECT veg, count(veg) from accepted WHERE inviteId=:inviteId group by veg");
        $query->bindParam(":inviteId", $inviteId);
        $inviteId=$this->getId();
        $query->execute();
        $vegetarians=0;
        $nonvegetarians=0;
        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            if($row["veg"]=="0") {
                $vegetarians=$row["count(veg)"];
            }
            else if($row["veg"]=="1") {
                $nonvegetarians=$row["count(veg)"];
            }
        }
        if($vegetarians==0 && $nonvegetarians==0)
            return "No food preferences chosen";
        $vegper=round($vegetarians*100/($vegetarians+$nonvegetarians));
        $nonvegper=round($nonvegetarians*100/($vegetarians+$nonvegetarians));
        return "$vegper% prefer vegetarian<br>$nonvegper% prefer nonvegetarian";
    }

    public function getSouth() {
        $query=$this->con->prepare("SELECT south, count(south) from accepted WHERE inviteId=:inviteId group by south");
        $query->bindParam(":inviteId", $inviteId);
        $inviteId=$this->getId();
        $query->execute();
        $southIndian=0;
        $northIndian=0;
        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            if($row["south"]=="0") {
                $southIndian=$row["count(south)"];
            }
            else if($row["south"]=="1") {
                $northIndian=$row["count(south)"];
            }
        }
        if($southIndian==0 && $northIndian==0)
            return "No food preferences chosen";
        $southper=round($southIndian*100/($southIndian+$northIndian));
        $northper=round($northIndian*100/($southIndian+$northIndian));
        return "$southper% prefer southIndian<br>$northper% prefer northIndian";
    }

        public function getAcceptedNo() {
        $query=$this->con->prepare("SELECT count(*) as'count' FROM accepted WHERE inviteId=:inviteId");
        $query->bindParam(":inviteId", $inviteId);

        $inviteId=$this->getId();
        $query->execute();

        $data=$query->fetch(PDO::FETCH_ASSOC);
        return $data["count"];
        }

        public function getRejectedNo() {
            $query=$this->con->prepare("SELECT count(*) as'count' FROM rejected WHERE inviteId=:inviteId");
            $query->bindParam(":inviteId", $inviteId);
    
            $inviteId=$this->getId();
            $query->execute();
    
            $data=$query->fetch(PDO::FETCH_ASSOC);
            return $data["count"];
            }

            public function accept() {
                $id=$this->getId();
                $username=$this->userLoggedInObj->getUsername();

                if($this->wasAcceptedBy()) {
                    $this->decrementAccepted();
                    $query=$this->con->prepare("DELETE FROM accepted WHERE username=:username and inviteId=:inviteId");
                    $query->bindParam(":username", $username);
                    $query->bindParam(":inviteId", $id);
                    $query->execute();

                    $result=array(
                        "accepted"=>-1,
                        "rejected"=>0

                    );
                    return json_encode($result);

                }
                else {
                    $this->incrementAccepted();
                    $query=$this->con->prepare("DELETE FROM rejected WHERE username=:username and inviteId=:inviteId");
                    $query->bindParam(":username", $username);
                    $query->bindParam(":inviteId", $id);
                    $query->execute();
                    $count=$query->rowCount();
                    
                    $query=$this->con->prepare("INSERT INTO accepted(username, inviteId) VALUES(:username, :inviteId)");
                    $query->bindParam(":username", $username);
                    $query->bindParam(":inviteId", $id);
                    $query->execute();
                    $result=array(
                        "accepted"=>1,
                        "rejected"=>0-$count
                    );
                    return json_encode($result);

                }
            }

            public function reject() {
                $id=$this->getId();
                $username=$this->userLoggedInObj->getUsername();
                if($this->wasRejectedBy()) {
                    $query=$this->con->prepare("DELETE FROM rejected WHERE username=:username and inviteId=:inviteId");
                    $query->bindParam(":username", $username);
                    $query->bindParam(":inviteId", $id);
                    $query->execute();

                    $result=array(
                        "accepted"=>0,
                        "rejected"=>-1
                    );
                    return json_encode($result);

                }
                else {
                    $query=$this->con->prepare("DELETE FROM accepted WHERE username=:username and inviteId=:inviteId");
                    $query->bindParam(":username", $username);
                    $query->bindParam(":inviteId", $id);
                    $query->execute();
                    $count=$query->rowCount();
                    $k=$count;
                    while($k>0) {
                        $this->decrementAccepted();
                        $k--;
                    }
                    $query=$this->con->prepare("INSERT INTO rejected(username, inviteId) VALUES(:username, :inviteId)");
                    $query->bindParam(":username", $username);
                    $query->bindParam(":inviteId", $id);
                    $query->execute();
                    $result=array(
                        "accepted"=>0-$count,
                        "rejected"=>1

                    );
                    return json_encode($result);

                }
            }

            public function wasAcceptedBy() {
                $query=$this->con->prepare("SELECT * FROM accepted WHERE username=:username and inviteId=:inviteId");
                $query->bindParam(":username", $username);
                $query->bindParam(":inviteId", $id);

                $id=$this->getId();

                $username=$this->userLoggedInObj->getUsername();
                $query->execute();

                return $query->rowCount() >0;
            }

            public function wasRejectedBy() {
                $query=$this->con->prepare("SELECT * FROM rejected WHERE username=:username and inviteId=:inviteId");
                $query->bindParam(":username", $username);
                $query->bindParam(":inviteId", $id);

                $id=$this->getId();

                $username=$this->userLoggedInObj->getUsername();
                $query->execute();

                return $query->rowCount() >0;
            }

            public function getNumberOfReplies() {
                $query=$this->con->prepare("SELECT * FROM replies WHERE inviteId=:inviteId");
                $query->bindParam(":inviteId", $id);

                $id=$this->getId();
                $query->execute();

                return $query->rowCount();
            }

            public function getReplies() {
                $query=$this->con->prepare("SELECT * FROM replies WHERE inviteId=:inviteId AND responseTo=0 ORDER by datePosted DESC");
                $query->bindParam(":inviteId", $id);

                $id=$this->getId();
                $query->execute();

                $replies=array();

                while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    $reply=new Reply($this->con, $row, $this->userLoggedInObj, $id);
                    array_push($replies, $reply);
                }

                return $replies;
            }

        public function getCover() {
            $query=$this->con->prepare("SELECT filePath FROM coverphotos WHERE inviteId=:inviteId");
            $query->bindParam(":inviteId", $inviteId);
            $inviteId=$this->getId();
            $query->execute();
            return $query->fetchColumn();
        }

        private function incrementAccepted() {
            $query=$this->con->prepare("UPDATE invites SET accepted=accepted+1 where id=:id");
            $query->bindParam(":id", $inviteId);
    
            $inviteId=$this->getId();
            $query->execute();
    
            $this->sqlData["accepted"]=$this->sqlData["accepted"]+1;
        }

        private function decrementAccepted() {
            $query=$this->con->prepare("UPDATE invites SET accepted=accepted-1 where id=:id");
            $query->bindParam(":id", $inviteId);
    
            $inviteId=$this->getId();
            $query->execute();
    
            $this->sqlData["accepted"]=$this->sqlData["accepted"]-1;
        }

}
?>