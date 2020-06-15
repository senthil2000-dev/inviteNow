var siblings = function (elem, classFilter) {
	return Array.prototype.filter.call(elem.parentNode.children, function (sibling) {
		return ((sibling !== elem) && (sibling.classList.contains(classFilter)));
	});
};

function acceptInvite(button, inviteId) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/acceptInvite.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("inviteId="+inviteId);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data=this.responseText;
            var acceptButton = button;
            var rejectButton = siblings(button, "rejectButton")[0];

            acceptButton.classList.add("active");
            rejectButton.classList.remove("active");
            console.log(data);
            var result=JSON.parse(data);
            updateAcceptedValue(acceptButton.querySelector(".text"), result.accepted);
            updateAcceptedValue(rejectButton.querySelector(".text"), result.rejected);

            if(result.accepted < 0) {
                acceptButton.classList.remove("active");
                acceptButton.querySelector("img").setAttribute("src", "assets/images/icons/thumb-up.png");
            }
            else {
                acceptButton.querySelector("img").setAttribute("src", "assets/images/icons/thumb-up-active.png");
            }

            rejectButton.querySelector("img").setAttribute("src", "assets/images/icons/thumb-down.png");
        }
    };
}

function rejectInvite(button, inviteId) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/rejectInvite.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("inviteId="+inviteId);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data=this.responseText;
            var rejectButton = button;
            var acceptButton = siblings(button, "acceptButton")[0];

            rejectButton.classList.add("active");
            acceptButton.classList.remove("active");
            console.log(data);
            var result=JSON.parse(data);
            
            updateAcceptedValue(acceptButton.querySelector(".text"), result.accepted);
            updateAcceptedValue(rejectButton.querySelector(".text"), result.rejected);

            if(result.rejected < 0) {
                rejectButton.classList.remove("active");
                rejectButton.querySelector("img").setAttribute("src", "assets/images/icons/thumb-down.png");
            }
            else {
                rejectButton.querySelector("img").setAttribute("src", "assets/images/icons/thumb-down-active.png");

            }

            acceptButton.querySelector("img").setAttribute("src", "assets/images/icons/thumb-up.png");
        }
    };
}

function updateAcceptedValue(element, num) {
    var alphatext=element.textContent.replace(/[0-9]/g, '');
    var numText=element.textContent.replace(alphatext, '');
    var acceptedCountVal = numText || 0;
    if(parseInt(num)!=0) {
        if(alphatext=="DECLINE ")
            alphatext="REJECTED ";
        else if(alphatext=="REJECTED ")
            alphatext="DECLINE ";
        else if(alphatext=="ACCEPT ") {
            alphatext="ACCEPTED ";
                var modal = document.getElementById("myModal2");
                var span = document.getElementsByClassName("close1")[1];
                console.log(btn);
                modal.style.display = "block";
                setup();
                setup2();
                span.onclick = function() {
                  modal.style.display = "none";
                }
        }   
        else if(alphatext=="ACCEPTED ")
            alphatext="ACCEPT ";
    }
    numText=parseInt(acceptedCountVal)+parseInt(num);
    console.log(alphatext,numText);
    element.textContent=alphatext+numText;
}