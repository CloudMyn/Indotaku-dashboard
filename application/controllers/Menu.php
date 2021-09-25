<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Menu extends CI_Controller
{
   private $_userData;
   public function __construct()
   {
      parent::__construct();
      //-To Check User Access Level
      _checkUserAccess();
      $this->load->model("Menu_page/Menu_model", "menu_modl");
      $this->_userData = _getUserData();
      $this->load->library("form_validation");
   }


   // ------------------------------------------------------------ Menu ------------------------------------------------------------
   public function index()
   {
      $data["menus"] = $this->menu_modl->getMenu();
      $data["user_data"] = $this->_userData;
      $data["title"] = "Menu";

      //Form-Validasi
      $this->form_validation->set_rules("menu-name", "Name", "trim|required|regex_match[/^[a-zA-Z]{3,30}$/]", [
         "regex_match" => "The Name Field Is Only Received A Valid Alphabets And The Length Is Greater Than 3 And Lower Than 30"
      ]);


      if (!$this->form_validation->run()) {
         _templates($data, "menu_page/menu_view.php");
      } else {
         if ($this->menu_modl->addNewMenu()) {
            $this->session->set_flashdata("message", _flashMessage("success", "Menu Berhasil Di Tambahkan!"));
         } else {
            $this->session->set_flashdata("message", _flashMessage("danger", "Menu Gagal Di Tambahkan!"));
         }
         redirect("menu");
         exit;
      }
   }

   public function updateMenu()
   {
      if ($this->menu_modl->updateMenu()) {
         $this->session->set_flashdata("message", _flashMessage("success", "Menu Berhasil Di Pebahrui!"));
      } else {
         $this->session->set_flashdata("message", _flashMessage("danger", "Menu Gagal Di Pebahrui!"));
      }
      redirect("menu");
      exit;
   }

   public function detailMenu($menu_name, $menu_id)
   {
      $data["subs_menu"] = $this->menu_modl->getSubMenu($menu_id);
      $data["menu"] = $this->menu_modl->getMenu($menu_name);
      $data["user_data"] = $this->_userData;
      $data["title"] = "Menu Detail";

      $this->form_validation->set_rules("menu-name", "Name", "trim|required|max_length[30]|min_length[3]|regex_match[/^[a-zA-z\s]*$/]");
      $this->form_validation->set_rules("is-active", "Activation", "trim|required|is_natural");

      if (!$this->form_validation->run()) {
         _templates($data, "menu_page/menu_detail_view.php");
      } else {
         if ($this->menu_modl->updateMenu()) {
            $this->session->set_flashdata("message", _flashMessage("success", "Menu Berhasil Di Pebahrui!"));
         } else {
            $this->session->set_flashdata("message", _flashMessage("danger", "Menu Gagal Di Pebahrui!"));
         }
         redirect("menu");
         exit;
      }
   }

   public function delete($name)
   {
      if ($this->menu_modl->deleteMenu($name)) {
         $this->session->set_flashdata("message", _flashMessage("success", "Menu $name Berhasil Dihapus"));
         redirect("menu");
         exit;
      }
   }

   //------------------------------------------------------------ Sub Menu ------------------------------------------------------------

   public function submenu($id = "")
   {
      $data["user_data"] = $this->_userData;
      $data["sbmenu"] = $this->menu_modl->getSubMenu();
      $data["menus"] = $this->menu_modl->getMenu();
      $data["title"] = "Sub Menu";

      $this->form_validation->set_rules("submenu-name", "Name", "trim|required");
      $this->form_validation->set_rules("submenu-icon", "Icon", "trim|required");
      $this->form_validation->set_rules("submenu-url", "URL", "trim|required");

      if (!$this->form_validation->run()) {
         _templates($data, "menu_page/submenu_page/sbmenu_view.php");
      } else {
         $result = $this->menu_modl->addSubMenu();
         if (!$result) {
            $this->session->set_flashdata("message", _flashMessage("danger", "Menu Baru Gagal Ditambahkan"));
         } else {
            $this->session->set_flashdata("message", _flashMessage("success", "Menu Baru Berhasil Ditambahkan"));
         }
         redirect("menu/submenu");
         exit;
      }
   }

   public function updateSbMenu()
   {
      $menu_name = $this->input->post("submenu-name", true);
      $this->form_validation->set_rules("submenu-name", "Name", "trim|required");
      $this->form_validation->set_rules("submenu-icon", "Icon", "trim|required");
      $this->form_validation->set_rules("submenu-url", "URL", "trim|required");

      if (!$this->form_validation->run()) {
         $this->session->set_flashdata("message", _flashMessage("danger", "SubMenu $menu_name Gagal di Update"));
      } else {
         $result = $this->menu_modl->updateSubMenu();
         if (!$result) {
            $this->session->set_flashdata("message", _flashMessage("danger", "SubMenu $menu_name Gagal di Update"));
         } else {
            $this->session->set_flashdata("message", _flashMessage("success", "SubMenu $menu_name Berhasil di Update"));
         }
         redirect("menu/submenu");
         exit;
      }
   }


   // ------------------------------------------------------------ AjaxSubMenu ------------------------------------------------------------

   public function ajaxGetAllSubMenuById($id)
   {
      $result = $this->menu_modl->getSubMenuById($id);
      echo json_encode($result);
   }

   public function ajaxGetAllMenu()
   {
      $result = $this->menu_modl->getMenu();
      echo json_encode($result);
   }
}
