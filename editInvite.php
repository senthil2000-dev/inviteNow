<?php
require_once("includes/header.php");
require_once("includes/classes/InviteRead.php");
require_once("includes/classes/InviteDetailsFormProvider.php");
require_once("includes/classes/InviteUploadData.php");

if(!User::isLoggedIn()) {
    header("Location: signIn.php");
}
if(!isset($_GET["inviteId"])) {
    echo "No invite selected";
    exit();
}

$invite=new Invite($con, $_GET["inviteId"], $userLoggedInObj);
if($invite->getUploadedBy()!=$userLoggedInObj->getUsername()) {
    echo "Not your invite";
    exit();
}

$detailsMessage="";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inviteData= new InviteUploadData(
        null, 
        $_POST["inviteText"],
        $_POST["titleInput"],
        $_POST["descriptionInput"],
        null,
        null,
        null,
        $_POST["eventDateInput"],
        $_POST["deadlineInput"],
        $userLoggedInObj->getUsername()
    );

    if($inviteData->updateDetails($con, $invite->getId())) {
        $detailsMessage="<div class='alert alert-success'>
                            <strong>SUCCESS!</strong> Details updated successfully!
                        </div>";
        $invite=new Invite($con, $_GET["inviteId"], $userLoggedInObj);
        $action="edited details of the invitation";
        $inviteId=$_GET["inviteId"];
        $uploader=$invite->getUploadedBy();
        $members=$invite->getMembers();
        for($k=0;$k<sizeof($members);$k++) {
          $username=$members[$k];
          $query=$con->prepare("INSERT INTO notifications(postedBy, invite_replyId, action, friends) VALUES(:user, :invite_replyId, :action, :sentTo)");
          $query->bindParam(":user", $uploader);
          $query->bindParam(":invite_replyId", $inviteId);
          $query->bindParam(":action", $action);
          $query->bindParam(":sentTo", $username);
          $query->execute();
        }
    }
    else {
        $detailsMessage="<div class='alert alert-danger'>
                            <strong>ERROR!</strong> Something went wrong
                        </div>";
    }
}
?>

<div class="editInviteContainer column">

    <div class="message">
        <?php echo $detailsMessage; ?>
    </div>

    <div class="topSection">
        <?php
            $id=$_GET["inviteId"];
            $query=$con->prepare("SELECT content FROM invites WHERE id=:id");
            $query->bindParam(":id", $id);
            $query->execute();
            $content=$query->fetchColumn();
        ?>
        <head>
    <title>Draft</title>
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <style>
     .navigationItems {
        text-align: left;
     }
     #tools button{
        margin: 3px;
     }
  </style>
    <script src="https://kit.fontawesome.com/d1766da268.js" crossorigin="anonymous"></script>
</head>
<body onload="enableEditMode();">
<div>
    <div id='tools'>
      <button onclick="execCmd('bold');"><i class="fa fa-bold"></i></button>
<button onclick="execCmd('italic');"><i class="fa fa-italic"></i></button>
<button onclick="execCmd('underline');"><i class="fa fa-underline"></i></button>
<button onclick="execCmd('strikeThrough');"><i class="fa fa-strikethrough"></i></button>
<button onclick="execCmd('justifyLeft');"><i class="fa fa-align-left"></i></button>
<button onclick="execCmd('justifyCenter');"><i class="fa fa-align-center"></i></button>
<button onclick="execCmd('justifyRight');"><i class="fa fa-align-right"></i></button>
<button onclick="execCmd('justifyFull');"><i class="fa fa-align-justify"></i></button>
<button onclick="execCmd('cut');"><i class="fa fa-cut"></i></button>
<button onclick="execCmd('copy');"><i class="fa fa-copy"></i></button>
<button onclick="execCmd('indent');"><i class="fa fa-indent"></i></button>
<button onclick="execCmd('outdent');"><i class="fa fa-dedent"></i></button>
<button onclick="execCmd('subscript');"><i class="fa fa-subscript"></i></button>
<button onclick="execCmd('superscript');"><i class="fa fa-superscript"></i></button>
<button onclick="execCmd('undo');"><i class="fa fa-undo"></i></button>
<button onclick="execCmd('redo');"><i class="fa fa-repeat"></i></button>
<button onclick="execCmd('insertUnorderedList');"><i class="fa fa-list-ul"></i></button>
<button onclick="execCmd('insertOrderedList');"><i class="fa fa-list-ol"></i></button>
<button onclick="execCmd('insertParagraph');"><i class="fa fa-paragraph"></i></button>
<select onchange="execCmd('formatBlock', this.value);">
<option value="H1">H1</option>
<option value="H2">H2</option>
<option value="H3">H3</option>
<option value="H4">H4</option>
<option value="H5">H5</option>
<option value="H6">H6</option>
</select>
<button onclick="execCmd('insertHorizontalRule');">HR</button>
<button onclick="execCmd('createLink', prompt('Enter a URL', 'http://'));"><i class="fa fa-link"></i></button>
<button onclick="execCmd('unlink');"><i class="fa fa-unlink"></i></button>
<button onclick="toggleSource();"><i class="fa fa-code"></i></button>
<button onclick="toggleEdit();">Toggle Edit</button>
<br>
<select onchange="execCmd('fontName', this.value);">
<option value="Arial">Arial</option>
<option value="Comic Sans MS">Comic Sans MS</option>
<option value="Courier">Courier</option>
<option value="Georgia">Georgia</option>
<option value="Tahoma">Tahoma</option>
<option value="Times New Roman">Times New Roman</option>
<option value="Verdana">Verdana</option>
</select>
<select onchange="execCmd('fontSize', this.value);">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
</select>
Fore Color: <input type="color" onchange="execCmd('foreColor', this.value);">
Background: <input type="color" onchange="execCmd('hiliteColor', this.value);">
<label for='file'><i class="fa fa-2x fa-file-image-o"></i></label>
   <input type="file" name="file" id="file"/>
<button onclick="execCmd('selectAll');">Select All</button>
    </div>
    <iframe name="richTextField" width="1000px"; height="1200px;">
    </iframe>
    </div>
    <script type="text/javascript">
    var showingSourceCode=false;
    var isInEditMode=true;
    function enableEditMode() {
      str=<?php echo $invite->getTemplate(); ?>;
      if(str!=0) {
        var link='assets/images/coverPhotos/'+ str + '.jpg';
        richTextField.document.body.style.background="url(" +link+ ") no-repeat center";
        richTextField.document.body.style.backgroundSize="1000px 1200px";
      }
        richTextField.document.designMode='On';
        richTextField.focus();
        var cssLink = document.createElement("link");
        cssLink.href = "assets/css/style.css"; 
        cssLink.rel = "stylesheet"; 
        cssLink.type = "text/css"; 
        richTextField.document.head.appendChild(cssLink);
        var cont='<?php echo addslashes($content) ?>';
        richTextField.document.getElementsByTagName('body')[0].innerHTML=cont;
    }
    function execCmd(command, val=null) {
            console.log(command, val);
            richTextField.document.execCommand(command, false, val);
    }
    function toggleSource () {
				if(showingSourceCode){
					richTextField.document.getElementsByTagName('body')[0].innerHTML = richTextField.document.getElementsByTagName('body')[0].textContent;
					showingSourceCode = false;
				}else{
					richTextField.document.getElementsByTagName('body')[0].textContent = richTextField.document.getElementsByTagName('body')[0].innerHTML;
					showingSourceCode = true;
				}
			}

	function toggleEdit () {
		if(isInEditMode){
				richTextField.document.designMode = 'Off';
				isInEditMode = false;
		}else{
				richTextField.document.designMode = 'On';
				isInEditMode = true;
		}
	}
    
richTextField.document.body.addEventListener('click', (event) => {
  if (event.target.tagName === 'IMG') {
    console.log('image clicked');
    var tools=document.querySelector('#tools');
    width=event.target.width;
    height=event.target.height;
    event.target.classList.toggle('selected');
    if(event.target.classList.contains('selected')) {
        console.log(width);
        tools.innerHTML+="<label id='label1'>Width: </label><input onchange='setWidth(this.value)' id='dim1' type='number' min='1'>";
        tools.innerHTML+="<label id='label2'>Height: </label><input onchange='setHeight(this.value)' id='dim2' type='number' min='1'>";
        document.getElementById('dim1').value=width;
        document.getElementById('dim2').value=height;
    }
    else {
        toggleDimensions();
    }
  }
  else if(richTextField.document.querySelector('.selected')!=null) {
    richTextField.document.querySelector('.selected').classList.remove('selected');
    toggleDimensions();
  }
});
function toggleDimensions() {
  document.querySelector('#dim1').remove();
  document.querySelector('#dim2').remove();
  document.querySelector('#label1').remove();
  document.querySelector('#label2').remove();
}
function setWidth(val) {
  var selElement = richTextField.document.querySelector('.selected');
  selElement.style.width=val;
  document.querySelector('#dim2').value=selElement.clientHeight;
}
function submitForm() {
  event.preventDefault();
  document.getElementById('draftHtml').value=(richTextField.document.getElementsByTagName('body')[0].innerHTML);
  document.getElementById('draftPublish').submit();
}

function setHeight(val) {
  var selElement = richTextField.document.querySelector('.selected');
  selElement.style.height=val;
  document.querySelector('#dim1').value=selElement.clientWidth;
}

document.addEventListener('DOMContentLoaded', function(){ 
    var imageButton=document.querySelector("#file")
     imageButton.addEventListener('change', function(){
     var property=document.getElementById("file").files[0];
      var image_name = property.name;
      var form_data = new FormData();
      var ext = image_name.split('.').pop().toLowerCase();
      var image_size = property.size;
      if(!(['gif','png','jpg','jpeg'].includes(ext))) 
      {
      alert("Invalid Image File");
      }
      else if(image_size > 2000000)
      {
      alert("Image File Size is very big");
      }
      else
      {
      var form_data=new FormData();
      form_data.append("file", property);
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          execCmd('insertImage', this.response);
        }
      };
      xhr.open('POST', 'ajax/moveFile.php', true);
      xhr.send(form_data);
      }
    });
});
    </script>
    </div>

    <div class="bottomSection">
        <?php
        
        $formProvider= new InviteDetailsFormProvider($con, $content);
        echo $formProvider->createEditDetailsForm($invite);
        ?>
    </div>
    </body>

</div>