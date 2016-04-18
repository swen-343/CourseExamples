<?php

// Author: Wayne Starr
// Description: Create a RESTful API that accepts a message and logs it to a file.

function save_message($message)
{
  $file = 'log.txt';
  // Open the file to get existing content
  $log = file_get_contents($file);
  // Append a new person to the file
  $log .= $message."\r\n";
  // Write the contents back to the file
  file_put_contents($file, $log);
  
  //$log = $log.$message."\n";
  //$response = file_put_contents('http://www.se.rit.edu/~wes7817/activities/distributed/log.txt', $log);
  
  return $log;
}

// Create a default error string
$value = 'An error has occurred';

// Determine whether the argument is set
if (!empty($_POST['message'])) {
  $value = save_message($_POST['message']);
} else {
  $value = 'Missing argument';
}

// Return result back to the previous node
exit($value);
?>