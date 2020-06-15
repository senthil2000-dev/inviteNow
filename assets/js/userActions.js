function friend(userTo, userFrom, button) {

    if(userTo == userFrom) {
        alert("You can't make yourself a friend");
        return;
    }
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/friend.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("userTo="+userTo+"&userFrom="+userFrom);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        var count=this.responseText;
        if(count != null) {
            button.classList.toggle("friend");
            button.classList.toggle("unfriend");
            var buttonText=button.classList.contains("friend") ? "FRIEND": "UNFRIEND";
            button.textContent=buttonText;
        }
        else {
            alert ("Something went wrong");
        }
    }
    }
};