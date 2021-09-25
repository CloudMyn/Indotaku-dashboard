<?php

use phpDocumentor\Reflection\Types\Integer;

defined('BASEPATH') or exit('No direct script access allowed');

class Chapter_model extends CI_Model
{
   private $_comic_table, $_chapter_table;
   public function __construct()
   {
      $this->load->model("Comic_page/Comic_model", "comic_model");
      $active_serve        = $this->comic_model->get_active_server();
      $data                = $this->comic_model->get_active_table($active_serve);
      $this->_comic_table  = $data["ws_komik_table"];
      $this->_chapter_table = $data["ws_komik_ch_table"];
   }


   ///---------------------------------------------Chapter-Model---------------------------------------------

   /**
    *  ------------------ updateChapter() ------------------
    * 
    */
   public function updateChapter($data)
   {
      return $this->db->update($this->_chapter_table, $data, ["id" => $data["id"]]);
   }



   /**
    *  ------------------ getAllChapter() ------------------
    * 
    *  - Mendapatkan Semua Chapter Dari Semua Komik 
    *  @return Array dari Semua Comic Chapter
    */
   public function getAllChapter(): array
   {
      $this->db->order_by("chapter_id", "ASC");
      $this->db->order_by("chapter_id", "DESC");
      return $this->db->get($this->_chapter_table)->result_array();
   }

   /**
    *  ------------------ getChapter() ------------------
    * 
    *  - Mendapatkan Comic Chapter Berdasarkan `Komik_Name` 
    *  @return Array dari Chapter berdasarkan `komik_name`
    */
   public function get_comic_chapter($comic_slug)
   {
      $this->db->order_by("chapter_id", "DESC");
      $this->db->order_by("chapter_name", "ASC");
      return $this->db->get_where($this->_chapter_table, ["comic_slug" => $comic_slug])->result_array();
   }

   public function get_total_chapter($comic_slug): Int
   {
      $this->db->where("comic_slug", $comic_slug);
      $this->db->from($this->_chapter_table);
      return intval($this->db->count_all_results() ?? "0");
   }

   public function get_comic($comic_slug)
   {
      return $this->db->get_where($this->_comic_table, ["comic_slug" => $comic_slug])->row_array();
   }
   public function get_latest_chapter($comic_slug)
   {
      $this->db->order_by("chapter_order", "DESC");
      $result = $this->db->get_where($this->_chapter_table, ["comic_slug" => $comic_slug])->result_array();
      $data["comic_chapters"] =  count($result);
      $data["chapter_name"]   =  ($result === []) ? "Belum-Ada-Chapter 0" : $result[0]["chapter_name"];
      return $data;
   }

   public function get_chapter_slug($slug)
   {
      return $this->db->get_where($this->_chapter_table, ["chapter_slug" => $slug])->row_array();
   }


   /**
    *  ------------------ get_scrape_target() ------------------
    * 
    *  - Mendapatkan Sumber Scrape Website 
    *  @return Array dari scrape source
    */
   public function get_scrape_target() // [-Done-]
   {
      return $this->db->get("_komik_ws")->result_array();
   }

   public function save_scraped_data($data_chapters)
   {

      $comic_slug           = $data_chapters["comic_slug"];

      $total_chapters   = $this->get_total_chapter($comic_slug);

      $data["chapter_slug"]   = $data_chapters["chapter_slug"];
      $data["comic_slug"]     = $comic_slug;
      $data["chapter_name"]   = $data_chapters["chapter_name"];
      $data["chapter_images"] = $data_chapters["chapter_images"];
      $data["chapter_dir"]    = $data_chapters["chapter_url"];
      $data["chapter_date"]   =  time();
      /// Update Total Chapter Di table komik
      $comic_update["comic_chapters"] = ++$total_chapters;
      $comic_update["comic_url_source"] = $data_chapters["comic_url"];
      $this->db->update($this->_comic_table, $comic_update, ["comic_slug" => $comic_slug]);
      return $this->db->insert($this->_chapter_table, $data);
   }

   /**
    * --------------------------------------
    *  Tambahkan Chapter Ke Server Storage
    * --------------------------------------
    */
   public function add_new_chapter($comic_slug)
   {
      $chapter_order    = $this->input->post("chapter_name", true);      // - Get User Input With Name `chapter-num`
      $total_chapters   = $this->get_total_chapter($comic_slug);           // - Get Total Chapter
      $path_comic       = "assets/image/komik-chapters/$comic_slug";     // - Dir Location To Store Comic Folder
      $path_chapter     = "assets/image/komik-chapters/$comic_slug/chapter-" . $chapter_order; // - Dir Location To Store Chapter Folder

      // - Buat Direktori Untuk Folder Comic Jika Belum Ada
      if (!file_exists(FCPATH . $path_comic)) mkdir(FCPATH . $path_comic);
      fopen(FCPATH . "$path_comic/index.html", "w");
      // - Buat Direktori Untuk Folder Chapter Jika Belum Ada
      if (!file_exists(FCPATH . $path_chapter)) {
         mkdir(FCPATH . $path_chapter);
         fopen(FCPATH . "$path_chapter/index.html", "w");
      }

      /**
       * --------------------------------------
       *          Init CI Upload Lib
       * --------------------------------------
       */
      $config["upload_path"]      = "./assets/image/komik-chapters/$comic_slug/chapter-$chapter_order";
      $config["max_size"]         = "35048";
      $config["allowed_types"]    = "jpeg|jpg|png|JPG|PNG|JPEG";
      $config["overwrite"]        = true;
      $this->load->library("upload", $config);

      $file               = $_FILES["images"];    // - Simpan Semua Data Dari `$_FILES` kedalam `$file`
      $_FILES             = [];                   // - Kosongkan `$_FILES` Untuk Diisi Kembali Nanti
      $image_path   = "";                   // - Sediakan Variable Untuk Menampung Path Chapter Image
      for ($i = 0; $i < count($file["name"]); $i++) {
         $_FILES["image_$i"]["name"]     = $file["name"][$i];
         $_FILES["image_$i"]["type"]     = $file["type"][$i];
         $_FILES["image_$i"]["tmp_name"] = $file["tmp_name"][$i];
         $_FILES["image_$i"]["error"]    = $file["error"][$i];
         $_FILES["image_$i"]["size"]     = $file["size"][$i];
         $result = $this->upload->do_upload("image_$i");     // Lakukan Upload File
         // if (!$result) return false;                         // Jika Upload Gagal Maka return false
         $image_path .= "$path_chapter/" . $_FILES["image_$i"]["name"] . "|";
      }
      //Pecah Url Image Chapter
      $arr_chap = explode("|", $image_path);
      //Delete Last Index Of Array
      array_pop($arr_chap);
      $image_path = implode("|", $arr_chap);

      // -Collect Chapter Data
      $n = "";
      if ($chapter_order >= 0 and $chapter_order < 10) {
         $n = "0" . $chapter_order;
      } else {
         $n = $chapter_order;
      }
      $data["comic_slug"]        = $comic_slug;
      $data["chapter_name"]      = "chapter " . $n;
      $data["chapter_images"]    =  $image_path;
      $data["chapter_slug"]      =  strtolower($comic_slug) . "-chapter-" . $n;
      $data["chapter_date"]      =  time();
      $data["chapter_dir"]       = "assets/image/komik-chapters/$comic_slug/chapter-$chapter_order";
      $dataChapter["comic_chapters"] = ++$total_chapters;

      $this->db->update($this->_comic_table, $dataChapter, ["comic_slug" => $comic_slug]);
      return $this->db->insert($this->_chapter_table, $data);;
   }


   /** 
    * Delete
    * @param	id use id to delete
    * @return	bool true or false
    */
   public function remove_chapter($comic_slug, $id)
   {

      // Delete Chapter Directory
      $dir_x      =  $this->get_chapter_dir($id);
      $chapter_dir =   $dir_x["chapter_dir"];
      if (file_exists($chapter_dir)) {
         $n_dir      =   new DirectoryIterator($chapter_dir);
         $f_r        =   true;
         foreach ($n_dir as $dir_x) {
            if (is_file($dir_x->getRealPath()) and file_exists($dir_x->getRealPath())) {
               unlink($dir_x->getRealPath());
            }
         }

         $f_r = rmdir($chapter_dir);
         if (!$f_r) {
            redirect("comic/update-comic/$comic_slug");
            exit;
         }
      }


      $comic_chapters = $this->get_total_chapter($comic_slug);
      // Update Total Chapters
      $dataChapter["comic_chapters"] = $comic_chapters - 1;
      $this->db->update($this->_comic_table, $dataChapter, ["comic_slug" => $comic_slug]);

      return $this->db->delete($this->_chapter_table, ["chapter_id" => $id]);
   }

   // ---Chapter Sect---
   /** 
    * Delete
    * @param	chapter_order
    * @return	chapter_dir
    */
   public function get_chapter_dir($id)
   {
      return $this->db->get_where($this->_chapter_table, ["chapter_id" => $id])->row_array();
   }



   public function format_numbers($current_number, $total_number)
   {
      $data = "";
      if ($total_number >= 10000) {
         if ($current_number >= 0 && $current_number <= 9) {
            $data = "00000" . $current_number;
         } else if ($current_number >= 9 && $current_number <= 90) {
            $data = "0000" . $current_number;
         } else if ($current_number >= 100 && $current_number <= 99) {
            $data = "000" . $current_number;
         } else if ($current_number >= 1000 && $current_number <= 9999) {
            $data = "00" . $current_number;
         } else {
            $data = "0" . $current_number;
         }
      } else {
         if ($current_number >= 0 && $current_number <= 9) {
            $data = "0000" . $current_number;
         } else if ($current_number >= 10 && $current_number <= 99) {
            $data = "000" . $current_number;
         } else if ($current_number >= 100 && $current_number <= 999) {
            $data = "00" . $current_number;
         } else {
            $data = "0" . $current_number;
         }
         return $data;
      }
   }

   //------------------------------------------------Private-function------------------------------------------------

   // private function format_numbers($current_number)
   // {

   //    if ($current_number >= 0 && $current_number <= 9) {
   //       $data = "00000" . $current_number;
   //    } else if ($current_number >= 10 && $current_number <= 99) {
   //       $data = "0000" . $current_number;
   //    } else if ($current_number >= 100 && $current_number <= 999) {
   //       $data = "000" . $current_number;
   //    } else if ($current_number >= 1000 && $current_number <= 9999) {
   //       $data = "00" . $current_number;
   //    } else {
   //       $data = "0" . $current_number;
   //    }

   //    return $data;
   // }

}
