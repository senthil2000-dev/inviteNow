document.addEventListener('DOMContentLoaded', function(){
    document.querySelector(".navShowHide").addEventListener("click",function(){
        var main=document.querySelector("#mainSectionContainer");
        var nav=document.querySelector("#sideNavContainer");
        if(main.classList.contains("leftPadding")){
            nav.style.display="none";
        }
        else{
            nav.style.display="";
        }

        main.classList.toggle("leftPadding");

    });

    document.querySelector("#mainContentContainer").addEventListener("click", function() {
        var main=document.querySelector("#mainSectionContainer");
        var nav=document.querySelector("#sideNavContainer");
        if(main.classList.contains("leftPadding")){
            nav.style.display="none";
            main.classList.toggle("leftPadding");
        }
        
    });

});


function hover(element) {
    element.setAttribute('src', 'assets/images/icons/52-512.webp');
  }
  
  function unhover(element) {
    element.setAttribute('src', 'assets/images/icons/deletefull.png');
  }

function notSignedIn() {
    alert("You must be signed in to perform this action");
}

function notSignedIn2(button) {
    if (confirm("You must be signed in to view your profile, click OK to continue to SignIn page and CANCEL if you wish to continue reading")) {
        document.querySelector(button).setAttribute("href", "signIn.php");
    } 
}

document.addEventListener('DOMContentLoaded', function(){ 
    document.querySelector("#file-upload").addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(document.querySelector("#file-upload"));
        var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".profileImage").setAttribute("src", this.responseText);
            document.querySelector(".profilePicture").setAttribute("src", this.responseText);
        }
      };
      xhr.open('POST', 'ajax/updateProfilePic.php', true);
      xhr.send(formData);
    });
});

function submitForm() {
    document.querySelector("#press").click();
}

function callf(url) {
    if(url!="")
    window.location=url;
}

function submitForm2() {
    document.getElementById('submitForm').submit();
}

function status() {
    if(document.getElementById("checking").checked) {
        $word="pause";
    }
    else {
        $word ="resume";
    }
       if (confirm("Do you want to "+$word+ " receiving invitations")) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "ajax/status.php", true);
        xhttp.send();
    }   
}

function deleteAll2() {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/deleteAll2.php", true);
    xhttp.send();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.querySelector(".left").textContent="Your inbox is empty";
        elements=document.querySelectorAll(".inviteGrid a");
        Array.from(elements).forEach(element => element.remove());
    }
};
}

 document.addEventListener('DOMContentLoaded', function(){ 
  Array.from(document.querySelectorAll('.dropbtn')).forEach(el=>el.addEventListener("click", function(event){
    event.preventDefault();
    showDropdown(event);
    }));
  });

  document.addEventListener('DOMContentLoaded', function(){ 
    Array.from(document.querySelectorAll('.removing')).forEach(el=>el.addEventListener("click", function(event){  
      event.preventDefault();
      var url = window.location.pathname;
      var filename = url.substring(url.lastIndexOf('/')+1);
        console.log(filename);
        if(filename=="editInvites.php") 
            removeInvite(event);
        else
            removeItem(event);
      }));
      Array.from(document.querySelectorAll('.editing')).forEach(el=>el.addEventListener("click", function(event){
         event.preventDefault();
         window.location.href=event.target.classList[1]; 
      }));
    });

function showDropdown(event) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            if(event.target.nextSibling.nextSibling!=openDropdown)
                openDropdown.classList.remove('show');
        }
    }
    console.log(event.target.classList[4]);
    document.getElementById(event.target.classList[4]).classList.toggle("show");
}

window.onclick = function(event) {
    console.log(event.target);
    if (event.target == document.getElementById("myModal")) {
        document.getElementById("myModal").style.display = "none";
      }
    var dropdowns = document.getElementsByClassName("dropdown-content");
    if (!event.target.matches('.dropbtn')) {
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

function removeItem(event) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/deleteReceived.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+event.target.classList[1]);
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        n=this.responseText;
        console.log(n);
        if(n==0) {
            console.log("1");
            document.querySelector(".left").textContent="Your inbox is empty";
        }
        event.target.closest(".flexing").remove();
    }
    };
}

function removeInvite(event) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/deleteInvite.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id="+event.target.classList[1]);
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        n=this.responseText;
        console.log(n);
        if(n==0) {
            console.log("1");
            document.querySelector(".left").textContent="No invites sent";
        }
        event.target.closest(".flexing").remove();
    }
    };
}

document.addEventListener('DOMContentLoaded', function(){ 
var modal = document.getElementById("myModal");

var btn = document.getElementById("btn");


var span = document.getElementsByClassName("close1")[0];
console.log(btn);

btn.onclick = function() {
  modal.style.display = "block";
}
span.onclick = function() {
  modal.style.display = "none";
}

});

function saveDetails(form) {
    event.preventDefault();
    var e = document.getElementById("privacyValue");
    var strUser = e.options[e.selectedIndex].value;
    if(strUser==0) {
        var text=document.querySelector("#invitees").innerHTML;
        if(document.querySelectorAll("#invitees li").length==0)
            alert("Please enter invited members");
        else {
            var text=text.replace(/<\/li>/g, ";");
            text=text.replace(/<li>/g, "");
            text=text.slice(0,-1);
            
        }
    }
    else {
        text="open";
    }
    document.getElementById("hiddenMem").value=text;
    form.submit();
}

function privacyChange() {
    if(document.querySelectorAll(".searchBarContainer")[1]) {
        var searchBar=document.querySelectorAll(".searchBarContainer")[1];
        searchBar.style.display=(searchBar.style.display=="none")?"flex":"none";
        document.getElementById("invitees").style.display=(searchBar.style.display=="none")?"none":"";
    }
}

function attendance() {
    event.preventDefault();
}