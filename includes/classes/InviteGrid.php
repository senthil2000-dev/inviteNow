<?php
class InviteGrid {
    private $con, $userLoggedInObj;
    private $largeMode=false;
    private $gridClass="inviteGrid";
    private $id2=0;

    public function __construct($con, $userLoggedInObj) {
        $this->con=$con;
        $this->userLoggedInObj=$userLoggedInObj;
    }

    public function create($invites, $title, $showFilter, $received=false, $days=0) {

        if($invites==null && $received==false) {
            $gridItems=$this->generateItems();
        }
        else {
            $gridItems=$this->generateItemsFromInvites($invites);
        }

        $header="";

        if($title != null) {
            $header=$this->createGridHeader($title, $showFilter, $received, $days);
        }

        return "$header
                <div class='$this->gridClass'>
                    $gridItems
                </div>";
    }

    public function generateItems() {
        $privacy=1;
        $query=$this->con->prepare("SELECT * FROM invites WHERE privacy=:privacy ORDER BY RAND() LIMIT 15");
        $query->bindParam(":privacy", $privacy);
        $query->execute();

        $elementsHtml="";
        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $invite = new Invite($this->con, $row, $this->userLoggedInObj);
            $item=new InviteGridItem($invite, $this->largeMode);
            $elementsHtml.=$item->create();
        }

        return $elementsHtml;
    }

    public function generateItemsFrominvites($invites) {
        $elementsHtml="";

        foreach($invites as $invite) {
            $item=new InviteGridItem($invite, $this->largeMode);
            if($this->id2==0) {
                $elementsHtml.=$item->create();
            }
            else {
                $elementsHtml.=$item->creating($this->id2);
            }
            
        }

        return $elementsHtml;
    }

    public function createGridHeader($title, $showFilter, $received, $days) {
        $filter="";
        $values=[7, 31, 183, 365, 730, 1095, 1460, 1826];
        $text=["Last week", "Last Month", "Last 6 Months", "Last 1 year", "Last 2 years", "Last 3 years", "Last 4 years", "Last 5 years"];
        $html="";
        for($i=0 ; $i<sizeof($values); ++$i) {
            if($days!=$values[$i])
                $html.="<option value=$values[$i]>$text[$i]</option>";
            elseif($days==0){
                $html.="<option value=$values[$i] selected>$text[$i]</option>";
            }
            else{
                $html.="<option value=$values[$i] selected>$text[$i]</option>";
            }
        }

        if($received) {
            $query=$this->con->prepare("SELECT statusPaused FROM users WHERE username=:user");
            $query->bindParam(":user", $username);
            $username=$this->userLoggedInObj->getUsername();
            $query->execute();
            $_SESSION["status"]=$query->fetchColumn();
            $checked=$_SESSION["status"]?"checked":"";
            $filter="<form id='submitForm' action='received.php' method='GET'>
                    <div class='right'>
                        <span class='deleteMessage' onclick='deleteAll2()'>Clear Inbox</span>
                        <img class='deleteSearch' onmouseover='hover(this)' onmouseout='unhover(this)' onclick='deleteAll2()' src='assets\images\icons\deletefull.png' title='delete all received invites' alt='Clear Inbox'></img>
                        <label>Pause Invite Inbox:</label>
                        <label class='switch'>
                        <input type='checkbox' id='checking' onchange='status()' $checked>
                        <span class='slider round'></span>
                        </label>
                         <label for='range' id='split'>Choose a range of time:</label>
                         <select name='rangeValue' id='range' onchange='submitForm2()'>
                         $html
                         </select>
                     </div>
                     </form>";
         }

        if($showFilter) {
           $link="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
           $urlArray=parse_url($link);
           $query=$urlArray["query"];
           parse_str($query,$params);

           unset($params["orderBy"]);

           $newQuery=http_build_query($params);
           $newUrl=basename($_SERVER["PHP_SELF"]) . "?" . $newQuery;

           $filter="<div class='right'>
                        <span>Order by:</span>
                        <a href='$newUrl&orderBy=uploadDate'>Upload date</a>
                        <a href='$newUrl&orderBy=accepted'>Most accepted</a>
                    </div>";
        }

        

        return "<div class='inviteGridHeader'>
                    <div class='left'>
                        $title
                    </div>
                    $filter
                </div>";
    }

    public function createLarge($invites, $title, $showFilter, $received=false, $days=0) {
        $this->gridClass .= " large";
        $this->largeMode=true;
        return $this->create($invites, $title, $showFilter, $received, $days);
    }

    public function create2($invites, $title, $showFilter, $id, $received=false, $days=0) {
        $this->id2=$id;
        return $this->create($invites, $title, $showFilter, $received, $days);
    }
}
?>