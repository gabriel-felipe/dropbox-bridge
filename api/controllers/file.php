<?php
namespace EntermotionTrial\Controllers;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use EntermotionTrial\Helpers\Registry;
class File
{
    public function status()
    {
        $config = Registry::get("config");

        $id = "";
        if (isset($_GET['id'])) {
           $id = $_GET['id'];
        }

        $result = json_encode(['status'=>"not_found"]);
        if ($id) {
            $metaFile = $config->syncDir."/meta/".$id.".json";
            if (file_exists($metaFile)) {
                $content = file_get_contents($metaFile);
                if ($content) {
                  $result = $content;
                }
            }
        }

        return $result;
    }
}
?>
