<?php include("login-dropbox-callback.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="expires" content="0">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Dropbox Bridge</title>
    <link rel="stylesheet" href="dist/app.min.css">
    <link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
</head>
<body>
  <?php include_once("dist/templates.html"); ?>
  <div id="app">

    <router-view @found-user="updateUser" :user="user"></router-view>

  </div>
  <script type="text/javascript" src="dist/vendor.min.js"></script>
  <script type="text/javascript" src="dist/app.min.js"></script>
</body>
</html>
