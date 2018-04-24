<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",true);
require_once("../vendor/autoload.php");
require_once("autoload.php");

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use DropboxBridge\Helpers\Config;
use DropboxBridge\Helpers\Registry;
$config = new Config("config.json");
Registry::set("config",$config);

$documentRoot = str_replace("/",DIRECTORY_SEPARATOR,$_SERVER['CONTEXT_DOCUMENT_ROOT']);
$urlBase = str_replace($documentRoot, "",dirname(__FILE__)."/../");
$urlBase = "http://".$_SERVER["SERVER_NAME"].$_SERVER["CONTEXT_PREFIX"].$urlBase;
if ($config->urlBase  !== $urlBase) {
    $config->urlBase = $urlBase;
    $config->save();
}

$syncDir = realpath(__DIR__."/../synced");
$config->syncDir = $syncDir;

$token = $config->token;
$clientId = $config->clientId;
$clientSecret = $config->clientSecret;

$app = new DropboxApp($clientId, $clientSecret, $token);
$dropbox = new Dropbox($app);
Registry::set("dropbox",$dropbox);


$router = new DropboxBridge\Helpers\Router("routes.json");
$action = $router->getAction($_GET['url']);
if (!$action) {
    $action = new DropboxBridge\Helpers\Action("error","notFound");
}
echo $action->exec();
