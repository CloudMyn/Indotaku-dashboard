<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * -------------------------------------------------------------------------------------------------------------------------------------------
 *                                                 *CONTROLLER FOR ALL ADMIN PAGES AND LOGIC*
 * -------------------------------------------------------------------------------------------------------------------------------------------
 */
class Admin extends CI_Controller
{
   public function __construct()
   {
      parent::__construct();
      //-To Check User Access Level
      _checkUserAccess();
      $this->load->model("Admin_page/Admin_model", "admin_model");
   }

   /**
    * ------------------------------------------------------------------------
    *                Main Page Of This Controller Is Dashboard
    * ------------------------------------------------------------------------
    * Merupakan Halaman Utama Dari Comic Controller.
    */
   public function index()
   {
      $data["title"]          = "Dashboard";                      // - judul dari halaman
      $data["user_data"]      = _getUserData();                   // - dapatkan data dari user sekarang
      _templates($data, "admin_page/admin_view.php");
   }

   /**
    * ------------------------------------------------------------------------
    *                               User Role View
    * ------------------------------------------------------------------------
    */
   public function role()
   {

      $data["title"]       = "Role";                              // - judul dari halaman
      $data["user_data"]   = _getUserData();                      // - dapatkan data dari user sekarang
      $data["roles"]       = $this->admin_model->get_all_roles();    // - dapatkan semua level akses pengguna
      _templates($data, "admin_page/role_page/role_view.php");
   }

   public function roleMenu()
   {
      $data["title"]       = "Add New Role";                              // - judul dari halaman
      $data["user_data"]   = _getUserData();                      // - dapatkan data dari user sekarang
      _templates($data, "admin_page/role_page/role_menu_view.php");
   }

   public function config($id = NULL)
   {
      // var_dump($this->input->post());
      $data["title"]             =  "Config";                            // - judul dari halaman
      $data["user_data"]         =  _getUserData();                      // - dapatkan data dari user sekarang
      $data["targets"]           =  $this->admin_model->get_scrape_target();
      $data["active_target"]     =  $this->admin_model->get_active_server();

      if (!isset($id)) {
         _templates($data, "admin_page/config_view.php");
      } else {
         $res = $this->admin_model->set_active_server($id);
         if ($res) return "Success";
         else return "Failed";
      }
   }
}
