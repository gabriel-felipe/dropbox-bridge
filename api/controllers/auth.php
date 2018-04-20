<?php
namespace EntermotionTrial\Controllers;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use EntermotionTrial\Helpers\Registry;
class Auth
{


  public function loginUrl()
  {
      $dropbox = Registry::get("dropbox");
      $authenticated = false;
      if ($dropbox->getAccessToken()) {
        try {
           $dropbox->getCurrentAccount();
           $authenticated = true;
        } catch (\Exception $e) {
           // Do nothing token is expired and user not authenticated
        }
      }

      //DropboxAuthHelper
      $authHelper = $dropbox->getAuthHelper();
      //Callback URL
      $callbackUrl = "http://localhost/dropbox-integration";
      $authUrl = $authHelper->getAuthUrl($callbackUrl);
      return json_encode(["url"=>$authUrl,"authenticated"=>$authenticated]);
  }

  public function currentAccount()
  {
      $config = Registry::get("config");
      $dropbox = Registry::get("dropbox");
      $authenticated = false;
      $user = [];
      $files = [];
      $folder = $config->folder;
      if ($dropbox->getAccessToken()) {
        try {

           $account = $dropbox->getCurrentAccount();
           $account = $account->getData();
           $user['name'] = $account['name']['display_name'];
           if ($folder) {
             try {
               $listFolderContents = $dropbox->listFolder($folder);
               //Fetch Items (Returns an instance of ModelCollection)
               $items = $listFolderContents->getItems();

               //All Items
               $items->all();

               $subFolders = [];
               foreach($items as $item){
                   $type = $item->getDataProperty('.tag');
                   if ($type === "file") {

                       $files[] = [
                         "path"=>(string)$item->getDataProperty("path_display"),
                         "id"=>$item->getDataProperty("id")
                       ];
                   }
               }
             } catch (\Exception $e)
             {}
           }
           $authenticated = true;
        } catch (\Exception $e) {
           // Do nothing token is expired and user not authenticated
        }
      }


      return json_encode(["user"=>$user,"authenticated"=>$authenticated,"folder"=>$folder,"files"=>$files,"newFileCallbackUrl"=>$config->newFileCallbackUrl]);
  }
}
