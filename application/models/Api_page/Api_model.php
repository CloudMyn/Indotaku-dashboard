<?php 

class Api_model extends CI_Model{
   public function getAllApiKeys(){
      return $this->db->get("_api_keys")->result_array();
   }
   public function getApiKeyLimitBy($limit, $start, $keyword){
      if($keyword){
         $this->db->like("user_id", $keyword, "after");
         $this->db->or_like("key", $keyword, "after");
      }
      $this->db->order_by("_api_keys.date_created", "DESC");
      $this->db->join("users_account", "_api_keys.user_id = users_account.id");
      $this->db->join("user_role", "user_role.role_id = _api_keys.level");
      return $this->db->get("_api_keys", $limit, $start)->result_array();
   }

   public function getLatestApiKeyQuery($keyword){
      $this->db->like("user_id", $keyword, "after");
      $this->db->or_like("key", $keyword, "after");
      $this->db->order_by("date_created", "DESC");
      $this->db->from("_api_keys");
      $this->db->join("user_role", "role_id = level");
      return $this->db->count_all_results();
   }
}