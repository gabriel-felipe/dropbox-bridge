<?php
namespace EntermotionTrial\Controllers;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use EntermotionTrial\Helpers\Registry;
class Folder
{
    public function list()
    {
        $dropbox = Registry::get("dropbox");

        $folder = "/";
        if (isset($_GET['folder'])) {
           $folder = $_GET['folder'];
        }

        $listFolderContents = $dropbox->listFolder($folder);
        //Fetch Items (Returns an instance of ModelCollection)
        $items = $listFolderContents->getItems();

        //All Items
        $items->all();

        $subFolders = [];
        foreach($items as $item){
            $type = $item->getDataProperty('.tag');
            if ($type === "folder") {
                $subFolders[] = [
                  "path"=>(string)$item->getDataProperty("path_display"),
                  "id"=>$item->getDataProperty("id")
                ];
            }
        }
        return json_encode(["folders"=>$subFolders],JSON_UNESCAPED_SLASHES);
    }

    public function update()
    {
      $dropbox = Registry::get("dropbox");
      $config = Registry::get("config");
      $folder = null;
      if (isset($_GET['folder'])) {
         $folder = $_GET['folder'];
      }
      $error = "";
      if ($folder) {
        try {
          /* Checking folder existence if it does not exist it will throw an exception */
          $listFolderContents = $dropbox->listFolder($folder);
          $config->folder = $folder;
          $config->save();
        } catch (\Exception $e) {
          $error = "You need to specify a folder to update the folder";
        }

      } else {
        $error = "You need to specify a folder to update the folder";
      }

      $result = [
        "status" => ($error) ? "error" : "success"
      ];
      if ($error) {
        $result['error'] = $error;
      }

      return json_encode($result,JSON_UNESCAPED_SLASHES);
    }
}
?>
