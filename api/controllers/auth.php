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
      if ($dropbox->getAccessToken()) {
        try {

           $account = $dropbox->getCurrentAccount();
           $account = $account->getData();
           $user['name'] = $account['name']['display_name'];
           $authenticated = true;
        } catch (\Exception $e) {
           // Do nothing token is expired and user not authenticated
        }
      }


      return json_encode(["user"=>$user,"authenticated"=>$authenticated,"folder"=>$config->folder]);
  }
}
