# What is it?:
It is a software that can be used as a bridge between Dropbox and other web software.
The basic use flow is:
1) User sing in with his Dropbox account.
2) User select which folder to watch.
3) Software lists files on the folder and starts watching the folder for changes.
4) At the files list user can download the files
5) User defines a callback url for the new action.
6) Whenever a new file is added to the synced folder the software makes a post request to the url saved, with information about the new file, and it's download link, so the other software can interact with this file.

## How does it work?
The frontend was made using Vue and Bootstrap and for the backend I wrote a rest api and some cli tools with PHP, every line of php was written specially to this project, no framework or previously made code was used. The trickiest part was the real time updating file list and downloading the files to the server asynchronously with a progress bar at the frontend.

The cli/daemon.php consists of an infinite loop that keeps running on a terminal and constantly checks the selected folder files and writes the file list result to synced/files.json. Every time it finds a new file, it calls asynchronously the cli/download.php which starts a new process to download the new file to the server. For this download, I didn't used the SDK, because I wanted to show a progress bar, and the SDK does not provide me with the tools to do it, so I made a manual curl request, using the CURLOPT_PROGRESSFUNCTION option. With this I could write the status of the downloading file to a synced/meta/{id}.json, this json contains the download link to the file, it's status and it's download progress. At the frontend I made recursive calls with some timeout that constantly checks this meta json file until the file is fully downloaded.

## How to test it locally?
This should be cloned at http://localhost/dropbox-integration if not, the $callbackUrl at api/controllers/auth.php and the apiEndPoint at app/app.js must be changed.

After cloning, you must install all the dependencies with composer install and npm install.

The clientSecret and clientId should be configured at api/config.json, the system fills the rest of the configurations automatically when needed.

There is no database, so the software uses the api/config.json file to store the user's variable.

If you need to edit something on the frontend layer, which is all inside the /app folder, you must run "grunt watch", so it watches the files and compiles them automatically to the distribution form which is referenced in index.php.

Before starting to test, you need to run the cli/daemon.php in a terminal, so it can watch the selected folder.

## Dev Timeline

#### 0:30:
  - Reading docs and thinking about what to build.

#### 1:30:
  - Finished the "framework" aka project structure.
  - Downloaded dependencies (npm and composer)
  - Configured grunt
  - Implemented auto-loaders and simple router (VERY BASIC, only enough to make this app).

#### 2:00:
  - Created the Dropbox account and app.
  - Authorization process is done

#### 4:00:
  - Studied the docs of the Dropbox api and SDK.
  - Started HTML and CSS
  - Improved login screen
  - Listed level 0 folders after login to selection. Folder selection is not yet done.

#### 5:00:
  - Implemented folder selection

#### 6:00:
  - Implemented file list
  - Created the daemon to watch files

#### 7:00:
  - Made the daemon download the files
  - Implemented real time updating at the file list

#### 8:00:
  - Implemented the configuration to the callback url when a new file is downloaded.
  - Improved design a little

## To do list
  - Improve exception handling and edge cases
  - Improve folder structure: helpers, autoload and config.json are shared resources between api and cli, and therefore should be outside of api folder.
  - Improve design
  - The daemon, is currently a php file that needs to be executed manually on the terminal, should be created a service for more control about the daemon process.
  - Remove the state and code params of the url after the login, so it does not fires an error if the user refreshes the page.
  - Implement a database so multiple users can use the same installation.
