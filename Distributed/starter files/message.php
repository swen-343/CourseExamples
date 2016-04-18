<?php

// Author: Wayne Starr
// Description: Create a RESTful API that accepts a message and passes it on.

function send_message($message)
{
  // Set the next user and node to send to
  $next_user = 'wes7817'; // CHANGE THIS USER
  $next_node = 'http://www.se.rit.edu/~'.$next_user.'/activities/distributed/end.php'; //CHANGE THIS NODE

  // Modify the received message as we see fit.
  $message = $message.'abc'; //ADD YOUR NAME HERE
  
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
  
  // Set the headers and send the request
  $context  = stream_context_create($options);
  $response = file_get_contents($next_node, false, $context);
  
  return $response;
}

// Create a default error string
$value = 'An error has occurred';

// Determine whether the argument is set
if (!empty($_POST['message'])) {
  $value = send_message($_POST['message']);
} else {
  $value = 'Missing argument';
}

// Return result back to the previous node
exit($value);

?>