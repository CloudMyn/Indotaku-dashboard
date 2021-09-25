<?php

class Admin_model extends CI_Model
{
   public function get_all_roles()
   {
      return $this->db->get("user_role")->result_array();
   }

   public function get_scrape_target()
   {
      $this->db->select("ws_komik_id, ws_komik_name");
      return $this->db->get("_komik_ws")->result_array();
   }

   public function get_active_server()
   {
      return $this->db->get("_komik_config", ["_kc_name" => "active_server"])->row_array()["_kc_value"];
   }

   public function set_active_server($id)
   {
      $name = $this->db->get_where("_komik_ws", ["ws_komik_id" => $id])->row_array()["ws_komik_name"];
      return  $this->db->update("_komik_config", ["_kc_value" => $name], ["_kc_name" => "active_server"]);
   }
}
