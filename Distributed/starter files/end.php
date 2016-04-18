<?php

// Author: Wayne Starr
// Description: Create a RESTful API that returns the sent message

// Create a default error string
$value = "An error has occurred";

// Determine whether the argument is set
if (!empty($_POST["message"])) {
  $value = $_POST["message"];
} else {
  $value = "Missing argument";
}

// Return result back to the previous node
exit($value);

?>