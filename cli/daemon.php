<?php

require_once("../vendor/autoload.php");
require_once("../api/autoload.php");

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use EntermotionTrial\Helpers\Config;

$config = new Config("../api/config.json");
$folder = $config->folder;
$jsonFilesPath = "../synced/files.json";
$token = $config->token;
$clientId = $config->clientId;
$clientSecret = $config->clientSecret;

$app = new DropboxApp($clientId, $clientSecret, $token);
$dropbox = new Dropbox($app);
$downloadPath = realpath(__DIR__."/../synced")."/files";
$metaFiles = realpath(__DIR__."/../synced")."/meta/*.json";

do{
    $config->load("../api/config.json");
    $newToken = $config->token;
    if ($newToken) {
        /* If token changed, reconnects to dropbox */
        if ($newToken !== $token) {
          $app = new DropboxApp($clientId, $clientSecret, $token);
          $dropbox = new Dropbox($app);
        }
        $newFolder = $config->folder;
        if ($newFolder) {
            $currentFiles = [];
            if ($newFolder !== $folder) {
                unlink($jsonFilesPath);
                exec("rm -rf ".$downloadPath);
                exec("rm -rf ".$metaFiles);
                $folder = $newFolder;
            }

            if (file_exists($jsonFilesPath)) {
                $currentFiles = json_decode(file_get_contents($jsonFilesPath),true);
            }
            try {
              $listFolderContents = $dropbox->listFolder($folder);
              $items = $listFolderContents->getItems();

              $items->all();

              foreach($items as $item){
                  $type = $item->getDataProperty('.tag');
                  if ($type === "file") {
                      $id = $item->getDataProperty("id");
                      $path = $item->getDataProperty("path_display");
                      if (!isset($currentFiles[$id])) {
                          $currentFiles[$id] = $path;
                          exec("php download.php id=\"".$id."\" path=\"".$path."\" > /dev/null 2>&1 &");
                      }
                  }
              }
              $jsonFiles = fopen($jsonFilesPath,"w+");
              fwrite($jsonFiles,json_encode($currentFiles,JSON_UNESCAPED_SLASHES));
              fclose($jsonFiles);
          } catch (\Exception $e)
          {
              /* If failed, could be that the user is not logged in, so it tries to reconnect to dropbox */
              $token = $config->token;
              $clientId = $config->clientId;
              $clientSecret = $config->clientSecret;
              $app = new DropboxApp($clientId, $clientSecret, $token);
              $dropbox = new Dropbox($app);
          }
      }
  }

  sleep(5);
}while(TRUE);
