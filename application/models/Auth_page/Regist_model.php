<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Regist_model extends CI_Model
{
   public function setNewAccount()
   {
      $data = [
         "id" => "",
         "image" => "default.png",
         "fullname" => htmlspecialchars($this->input->post("fullname", true)),
         "username" => htmlspecialchars($this->input->post("username", true)),
         "email" => htmlspecialchars($this->input->post("email", true)),
         "password" => password_hash($this->input->post("password"), PASSWORD_DEFAULT),
         "role_id" => 3,
         "is_active" => 1,
         "last_online" => time(),
         "date_created" => time()
      ];
      return $this->db->insert("users_account", $data);
   }
}
