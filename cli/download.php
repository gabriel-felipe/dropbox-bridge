<?php

require_once("../vendor/autoload.php");
require_once("../api/autoload.php");

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use DropboxBridge\Helpers\Config;

$globalParams = array();
foreach ($argv as $arg) {
    $keyvalue = explode("=",$arg);
    if (count($keyvalue) === 2) {
        $key = $keyvalue[0];
        $value = $keyvalue[1];
        if ($key === "env") {
            $env = $value;
        }
        $globalParams[$key] = $value;
    }
}

$config = new Config("../api/config.json");
$jsonFilesPath = "../synced/files.json";
$token = $config->token;


$downloadPath = realpath(__DIR__."/../synced")."/files";
$previousProgress = 0;
$path = $globalParams['path'];

if ($path) {
    function progressCallback ($resource, $download_size, $downloaded_size, $upload_size, $uploaded_size)
    {
        global $previousProgress;
        global $globalParams;
        global $config;
        if ( $download_size == 0 )
            $progress = 0;
        else
            $progress = round( $downloaded_size * 100 / $download_size );

        if ($progress > $previousProgress) {
            $previousProgress = $progress;
            $meta = fopen("../synced/meta/".$globalParams['id'].".json","w");
            $status = ($progress == 100) ? "downloaded" : "downloading";
            $dados = [
                "link" => $config->urlBase."/synced/files/".$globalParams['path'],
                "progress" => $progress,
                "status" => $status,
            ];
            if ($progress == 100 and $config->newFileCallbackUrl) {
                $curlNewFile = curl_init($config->newFileCallbackUrl);
                curl_setopt($curlNewFile, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($curlNewFile, CURLOPT_POSTFIELDS, $dados);
                curl_exec($curlNewFile);
            }

            fwrite($meta,json_encode($dados));
            fclose($meta);
        }
    }

    try {
        $destination = $downloadPath.$path;
        $directory = dirname($destination);
        if (!is_dir($directory)) {
            mkdir($directory,0755,true);
        }

        $targetFile = fopen( $destination, 'w' );
        $ch = curl_init( 'https://content.dropboxapi.com/2/files/download' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_NOPROGRESS, false );
        curl_setopt($ch, CURLOPT_BUFFERSIZE,64000);
        curl_setopt( $ch, CURLOPT_PROGRESSFUNCTION, 'progressCallback' );
        curl_setopt( $ch, CURLOPT_FILE, $targetFile );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Authorization: Bearer '.$token,
          'Dropbox-API-Arg: '.json_encode(['path' => $path])
          )
        );
        curl_exec( $ch );
        fclose( $targetFile );
        // $file = $dropbox->download($path", $destination);
    } catch (\Exception $e) {
        echo $e->getMessage();
    # code...
    }
}
