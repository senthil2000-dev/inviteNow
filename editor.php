<?php
require_once("includes/header.php");
if($userLoggedInObj->getUsername()=="") {
  echo "You must be logged in to upload an invitation";
  exit();
}
  
?>
<!DOCTYPE html>
<html lang="en">
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
<body style="text-align: center; background:darkturquoise;" onload="enableEditMode();">
<div style="padding: 1vw;margin: auto; background-color: darkturquoise;">
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
<label for='file'><i style="background: white;" class="fa fa-2x fa-file-image-o"></i></label>
   <input type="file" name="file" id="file"/>
<!-- <button onclick="execCmd('insertImage', prompt('Enter the image URL', 'https://'));"></button> -->
<button onclick="execCmd('selectAll');">Select All</button>
<?php
$query=$con->prepare("SELECT * FROM categories");
$query->execute();
$html="<select class='themeSet' onchange='setTheme(this)' name='themeInput'><option value='0'>None</option>";

while($row=$query->fetch(PDO::FETCH_ASSOC)){
    $id=$row["id"];
    $name=$row["name"];
    $html.="<option value='$id'>$name</option>";
}

$html.="</select>";

echo $html; ?>
    </div>
    <iframe name="richTextField" width="1000px"; height="1200px;" style="background-color:white"></iframe>
    <form action="upload.php" method='POST' id='draftPublish'>
      <input type="text" name='content' id='draftHtml' hidden>
      <input type="text" name='theme' id='themeNo' hidden>
      <button class="btn btn-primary" onclick='submitForm()'>DRAFT AND PUBLISH</button>
    </form>
    </div>
    <script type="text/javascript">
    document.getElementById("mainContentContainer").style.background="darkturquoise";
    document.getElementById("pageContainer").style.background="darkturquoise";
    document.getElementById("mainContentContainer").style.background="darkturquoise";
    var showingSourceCode=false;
    var isInEditMode=true;
    function enableEditMode() {
        richTextField.document.designMode='On';
        richTextField.focus();
        var cssLink = document.createElement("link");
        cssLink.href = "assets/css/style.css"; 
        cssLink.rel = "stylesheet"; 
        cssLink.type = "text/css"; 
        richTextField.document.head.appendChild(cssLink);
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
    
    function setTheme(menu) {
      var str = menu.options[menu.selectedIndex].value;
      console.log(str);
      var link='assets/images/coverPhotos/'+ str + '.jpg';
      richTextField.document.body.style.background="url(" +link+ ") no-repeat center";
      richTextField.document.body.style.backgroundSize="1000px 1200px";
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
  function escapeHtml(unsafe) {
        return unsafe
            
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
  document.getElementById('dim1').remove();
  document.getElementById('dim2').remove();
  document.getElementById('label1').remove();
  document.getElementById('label2').remove();
}
function setWidth(val) {
  var selElement = richTextField.document.querySelector('.selected');
  selElement.style.width=val;
  document.querySelector('#dim2').value=selElement.clientHeight;
}
const replaceOnDocument = (pattern, string, {target = richTextField.document.body} = {}) => {
  [target,
    ...target.querySelectorAll("*:not(script):not(noscript):not(style)")
  ].forEach(({childNodes: [...nodes]}) => nodes
    .filter(({nodeType}) => nodeType === document.TEXT_NODE)
    .forEach((textNode) => textNode.textContent = textNode.textContent.replace(pattern, string)));
};


function submitForm() {
  event.preventDefault();
  replaceOnDocument(/"/g, "&quot;");
  replaceOnDocument(/'/g, "&#039;");
  replaceOnDocument(/>/g, "&gt;");
  replaceOnDocument(/</g, "&lt;");
  var menu=document.getElementsByClassName("themeSet")[0];
  document.getElementById('themeNo').value=menu.options[menu.selectedIndex].value;
  document.getElementById('draftHtml').value=richTextField.document.getElementsByTagName('body')[0].innerHTML;
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
</body>
</html>