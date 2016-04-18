<?php

// Author: Wayne Starr
// Description: Create a RESTful API that returns the sent message

// Create a default error string
$value = array('status' => false, 'msg' => 'An error has occurred', 'count' => 0);

// Determine whether the argument is set
if (!empty($_POST["message"])) {
  $value = array('status' => true, 'msg' => $_POST["message"], 'count' => 0);
} else {
  $value = array('status' => false, 'msg' => 'Missing argument', 'count' => 0);
}

// Return result back to the previous node
exit(json_encode($value));

?>