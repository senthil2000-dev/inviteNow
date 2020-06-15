<?php
require_once("includes/header.php");
require_once("includes/classes/InviteRead.php");
require_once("includes/classes/InviteInfoSection.php");
require_once("includes/classes/Reply.php");
require_once("includes/classes/ReplySection.php");

if(!isset($_GET["id"])) {
    echo "No url passed into page";
    exit();
}

$invite=new Invite($con, $_GET["id"], $userLoggedInObj);
$members=$invite->getMembers();
$uploadedBy=$invite->getUploadedBy();
if($members[0]!="open") {
  if(!(in_array($usernameLoggedIn, $members, true))&&$uploadedBy!=$usernameLoggedIn) {
    echo "Private Invitation. Permission not granted";
      exit();
  }
}

$username=$userLoggedInObj->getUsername();
$action="has seen";
$query=$con->prepare("SELECT * FROM notifications WHERE postedBy=:user AND invite_replyId=:inviteId AND action=:action");
$query->bindParam(":user", $username);
$query->bindParam(":inviteId", $_GET["id"]);
$query->bindParam(":action", $action);
$query->execute();
if($query->rowCount()==0) {
      $query=$con->prepare("INSERT INTO notifications(postedBy, invite_replyId, action) VALUES(:user, :invite_replyId, :action)");
      $query->bindParam(":user", $username);
      $query->bindParam(":invite_replyId", $_GET["id"]);
      $query->bindParam(":action", $action);
      $query->execute();
}
?>
<script src="assets/js/inviteReadActions.js"></script>

<script src="assets/js/replyActions.js"></script>

<div class="readLeftColumn">
<div class='invitation'>
<?php
    $inviteInfo = new InviteInfoSection($con, $invite, $userLoggedInObj);
    echo $inviteInfo->create();

    $inviteRead = new InviteRead($invite);
    echo $inviteRead->create();
?>
</div>
<?php
    $replySection = new ReplySection($con, $invite, $userLoggedInObj);
    echo $replySection->create();
?>

</div>
<?php $text=$_SERVER['PHP_SELF'];
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : '';
$url=$protocol.$_SERVER["HTTP_HOST"].$text."?id=".$_GET["id"]; ?>
<!-- Trigger/Open The Modal -->
<!-- The Modal -->
<div id="myModal" class="modal1">

  <!-- Modal content -->
  <div class="modal-content1">
    <div class="modal-header1">
      <span class="close1">&times;</span>
      <h2>Share invite through</h2>
    </div>
    <div class="modal-body1" id='bodyMargin'>
      <a id='blue' href="https://twitter.com/intent/tweet?text=<?php echo $url; ?>">
      <i class="fab fa-twitter fa-3x" aria-hidden="true"></i>
      <span>Twitter</span>
      </a>
      <a href="https://web.whatsapp.com/send?text=<?php echo $url; ?>" data-action="share/whatsapp/share">
      <i class="fab fa-whatsapp-square green fa-3x"></i>
      <span id='whats'>Whatsapp</span>
      </a>
      <a href="mailto:?subject=I wanted you to see this site&amp;body=Check out this invite <?php echo $url; ?> ."
        title="Share by Email">
        <i class="fas fa-envelope fa-3x red" id='marginLeft1' aria-hidden="true"></i>
        <span id='marginLeft2'>Email</span>
      </a>
    </div>
    <div class="modal-footer1">
      <h5>Made by InviteNow</h5>
    </div>
  </div>
</div>
<div id="myModal2" class="modal1">
  <div class="modal-content1">
    <div class="modal-header1">
      <span class="close1">&times;</span>
      <h2>THANKS FOR ACCEPTING. FILL YOUR PREFERENCES IF YOU WISH TO</h2>
    </div>
    <div class="modal-body1 polling" id='bodyMargin'>
      <div class="container" id="voting-box">
        <div class="leftOpt" onclick="addleft()">
          <div class="poll">
            <span class="option-size" id="size-one"></span>
            <br>
            <span class="option-title" id="option-one"></span>                
          </div>
        </div>
        <div class="rightOpt" onclick="addright()">
          <div class="poll">
            <span class="option-size" id="size-two"></span>
            <br>
            <span class="option-title" id="option-two"></span>
          </div>
        </div>
      </div>
      <div class="stats-container">
            <span id="total-left">Option A: 1</span>
            <br>
            <span id="total-right">Option B: 1</span>
            <br>
            <span id="total-votes">Total Votes Casted: 2</span>
        </div>
        <div class="container" id="voting-box2">
        <div class="leftOpt" onclick="addleft2()">
          <div class="poll">
            <span class="option-size" id="size-one2"></span>
            <br>
            <span class="option-title" id="option-one2"></span>                
          </div>
        </div>
        <div class="rightOpt" onclick="addright2()">
          <div class="poll">
            <span class="option-size" id="size-two2"></span>
            <br>
            <span class="option-title" id="option-two2"></span>
          </div>
        </div>
      </div>
      <div class="stats-container">
            <span id="total-left2">Option C: 1</span>
            <br>
            <span id="total-right2">Option D: 1</span>
            <br>
            <span id="total-votes2">Total Votes Casted: 2</span>
        </div>
    </div>
    <div class='numberPeople'>
      <input type='number' id='people' name='noOfPeople' value='2'>
      <button class='btn btn-primary' onclick='updateAttendance()'>SUBMIT</button>
    </div>
    <div class="modal-footer1">
      <h5>Made by InviteNow</h5>
    </div>
  </div>
</div>
<?php
$template=$invite->getTemplate();
if($template!=0) {
  $link='assets/images/coverPhotos/' . $template . '.jpg';
  echo "<script>
          document.getElementsByClassName('inviteRead')[0].setAttribute('id', 'template');
          document.getElementsByClassName('inviteRead')[0].style.backgroundImage='url(". $link.")';
        </script>";
}

?>
<script src="assets/js/speech.js"></script>
<script>


var pointA = 1;
var pointB = 1;
var totalVotes = pointA + pointB;


function addleft(){
  makeAjaxRequestVeg(0);
}
function addright(){
  makeAjaxRequestVeg(1);
}

function updatePoints(){
    var percentA = (pointA / totalVotes) * 100;
    var percentB = (pointB / totalVotes) * 100;
    var size = percentA + "% " + percentB + "%";

    document.getElementById("size-one").innerHTML = Math.round(percentA) + '%';
    document.getElementById("size-two").innerHTML = Math.round(percentB) + '%';
    document.getElementById("voting-box").style.gridTemplateColumns=  percentA + "% " + percentB + "%";

    document.getElementById("total-votes").innerHTML = "Total Votes Casted: " + totalVotes;
    document.getElementById("total-left").innerHTML = "Option A: " + pointA;
    document.getElementById("total-right").innerHTML = "Option B: " + pointB;
}

function setup() {
  var id=<?php echo $_GET["id"]; ?>;
  var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/getVeg.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+id);
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var n=JSON.parse(this.responseText);
        console.log(n);
        pointA = parseInt(n[0])+1;
        pointB = parseInt(n[1])+1;
        totalVotes = pointA + pointB;
        document.getElementById("option-one").innerHTML = "Veg";
        document.getElementById("option-two").innerHTML = "Non veg";
        updatePoints();
    }
    };
    
}

function makeAjaxRequestVeg(num) {
    var id=<?php echo $_GET["id"]; ?>;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/updateVeg.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+id+"&num="+num);
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var n=this.responseText;
        if(n==0) {
          if(num==0) {
            pointA += 1;
            totalVotes += 1;
            updatePoints();
          }
          else if(num==1) {
            pointB += 1;
            totalVotes += 1;
            updatePoints();
          }
        }
    }
    };
}
var pointC = 1;
var pointD = 1;
var totalVotes2 = pointC + pointD;


function addleft2(){
  makeAjaxRequestSouth(0);
}
function addright2(){
  makeAjaxRequestSouth(1);
}

function updatePoints2(){
    var percentC = (pointC / totalVotes2) * 100;
    var percentD = (pointD / totalVotes2) * 100;
    console.log(percentC, percentD);
    var size = percentC + "% " + percentD + "%";

    document.getElementById("size-one2").innerHTML = Math.round(percentC) + '%';
    document.getElementById("size-two2").innerHTML = Math.round(percentD) + '%';
    document.getElementById("voting-box2").style.gridTemplateColumns=  percentC + "% " + percentD + "%";

    document.getElementById("total-votes2").innerHTML = "Total Votes Casted: " + totalVotes2;
    document.getElementById("total-left2").innerHTML = "Option C: " + pointC;
    document.getElementById("total-right2").innerHTML = "Option D: " + pointD;
}

function setup2() {
  var id=<?php echo $_GET["id"]; ?>;
  var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/getSouth.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+id);
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var n=JSON.parse(this.responseText);
        console.log(n);
        pointC = parseInt(n[0])+1;
        pointD = parseInt(n[1])+1;
        console.log(pointC, pointD);
        totalVotes2 = pointC + pointD;
        document.getElementById("option-one2").innerHTML = "South Indian";
        document.getElementById("option-two2").innerHTML = "North Indian";
        updatePoints2();
    }
    };
    
}

function makeAjaxRequestSouth(num) {
    var id=<?php echo $_GET["id"]; ?>;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/updateSouth.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+id+"&num="+num);
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var n=this.responseText;
        if(n==0) {
          if(num==0) {
            pointC += 1;
            totalVotes2 += 1;
            updatePoints2();
          }
          else if(num==1) {
            pointD += 1;
            totalVotes2 += 1;
            updatePoints2();
          }
        }
    }
    };
}

function updateAttendance() {
  var id=<?php echo $_GET["id"]; ?>;
  num=document.getElementById("people").value;
  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "ajax/updateAttendance.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("id="+id+"&num="+num);
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementsByClassName("close1")[1].click();
    }
  };
}
</script>
<?php require_once("includes/footer.php"); ?>