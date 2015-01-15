# Cam Viewer
View groups of images uploaded from an Internet Surveillance/Security Camera

## Author
[Steve Veerman](http://steve.veerman.ca/)

## About
Cam Viewer will group images uploaded from an Internet camera by time (default group is 30 seconds) and display them in reverse chronological order (newest first). Tested on Trendnet TV-IP551WI and Trendnet TV-IP572WI, but it should work with any Internet camera that has built-in ftp upload functionality.

## Requirements
* PHP
* Internet enabled camera with ftp upload functionality

## Setup and Usage
* Create a new directory in the web root and name choose a name for your camera (eg. http://domain.com/camera_one/)
* Configure your camera to upload images via ftp to this new directory (refer to the screenshots in setup for examples) 
* Place index.php in the new directory
* Visit your new directory in a web browser (eg. http://domain.com/camera_one/)
* Click on an image to toggle group zoom in/out
