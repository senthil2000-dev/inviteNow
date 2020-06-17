<?php
class InviteGridItem{

    private $invite, $largeMode;

    public function __construct($invite, $largeMode)  {
        $this->invite=$invite;
        $this->largeMode=$largeMode;
    }

    public function create() {
        $cover=$this->createCover();
        $details=$this->createDetails();
        $url = "read.php?id=".$this->invite->getId();
        $id=$this->invite->getId();
        $add="editInvite.php?inviteId=".$id;
        $dateOfEvent=$this->invite->getDate();
        $title=$this->invite->getTitle();
        $description=$this->invite->getDescription();
        $text="Remove";
        $info="";
        $calendarOption="";
        $editOption="";
        if(basename($_SERVER["PHP_SELF"])=="editInvites.php") {
            $editOption="<span class='editing $add'>Edit</span>";
            $text="Cancel";
            $attendance=$this->invite->getAttendance()?$this->invite->getAttendance():0;
            $veg=$this->invite->getVeg();
            $south=$this->invite->getSouth();
            $info="<button onclick='attendance()' class='btn btn-primary'>$attendance attendees<br>$veg<br>$south</button>";
        }
        else if(basename($_SERVER["PHP_SELF"])=="accepted.php") {
            $added=$this->invite->isAdded();
            $calendarText=($added==1)?"ADDED TO GOOGLE CALENDAR":"ADD TO GOOGLE CALENDAR";
            $calendarOption=(basename($_SERVER["PHP_SELF"])=="accepted.php")? "<button onclick='submitDate(this, \"$id\", \"$dateOfEvent\", \"$title\", \"$description\")' style='margin: auto 5px auto auto;' class='btn btn-primary $id'>$calendarText</button>": "";
        }
        
        if(basename($_SERVER["PHP_SELF"])=="received.php"||basename($_SERVER["PHP_SELF"])=="editInvites.php") {
            $delete="<div class='dropdown'>
                        <ul class='dropbtn icons btn-right showLeft $id'>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                        <div class='dropdown-content myDropdown' id='$id'>
                            <span class='removing $id'>$text</span>
                            $editOption
                        </div>
                    </div>";
            return "<a class='flexing' href='$url'>
                        <div class='inviteGridItem'>
                            $cover
                            $details
                            $info
                            $delete
                        </div>
                    </a>";
        }
        return "<a id='$id' href='$url'>
                    <div class='inviteGridItem'>
                        $cover
                        $details
                        $calendarOption
                    </div>
                </a>";
    }

    public function createCover() {
        
        $cover=$this->invite->getCover();
        $category=$this->invite->getCategoryName();

        return "<div class='cover'>
                    <img src='$cover'>
                    <div class='category'>
                        <span>$category</span>
                    </div>
                </div>";

    }

    private function createDetails() {
        $title=$this->invite->getTitle();
        $username=$this->invite->getUploadedBy();
        $description=$this->createDescription();
        $timestamp=$this->invite->getTimeStamp();
        $accepted=$this->invite->getAcceptedNo();
        return "<div class='details'>
                    <h3 class='title'>$title</h3>
                    <span class='username'>$username</span>
                    <div class='stats'>
                        <span class='acceptedCount'>$accepted accepted - </span>
                        <span class='timeStamp'>$timestamp</span>
                    </div>
                    $description
                </div>";

    }

    private function createDescription() {
        if(!$this->largeMode) {
            return "";
        }
        else {
            $description=$this->invite->getDescription();
            $description=(strlen($description)>350) ? substr($description, 0, 347) . "..." : $description;
            return "<span class='description'>$description</span>";
        }
    }
}
?>
