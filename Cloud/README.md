Cloud Activity Instructions
===========================

Getting Started
---------------

This activity will teach you the basics of working with computers in the cloud, as well as introduce you to working with docker containers (a common way to deploy and serve services in the cloud).

To start, login to [here](https://academiccloud.main.ad.rit.edu/vcac/) using your RIT username and password with MAIN\ appended to the start of your username (e.g. MAIN\wes7817).  This will give you access to the RIT Acedemic Cloud,a cloud built on top of VMWare's vRealize 6 that is hosted here at RIT (more information for the curious can be found [here](https://wiki.rit.edu/display/AcademicCloud/Academic+Cloud)).

Once you login, you will be brought to a dashboard where you can request and manage resources that you have access to.  To get yourself acquainted, first click on "Items" in the top bar.  This will bring you to a page where you can view all of the items that are provisioned to you.  Since this is (probably) your first time logging in, you should see a blank list.  So let's fix that, click on "Catalog" next to "Items" to view all of the kinds of servers you can add.

This will bring you to a dashboard of items you can add containing everything from Arch Linux to Windows XP.  What you are interested in for this activity is "Ubuntu Server 14.04 LTS."  Once you find this listing, select it, and you will be brought to a window where you can request this resource.  You can look over the details if you want, and when you are done, select the "Rquest" button at the bottom of the page.

Next, you will be brought to a screen where you can configure your request.  For now, just modify the reason for the request to read "Prof Krutz Cloud Docker Activity" and keep the remaining values defaulted.  Click "Submit" and your server will be provisioned.  If you select the "Requests" tab now, you should see an entry that says "In Progress."  You will now need to wait for this to complete.

Connecting to the Server
------------------------

Once the server is provisioned, you can now connect to the server, but because the servers in this pool are behind a private firewalled NAT that requires a VPN to access (one that can't be installed on SE machines) you will need to access the server within the browser.

To do this, go back to the "Items" tab, and select your new server from the list.  From here you will see many options that allow you to control the server, but the one you are interested in is "Connect to a Remote Console" in the pane to the right.  This will bring up a new window (popups may need to be allowed) that will show you the output from this new machine (ignore any errors, and if the server isn't booted just wait.  You may also need to try different browsers if that didn't work).

Once you are at the login prompt, you can login to the "student" user using the password "student".  You are now ready to install and run docker.

Installing Docker
-----------------

Docker is a containerization platform based on linux containers that allows multiple applications to be easily managed and deployed to servers even if they would normally conflict or have issues.  To get started with it, go [here](https://docs.docker.com/linux/) and follow the tutorial from "Install Docker" through "Build your own image".

*Note: If you get an error stating that the docker daemon is not running, you must add your student user to the docker group using the following command: "sudo usermod -aG docker student" then logout using the command "logout" and login using the same credentials as before.

Activity End
------------

Once you have completed those steps of the tutorial, you are done!  Feel free to play around more with your cloud server, whalesay, Docker, and the RIT Academic Cloud (you can create up to 20 VMs), and welcome to the world of cloud computing!