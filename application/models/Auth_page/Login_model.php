<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model{

   //--Init User Data--
   private $_db_account;

   //Konstruktor Method
   public function __construct()
   {
      //--Ambil Data User Yang Sedang Aktif Dari Database
      $this->_db_account = $this->db->get_where("users_account", ["email" => $this->input->post("email", true)]);
   }

   //Method Validasi Akun
   public function _validateAccount($data){
      if( $data === "email") {
         //Mengembalikan Jumlah Baris Yang Terpengaruhi
         return $this->_db_account->num_rows();
      } else if ( $data === "isActive" ) {
         //Mengembalikan Aktifasi user
         return $this->_db_account->row_array()["is_active"];
      } else if ( $data === "password" ) {
         //Berisi Password User Yang Dimasukkan
         $user_pass = $this->input->post("password");
         //Berisi Passsword User Yang Diambil Dari Databases
         $hash_pass = $this->_db_account->row_array()["password"];
         //Check Jika Password Tidak Cocok Dengan Password Yang Ada Di Dalam Databases
         if( !password_verify($user_pass, $hash_pass) ) {
            return false;
         } else {
            return true;
         }
      }
   }

   public function _getRoleId(){
      //Mengembalikan Role_id
      return $this->_db_account->row_array()["role_id"];
   }
}