<?php
namespace EntermotionTrial\Helpers;

class Config
{
  protected $configFile=null;
  protected $data = [];
  public function __construct($file)
  {
      if (file_exists($file)) {
          $this->configFile = $file;
          $data = json_decode(file_get_contents($file),true);
          if (is_array($data)) {
            $this->setData($data);
          } else {
            throw new \Exception("Config constructor expects a json file. \"$file\" given.", 1);
          }
      } else {
        throw new \Exception("Config constructor expects a filepath. \"$file\" given.", 1);
      }
  }

  public function save()
  {
      $fhandler = fopen($this->configFile,"w+");
      fwrite($fhandler,json_encode($this->data,JSON_PRETTY_PRINT));
      fclose($fhandler);
  }

  public function setData(array $data)
  {
      $this->data = $data;
  }

  public function __get($key)
  {
      return (isset($this->data[$key])) ? $this->data[$key] : null;
  }

  public function __set($key,$value)
  {
      $this->data[$key] = $value;
      return $this;
  }
}
