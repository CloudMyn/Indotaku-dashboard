<?php

class Block extends CI_Controller
{
   public function index()
   {
      $data["title"] = "Blocked";
      _checkUserAccess();
      $data["user_data"] = _getUserData();
      _templates($data, "block_page/block_view.php");
   }
}
