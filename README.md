# What I'm building:
I'm building a software that can be used as a bridge between Dropbox and other web software.
The basic use flow is:
1) User sing in with his Dropbox account.
2) User select which folder to watch.
3) Software lists files on the folder and starts watch folder for changes.
4) At the files list user can download files
5) User defines a callback url for the new action.
6) Whenever a new file is added to the synced folder the software makes a post request to the url saved, with information about the new file, and it's download link, so the other software can interact with this file.
7) If I have enough time, I would like to build filters like: only watch the files with this or that extension, and so on.

#### 0:30:
  Reading docs and thinking about what to build.

#### 1:30:
  Finished the "framework" aka project structure.
  Downloaded dependencies (npm and composer)
  Configured grunt
  Implemented auto-loaders and simple router (VERY BASIC, only enough to make this app).

#### 2:00:
  Created the Dropbox account and app.
  Authorization process is done

#### 4:00:
  Studied the docs of the Dropbox api and SDK.
  Started HTML and CSS
  Improved login screen
  Listed level 0 folders after login to selection. Folder selection is not yet done.
