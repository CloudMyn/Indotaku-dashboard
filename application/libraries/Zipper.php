
<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

class Zipper
{

   private $ci;
   private $http;
   private $path;

   public function __construct()
   {
       $this->ci = &get_instance();
       $this->ci->load->model("Comic_page/Comic_model", "comic_model");
       $this->http = new Client();
   }


   /**
    * @param uri     ex: http:// or https://
    * @param config  ex: $config[name] = value
    * @return void
    *
    * ---- Configuration Name ----
    * 
    * $config['file_name']             = Required
    * $config['allowed_types']         = Required
    * $config['allowed_ext']           = Required
    * $config["is_download"]           = Opt
    * $config["path"]                  = Opt
    */
   public function zip_files($uri, $config)
   {
      $this->is_downloadable = (!isset($config["is_download"])) ? false : $config["is_download"];
      $this->path = (!isset($config["path"])) ? null : $config["path"];

      $response = $this->http->get($uri);
      $blobs_file = $response->getBody()->getContents();
      $arr_type = explode("/", $response->getHeaders()["Content-Type"][0]);
      $file_ext = end($arr_type);
      $file_type = $arr_type[0];
      if (!$this->_check_files($file_type, $file_ext, $config)) {
         return "Tipe File Yang Dimasukkan Tidak Valid!";
      } else {
         $this->ci->load->library("zip");
         $this->ci->zip->add_data($config["file_name"] . ".$file_ext", $blobs_file);
         $this->already_zip = true;
      }
   }

   /**
    * @param name ex: example.zip
    * To Download Zip
    */
   public function get_zip_file($name): String
   {
      $this->ci->load->library("zip");
      if ($this->already_zip == false) {
         // show_error("Add Zip Data First!, You Can Add The Data That You Want To Zipping Using zip_files() Method");
         return "false";
      } else {
         $this->ci->zip->compression_level = 5; // max 9
         (!$this->is_downloadable) ? $this->_save_zip_file($this->path, $name) : $this->ci->zip->download($name);
         $this->ci->zip->clear_data();
         return "true";
      }
   }

   private function _save_zip_file($path, $name)
   {
      $this->ci->load->library("zip");
      $arr = explode("-", $path);
      $path = trim($arr[0]);
      if (!file_exists($path)) {
         mkdir($path);
      }
      if (!file_exists($path . "/index.html")) {
         fopen($path . "/index.html", "w");
      }
      $this->ci->zip->archive("$path\\$name");
   }


   /**
    * @param file_types       ex: image|media|application
    * @param file_extention   ex: jpg|jpeg|png 
    * @param configuration    ex: allowed_types & extention
    * @return bool            ex: true if valid & false if invalid
    */
   private function _check_files($current_type, $current_ext, $config)
   {
      $ext_pattern = "/($current_ext)/";
      if ($current_type != $config["allowed_types"]) {
         return false;
      } else {
         return (preg_match($ext_pattern, $config["allowed_ext"]) == 1) ? true : false;
      }
   }
}
