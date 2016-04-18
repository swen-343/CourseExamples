Distributed Activity Instructions
=================================

Getting Started
---------------

Download end.php, message.php and sender.html (under starter files) to your SE lab computer from [here](https://github.com/swen-343/CourseExamples/tree/master/Distributed).  Move the files into your Z drive at the following location: Z:\public_html\activities\distributed (create the folders if need be)

Open PuTTY, and logon to nitron.se.rit.edu over port 22 using your SE username and password.  Once you have logged in, run the following commands:

* chmod 755 public_html
* cd public_html
* chmod 755 activities
* cd activities
* chmod 755 distributed
* cd distributed
* chmod 755 message.php
* chmod 755 end.php
* chmod 755 sender.html

Once you have run this, you can view the page by opening a browser, and going to: http://www.se.rit.edu/~ _your username_ /activities/distributed/sender.html.  Once you click on Submit, you should see the message you inputted with "abc" concatenated on the end.

Now you are ready to make some changes.

*Note: If you need help at any point, look at the files for the different steps that are also located in the GitHub repository*

Modifying your Node
-------------------

The way this works is that the sender.html page sends a POST request to message.php, which has a $next_node value that it passes its message off to (after modifying it of course).  Right now, this simply goes into the end.php that is hosted under http://www.se.rit.edu/~wes7817/activities/distributed/end.php.

The first change you should make is to change $next_user in message.php to your user name in order to point to your version of end.php, and re-submit the form just to make sure everything works.  Next change the way that the message is modified to add your name to the end of the message instead of "abc".

Now when you submit the form, you should see the message sent modified with your name instead.

Linking Up as a Group
---------------------

For the next section, get into groups of 5-6 people, and elect someone to be the first node in the chain, and someone else to be the end node.  Then change the $next_user variable until a chain is created from the first user to the last.  Be sure to ensure that everyone has deployed their php files in the same way (as shown in "Getting Started"), and have the first person submit their form.

You should see a message show with all of the names of the members of your group as the message is passed from computer to computer and mutated along the way.

Next introduce an error into the chain by having one person change their $next_user to something that doesn't exist.  Now when you submit the form, you should just see "false" returned.  This is quite unhelpful as it doesn't tell you where the error occured along the way.  In the next section you will add code to fix this issue.

Adding Acknowledgement, and Logging
-----------------------------------

### Acknowledgement ###

Two common ways that web servers (and developers) determine what went wrong in a complex system are acknowledgement messages, and logging.  In this section, you will add both to this application.  The first change you will make is to add acknowledgement messages to your code.

This is done by returning a JSON object from the webservice instead of a simple string.  The first step is to edit your "end.php" file to return the fields "status" (whether the service succeeded), "msg" (the message returned), and "count" (the number of nodes the message passed through).  Edit your code as follows:

#### Old end.php Code ####

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

#### New end.php Code ####

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

As you can see, this changes that string to a php object containing the defined fields, and then on exit, encodes the object as JSON.  Next, however, you need to change message.php to accept this new response and check for any failures that occurred.  Modify the following section as follows:

#### Old message.php Return Section ####

	// Set the headers and send the request
	$context  = stream_context_create($options);	
	$response = file_get_contents($next_node, false, $context);
		
	return $response;

#### New message.php Return Section ####

	// Set the headers and send the request
	$context  = stream_context_create($options);
	$response = file_get_contents($next_node, false, $context);
	
	if ($response != false) {
		$response = json_decode($response);
		$response->count = $response->count + 1;
		return $response;
	} else {
		$response = array('status' => false, 'msg' => 'Failed at node: '.$next_node, 'count' => 0);
		return $response;
	}

As you can see this decodes the JSON and checks for the response code, returning the appropriate message, but you are not done yet.  You also have to change the error messages at the bottom, and encode the data as JSON.  Modify this section as follows:

#### Old message.php Exit Section ####

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

#### New message.php Exit Section ####

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

Now you have successfully added acknowledgement messages!  Once all of your team members have made these changes, you should see a JSON object response from the web service.  This should tell you where in the chain that error you introduced is.  Next you'll add logging for even greate accountability.

### Logging ###

In order to add logging, one of your team members has to download the logger.php, and log.txt files from the step 2 files folder on GitHub into their /public_html/activities/distributed folder.  Then, they need to ssh into nitron.se.rit.edu navigate to that directory, and run the following commands:

* chmod 755 logger.php
* chmod 777 log.txt

This allows php to properly write to the log file that you can look at later.  Next have each team member add the following code after the call to the next node, but before the code is returned, modifying the url to point to the group member who downloaded the logger:

#### Logging Code for message.php  ####

	// Build the data array and the POST request
	$data = array('message' => $message." sent to ".$next_node);
	
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

This will send a log message stating that the message was sent to whatever the next node was.  If you rerun your group's chain you will see that you have messages in the log.txt which can help you find where the errors lie.  Now, fix your introduced error, and move on to the next section.

Linking Up as a Class
---------------------

As your next task you will link all of your groups into one big chain.  One group will be selected to be the first group, and one will be selected to be the end.  If you are not the end group, have your end node link up with the first node of another group.  The first group will then submit their form, and show the output of the message being mutated by all of the nodes in the chain.  Once this is working, one node will be selected to have an error introduced, and the chain will be rerun.  Once this happens it should be simple enough to debug where the issue arose, but wouldn't it be nice if the chain was able to adapt on its own?

For the next (and final) section you will be adding redundancy to the chain so that the code will continue to run as expected even if an error occurs.

Adding Redundancy
-----------------

For this step, split the chain in half as evenly as is possible (if it is not even, then one person will have to sit out), and pair up people within the chain so that person A1, and B1 each send to person A2, and B2 respectively.  Once this is established, edit your code by adding a second node, and sending to it in the event of a failure (remember to keep track of who is A, and who is B to wire it up correctly):

#### Old message.php Node Definitions ####

	// Set the next user and node to send to
	$next_user = 'wes7817'; // CHANGE THIS USER
	$next_node = 'http://www.se.rit.edu/~'.$next_user.'/activities/distributed/end.php'; //CHANGE THIS NODE
	...
	$response = file_get_contents($next_node, false, $context);
	...
	$data = array('message' => $message." sent to ".$next_node);

#### New message.php Node Definitions ####

	// Set the next user and node to send to
	$next_userA = 'wes7817'; // CHANGE THIS USER
	$next_nodeA = 'http://www.se.rit.edu/~'.$next_userA.'/activities/distributed/endA.php'; //CHANGE THIS NODE
	$next_userB = 'wes7817'; // CHANGE THIS USER
	$next_nodeB = 'http://www.se.rit.edu/~'.$next_userB.'/activities/distributed/endB.php'; //CHANGE THIS NODE
	...
	$response = file_get_contents($next_nodeA, false, $context);
	...
	$data = array('message' => $message." sent to ".$next_nodeA);

Next change the way you handle errors to send to the other node (again keeping track of who is A, and who is B).

#### Old message.php Return Code ####

	if ($response != false) {
		$response = json_decode($response);
		$response->count = $response->count + 1;
		return $response;
	} else {
		$response = array('status' => false, 'msg' => 'Failed at node: '.$next_node, 'count' => 0);
		return $response;
	}

#### New message.php Return Code ####

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

Once all of the changes are made, run it one last time, and if it worked, congratulations!

Finished
--------

At this point, feel free to play around with this, or go back to just linking with your group.  Try modifying the message in special ways, or changing sender.html to do something different.  If you need help with HTML or PHP, w3schools is always a good reference.