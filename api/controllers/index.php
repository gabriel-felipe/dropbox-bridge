<?php
namespace DropboxBridge\Controllers;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

class Index
{
  public function index()
  {
      require_once 'vendor/autoload.php';

      //Configure Dropbox Application
      $app = new DropboxApp("to8ow50np23bfvw", "klza85ycpoc4ee7");

      //Configure Dropbox service
      $dropbox = new Dropbox($app);

      //DropboxAuthHelper
      $authHelper = $dropbox->getAuthHelper();

      //Callback URL
      $callbackUrl = "http://localhost/dropbox-integration/api/login-callback";
      return $callbackUrl;
  }
}
