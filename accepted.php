<?php
require_once("includes/header.php");
require_once("includes/classes/AcceptedInvitesProvider.php");
$set=0;
if(!User::isLoggedIn()) {
    header("Location:signIn.php");
}
if(!isset($_SESSION['access_token'])) {
	$set=1;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


</head>
<body>
<?php
$acceptedInvitesProvider = new AcceptedInvitesProvider($con, $userLoggedInObj);
$invites=$acceptedInvitesProvider->getInvites();

$inviteGrid = new InviteGrid($con, $userLoggedInObj);
?>
<div class="largeInviteGridContainer">
<?php 
if(sizeof($invites)>0) {
    echo $inviteGrid->createLarge($invites, "Invitations that you have accepted", false);
}
else {
    echo "No invitations accepted";
}
?>

<div id="form-container">
	<input type="text" id="event-title" placeholder="Event Title" autocomplete="off" hidden />
	<input type="text" id="event-date" placeholder="Event Date" autocomplete="off" hidden />
	<button id="create-event" hidden>Create Event</button>
</div>
</div>
<script>
var id=0;
function submitDate(button, inviteId, dateOfEvent, title, description) {
    event.preventDefault();
	if(button.textContent=="ADD TO GOOGLE CALENDAR") {
		id=inviteId;
		var set=<?php echo $set; ?>;
		if(set==1) {
			alert("Please authenticate with google to add your invite to calendar");
			window.location.href='google-login.php';
		}
		document.getElementById("event-title").value="Title: "+title+", Description: "+description;
		document.getElementById("event-date").value=dateOfEvent;
		document.getElementById("create-event").click();
		button.textContent="ADDED TO GOOGLE CALENDAR";
	}
	else {
		alert("Event already added");
	}
	
}

function AdjustMinTime(ct) {
	var dtob = new Date(),
  		current_date = dtob.getDate(),
  		current_month = dtob.getMonth() + 1,
  		current_year = dtob.getFullYear();
  			
	var full_date = current_year + '-' +
					( current_month < 10 ? '0' + current_month : current_month ) + '-' + 
		  			( current_date < 10 ? '0' + current_date : current_date );

	if(ct.dateFormat('Y-m-d') == full_date)
		this.setOptions({ minTime: 0 });
	else 
		this.setOptions({ minTime: false });
}


document.querySelector("#create-event").addEventListener('click', function(e) {
	if(document.querySelector("#create-event").getAttribute('data-in-progress') == 1)
		return;

	var blank_reg_exp = /^([\s]{0,}[^\s]{1,}[\s]{0,}){1,}$/,
		error = 0,
		parameters;
	if(document.querySelector(".input-error"))
		document.querySelector(".input-error").classList.remove('input-error');

	if(!blank_reg_exp.test(document.querySelector("#event-title").value)) {
		document.querySelector("#event-title").classList.add('input-error');
		error = 1;
	}

		if(!blank_reg_exp.test(document.querySelector("#event-date").value)) {
			document.querySelector("#event-date").classList.add('input-error');
			error = 1;
		}	

	if(error == 1)
		return false;

	parameters = { 	title: document.querySelector("#event-title").value, 
					event_time: document.querySelector("#event-date").value
				};
				
	document.querySelector("#create-event").setAttribute('disabled', 'disabled');
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "ajax.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("event_details="+JSON.stringify(parameters)+"&id="+id);
    xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			response=JSON.parse(this.response);
			console.log(response);
        	document.querySelector("#create-event").removeAttribute('disabled');
        	alert('Event created with ID : ' + response.event_id);
		}
		else if(this.status!=200) {
            document.querySelector("#create-event").removeAttribute('disabled');
            alert(this.response.responseJSON.message);
		}
	};
});

</script>

</body>