<?php
require_once("includes/config.php");
header('Content-type: application/json');

require_once('Google-calendar-api.php');

try {
	// Get event details
	$event = json_decode($_POST['event_details'], true);
	$capi = new GoogleCalendarApi();

	// Get user calendar timezone
	$user_timezone = $capi->GetUserCalendarTimezone($_SESSION['access_token']);

	// Create event on primary calendar
	$event_id = $capi->CreateCalendarEvent('primary', $event['title'], $event['event_time'], $user_timezone, $_SESSION['access_token']);
	$added=1;
	$inviteId=$_POST["id"];
	$query=$con->prepare("UPDATE accepted SET added=:added WHERE inviteId=:inviteId");
	$query->bindParam(":added", $added);
	$query->bindParam(":inviteId", $inviteId);
	$query->execute();
	echo json_encode([ 'event_id' => $event_id ]);
}
catch(Exception $e) {
	header('Bad Request', true, 400);
    echo json_encode(array( 'error' => 1, 'message' => $e->getMessage() ));
}

?>