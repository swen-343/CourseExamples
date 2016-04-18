<?php

// Author: Wayne Starr
// Description: Create a RESTful API that accepts a message and passes it on.

function send_message($message)
{
  // Set the next user and node to send to
  $next_userA = 'wes7817'; // CHANGE THIS USER
  $next_nodeA = 'http://www.se.rit.edu/~'.$next_userA.'/activities/distributed/endA.php'; //CHANGE THIS NODE
  $next_userB = 'wes7817'; // CHANGE THIS USER
  $next_nodeB = 'http://www.se.rit.edu/~'.$next_userB.'/activities/distributed/endB.php'; //CHANGE THIS NODE

  // Modify the received message as we see fit.
  $message = $message.'nodeA'; //ADD YOUR NAME HERE
  
  // Build the data array and the POST request
  $data = array('message' => $message);
  
  // Create the POST headers for the request
  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
  );
  
  // Set the headers and send the request to the next node
  $context  = stream_context_create($options);
  $response = file_get_contents($next_nodeA, false, $context);
  
  // START CHANGES
  // Build the data array and the POST request
  $data = array('message' => $message." sent to ".$next_nodeA);
  
  // Create the POST headers for the request
  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
  );
  
  // Set the headers and send the request to the logger
  $context  = stream_context_create($options);
  file_get_contents('http://www.se.rit.edu/~wes7817/activities/distributed/logger.php', false, $context); // CHANGE THIS URL
  
  if ($response != false) {
	$response = json_decode($response);
	$response->count = $response->count + 1;
    return $response;
  } else {
	// Build the data array and the POST request
	$data = array('message' => $message);
	
	// Create the POST headers for the request
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
  
	// Set the headers and send the request to the next node
	$context  = stream_context_create($options);
	$response = file_get_contents($next_nodeB, false, $context);
	if ($response != false) {
		$response = json_decode($response);
		$response->count = $response->count + 1;
		return $response;
	} else {
		$response = array('status' => false, 'msg' => 'Failed at node: '.$next_nodeB, 'count' => 0);
		return $response;
	}
  }
  // END CHANGES
}

// START CHANGES
// Create a default error string
$value = array('status' => false, 'msg' => 'An error has occurred', 'count' => 0);

// Determine whether the argument is set
if (!empty($_POST['message'])) {
  $value = send_message($_POST['message']);
} else {
  $value = array('status' => false, 'msg' => 'Missing argument', 'count' => 0);
}

// Return result back to the previous node
exit(json_encode($value));
// END CHANGES
?>