<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",true);
require_once("../vendor/autoload.php");
require_once("autoload.php");

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use EntermotionTrial\Helpers\Config;
use EntermotionTrial\Helpers\Registry;
$config = new Config("config.json");
Registry::set("config",$config);


$token = $config->token;
$clientId = $config->clientId;
$clientSecret = $config->clientSecret;

$app = new DropboxApp($clientId, $clientSecret, $token);
$dropbox = new Dropbox($app);
Registry::set("dropbox",$dropbox);


$router = new EntermotionTrial\Helpers\Router("routes.json");
$action = $router->getAction($_GET['url']);
if (!$action) {
    $action = new EntermotionTrial\Helpers\Action("error","notFound");
}
echo $action->exec();