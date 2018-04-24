<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",true);

use DropboxBridge\Helpers\Config;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

if (isset($_GET['code']) && isset($_GET['state'])) {
    require_once 'vendor/autoload.php';
    require_once 'api/autoload.php';

    $config = new Config("api/config.json");

    $app = new DropboxApp($config->clientId, $config->clientSecret);

    //Configure Dropbox service
    $dropbox = new Dropbox($app);
    $authHelper = $dropbox->getAuthHelper();
    //Bad practice! No input sanitization!
    $code = $_GET['code'];
    $state = $_GET['state'];
    $callbackUrl = "http://localhost/dropbox-integration";

    //Fetch the AccessToken
    $accessToken = $authHelper->getAccessToken($code, $state, $callbackUrl);

    $config->token = $accessToken->getToken();
    $config->save();
}
