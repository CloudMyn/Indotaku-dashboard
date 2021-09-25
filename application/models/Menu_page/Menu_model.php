<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{

   //------------------------------ Menu ------------------------------

   public function addNewMenu()
   {
      $menu = [
         "menu_name" => htmlspecialchars($this->input->post("menu-name", true)),
         "is_active" => htmlspecialchars($this->input->post("is-active", true)),
      ];
      return $this->db->insert("user_menu", $menu);
   }

   public function updateMenu()
   {
      $menu = [
         "menu_name" => htmlspecialchars($this->input->post("menu-name", true)),
         "is_active" => htmlspecialchars($this->input->post("is-active", true)),
      ];
      return $this->db->update("user_menu", $menu, ["menu_id" => $this->input->post("id")]);
   }

   public function deleteMenu($name)
   {
      return $this->db->delete("user_menu", ["menu_name" => $name]);
   }

   //------------------------------ Sub Menu ------------------------------

   public function addSubMenu()
   {
      $subMenu = [
         "sm_title" => $this->input->post("submenu-name", true),
         "sm_icon" => $this->input->post("submenu-icon", true),
         "sm_url" => $this->input->post("submenu-url", true),
         "menu_id" => $this->input->post("menu-name", true),
         "sm_active" => $this->input->post("is-active", true),
      ];
      return $this->db->insert("user_sub_menu", $subMenu);
   }

   public function updateSubMenu()
   {
      $subMenu = [
         "sm_title" => $this->input->post("submenu-name", true),
         "sm_icon" => $this->input->post("submenu-icon", true),
         "sm_url" => $this->input->post("submenu-url", true),
         "menu_id" => $this->input->post("menu-name", true),
         "sm_active" => $this->input->post("is-active", true),
      ];
      return $this->db->update("user_sub_menu", $subMenu, ["sm_id" => $this->input->post("sm_id")]);
   }

   // ------------------------------ Getter ------------------------------

   public function getMenu($menu = "")
   {
      if (empty($menu)) {
         return $this->db->get("user_menu")->result_array();
      } else {
         return $this->db->get_where("user_menu", ["menu_name" => $menu])->row_array();
      }
   }

   public function getSubMenu($id = "")
   {
      if (empty($id)) {
         return $this->db->join("user_menu", "menu_id")->order_by("menu_id", "ASC")->get("user_sub_menu")->result_array();
         // var_dump($resjoin);die;
         // return $this->db->get("user_sub_menu")->result_array();
      } else {
         return $this->db->get_where("user_sub_menu", ["menu_id" => $id])->result_array();
      }
   }

   public function getSubMenuById($id)
   {
      return $this->db->get_where("user_sub_menu", ["sm_id" => $id])->row_array();
   }
}
