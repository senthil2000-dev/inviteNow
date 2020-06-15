var siblings = function (elem, classFilter) {
	return Array.prototype.filter.call(elem.parentNode.children, function (sibling) {
        if(classFilter!=null)
		    return ((sibling !== elem) && (sibling.classList.contains(classFilter)));
        else
            return (sibling !== elem);
    });
};
function postReply(button, postedBy, inviteId, replyTo, containerClass) {
    console.log(replyTo);
    var textarea=siblings(button, "replyBodyClass")[0];
    var replyText=textarea.value;
    textarea.value="";
    var str=document.querySelector(".replyCount").textContent;
    
    var res = str.replace(" chats", "");
    var n=Number(res)+1;
    document.querySelector(".replyCount").textContent=(n+" chats");
    if(replyText) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "ajax/postReply.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("replyText="+replyText+"&postedBy="+postedBy+"&inviteId="+inviteId+"&responseTo="+replyTo);
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            reply=this.responseText;
            console.log(reply);
            if(!replyTo) {
                document.querySelector("." + containerClass).innerHTML=reply+document.querySelector("." + containerClass).innerHTML;
            }
            else {
                console.log(containerClass,siblings(button.parentNode, containerClass)[0]);
                siblings(button.parentNode, containerClass)[0].innerHTML+=reply;
                toggleResponse(button);
            }
        }
        };
    }
    else{
        alert("You can't post an empty reply")
    }
}

function toggleResponse(button) {
    var parent=button.closest(".itemContainer");
    var replyForm=parent.querySelector(".replyForm");
    console.log(parent, replyForm);
    replyForm.classList.toggle("hidden");
}

function getResponses(replyId, button, inviteId) {
    itemContainers=siblings(button);
    var ids=new Array();
    Array.from(itemContainers).forEach(el=>ids.push(el.id));
    console.log(ids);
    var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "ajax/getResponseChats.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("replyId="+replyId+"&inviteId="+inviteId+"&ids="+ids);
        xhttp.onreadystatechange = function() {
        var reply=this.responseText;
        console.log(reply);
        if (this.readyState == 4 && this.status == 200) {
        var newElement = document.createElement("div");
        newElement.classList.add("repliesSection");
        newElement.innerHTML+=reply;
        button.parentNode.replaceChild(newElement, button);
        }
    };
}