# Cam Viewer
Intuitively view groups of images uploaded from an Internet Surveillance/Security Camera.

## Author
[Steve Veerman](http://steve.veerman.ca/)

## About
Cam Viewer will group images uploaded from an Internet camera by time (default group is 30 seconds) and display them in reverse chronological order (newest first).

## Compatibility
Cam Viewer should work with any Internet Surveillance/Security Camera capable of uploading images via ftp to a web server.

I have tested the following devices successfully:
* Trendnet TV-IP551WI
* Trendnet TV-IP572WI
* Trendnet TV-IP751WC
* Trendnet TV-IP862IC
* D-Link DCS-825L

## Requirements
* PHP
* Internet enabled Surveillance/Security Camera with ftp upload functionality.

## Setup and Usage
* Place index.php in the web root directory
* Create a new directory in the web root directory and name choose a name for your camera (eg. http://domain.com/camera_one/)
* Configure your camera to upload images via ftp to this new directory (refer to the screenshots in setup for examples)
* Visit index.php in a web browser (eg. http://domain.com/index.php)
* Login with optional password
* Click on camera link
* Click on an image to toggle group zoom in/out
* Scroll to bottom of the page and click "More Load" for additional images

## Optional Setup
### Schedule the deletion of images after 60 days
You may wish to delete images uploaded on a schedule to minimize storage.
* Copy the cronjobs file to server
* Modify /home/www in cronjobs to be your web root path
* On the command line, add this job to cron: crontab cronjobs
