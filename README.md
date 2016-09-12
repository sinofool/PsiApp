# PsiApp
This is a handy project to install development builds to testing devices.

I have a few projects supporting different platforms, they all have CI configured but I cannot find a convience tool show all recent builds together.

The existing testing platforms all support iOS and Android but mostly not macOS and UWP, event Windows.

So here is this project.

![psiappscreen](https://cloud.githubusercontent.com/assets/380248/18430046/5eec29e4-7909-11e6-9c57-ee7ba371aec7.png)

# Installation
This project requires PHP only. No database.

Steps to run:

* Upload the "src" directory to your web server, rename it to whatever you like.
* Create a directory named "data".
* (optional) Setup authentication to the "src" directory if you needed.
* (optional) Setup proper file system permission for the "data" directory. 
  (iOS ipa is a zip file and there can be a cache. So it will need permission to create direcotry and files)
  This is optional, write access to web directory is dangerous. If you prefer no cache, it will use more memory to unzip the ipa file every time.
  
# Usage
Upload packages for different platforms to following locations (APP_NAME is a variable):
* iOS: data/APP_NAME/iOS/*.ipa
* Android: data/APP_NAME/Android/*.apk
* UWP: data/APP_NAME/UWP/*.appx
* macOS: data/APP_NAME/macOS/*.app.zip
* Windows: data/APP_NAME/Windows/*.application

