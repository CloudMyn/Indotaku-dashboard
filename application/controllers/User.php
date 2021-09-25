<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
   //Get User Data
   private $_userData;
   public function __construct()
   {
      parent::__construct();
      //-To Check User Access Level
      _checkUserAccess();
      $this->load->model("User_page/Profile_model", "profile_mod");
      $this->_userData = _getUserData();
   }
   public function index()
   {
      $data["user_data"] = $this->_userData;
      $data["title"] = "My Profile";
      _templates($data, "user_page/profile_view.php");
   }
}
