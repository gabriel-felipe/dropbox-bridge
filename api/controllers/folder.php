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
        $listFolderContents = $dropbox->listFolder("/");
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
}
?>
