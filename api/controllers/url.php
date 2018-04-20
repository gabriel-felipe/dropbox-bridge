<?php
namespace EntermotionTrial\Controllers;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use EntermotionTrial\Helpers\Registry;
class Url
{
    public function fileCallback()
    {
        $config = Registry::get("config");

        $url = "";
        if (isset($_GET['callbackUrl'])) {
           $url = $_GET['callbackUrl'];
        }

        $result = ['status' => "url_invalid"];
        if (filter_var($url,FILTER_VALIDATE_URL)) {
            $config->newFileCallbackUrl = $url;
            $config->save();
            $result['status'] = "success";
        }

        return json_encode($result);
    }
}
?>
