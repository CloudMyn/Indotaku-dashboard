<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Comic_model extends CI_Model
{
    private $_comic_table, $_chapter_table;
    public function __construct()
    {
        /// ------ Get Active Server ------
        $active_server  =   $this->get_active_server();
        $this->_comic_table  =   $this->get_active_table($active_server)["ws_komik_table"];
        $this->_chapter_table  =   $this->get_active_table($active_server)["ws_komik_ch_table"];
    }

    public function set_active_server(string $active_server): string
    {
        $data                   =   $this->get_active_table($active_server);
        $this->_comic_table     =   $data["ws_komik_table"];
        $this->_chapter_table   =   $data["ws_komik_ch_table"];
        return $data["ws_komik_name"];
    }

    public function get_active_server(): string
    {
        return $this->db->get_where("_komik_config", ["_kc_name" => "active_server"])->row_array()["_kc_value"];
    }

    public function get_active_table($active_server): array
    {
        return $this->db->get_where("_komik_ws", ["ws_komik_name" => $active_server])->row_array();
    }

    public function get_active_comic(){
        $this->db->select("comic_url_source, comic_web_source, comic_storage");
        return $this->db->get_where($this->_comic_table, ["comic_active" => "1"])->result_array();
    }

    public function new_comic($comic)
    {
        $this->db->insert("_komik_adv_filter", ["comic_slug" => $comic["comic_slug"]]);
        if ($this->db->get_where($this->_comic_table, ["comic_slug" => $comic["comic_slug"]])->num_rows() == 0) {
            return $this->db->insert($this->_comic_table, $comic);
        } else {
            return true;
        }
    }

    /**
     * to update time in comic
     *  @return void
     */
    public function update_time($comic_name)
    {
        $this->db->update($this->_comic_table, ["comic_update" => time()], ["comic_slug" => $comic_name]);
    }

    /// to update newest time in comic
    public function updateComicStatus($comic_name, $status)
    {
        $this->db->update($this->_comic_table, ["comic_active" => $status], ["comic_slug" => $comic_name]);
    }

    public function update_comic($comic_slug, $genre)
    {
        $this->load->model("Chapter_page/Chapter_model", "chapter_model");
        $old_cover = $this->get_comic_cover($comic_slug);
        if (!empty($_FILES["comic_cover"]["name"])) {
            unlink(FCPATH . "assets/image/komik/" . $old_cover);
            $comic["comic_cover"] =  $this->uploadImage();
        }
        $comic["comic_name"]        = htmlspecialchars($this->input->post("comic_name", true));
        $comic["comic_desc"]        = htmlspecialchars($this->input->post("comic_desc", true));
        $comic["comic_author"]      = htmlspecialchars($this->input->post("comic_author", true));
        $comic["comic_status"]      = htmlspecialchars($this->input->post("comic_status", true));
        $comic["comic_rating"]      = htmlspecialchars($this->input->post("comic_rating", true));
        $comic["comic_genre"]       = htmlspecialchars($genre);
        $comic["comic_type"]        = htmlspecialchars($this->input->post("comic_type", true));
        $comic["comic_web_source"]  = htmlspecialchars($this->input->post("comic_web_source", true));
        $comic["comic_active"]      = htmlspecialchars($this->input->post("comic_active", true));
        $comic["comic_storage"]     = htmlspecialchars($this->input->post("comic_storage", true));
        $comic["comic_chapters"]    = htmlspecialchars($this->chapter_model->get_total_chapter($comic_slug));
        return $this->db->update($this->_comic_table, $comic, ["comic_slug" => $comic_slug]);
    }

    public function delete_comic($slug)
    {
        $cover = $this->comic_model->get_comic_cover($slug);
        try {
            $dir = FCPATH . "assets\\image\\komik-chapters\\$slug";
            unlink(FCPATH . "assets\\image\\komik\\$cover");
            if (!file_exists($dir)) {
                rmdir($dir);
            }
        } catch (\Exception $er) {
        }
        return $this->db->delete($this->_comic_table, ["comic_slug" => $slug]);
    }

    public function save_comic_cover($cover_url, $comic_name, $blobs_file)
    {
        if (explode("\\", FCPATH)) {
            $path = FCPATH . "assets\image\komik";
            $arrUrl     =   explode(".", $cover_url);
            $extention  =   end($arrUrl);
            $full_path  =   "$path\\$comic_name-cover.$extention";
        } else {
            $path = FCPATH . "assets/image/komik";
            $arrUrl     =   explode(".", $cover_url);
            $extention  =   end($arrUrl);
            $full_path  =   "$path/$comic_name-cover.$extention";
        }
        if (!is_dir($path)) {
            mkdir($path);
        }
        $f = fopen($full_path, "w");
        file_put_contents($full_path, $blobs_file);
        $result = $this->_imageResize($full_path);
        fclose($f);
        // var_dump("Cover TerModifikasi $result");
        // die;
        return "$comic_name-cover.$extention";
    }

    public function uploadImage()
    {
        // --- Init variable ---
        $path = "./assets/image/komik/";

        // - Bertujuan Untuk mengambil extensi file yang di upload
        // var_dump($_FILES);die;
        $getExten = explode(".", $_FILES["comic_cover"]["name"]);  // Buat string ke dalam array dengan pemisah '.'
        $getExten = "." . end($getExten);                          // Ambil array dengan index terahkir dan concat dengan '.'
        // - Konfigurasi atau aturan file yang akan diupload
        $config["upload_path"] = $path;                           // Lokasi File Yang Akan Diupload
        $config["max_size"] = "2048";                             // Ukuran Max File
        $config["allowed_types"] = "jpg|png|jpeg";                     // Extensi Yang diperbolehkan
        $config["overwrite"] = true;                              // Timpah file jika terdapat nama yang sama

        //  - Bertujuan untuk mengubah nama file yang akan diupload
        //  - ex: contoh gambar.jpg to contoh-gambar-e3hsa8kjd.jpg
        $arr = explode(" ", $this->input->post("comic_name"));                     // Buat string ke dalam array dengan pemisah 'spasi'
        $config["file_name"] = strtolower(join("-", $arr)) . "-cover" . $getExten;             // gabungkan array dengan '-' dan concat dengan uniqid()

        //  - Load Library upload dan masukkan konfigurasi atau aturan
        $this->load->library("upload", $config);

        //  - Check jika file berhasil diupload
        if ($this->upload->do_upload("comic_cover")) {
            $this->_imageResize($path . $config["file_name"]);
            return $config["file_name"]; // kembalikan nama file untuk dimasukkan ke dalam database
        } else {
            var_dump($this->upload->display_errors()); // tampilkan error jika file gagal di upload
            exit;
        }
    }


    public function addNewLogs($data)
    {
        return $this->db->insert("_komik_logs", $data);
    }


    /**
     * --------------------------------------
     *       Untuk Mendapatkan Total
     * --------------------------------------
     *  @param String|part          merupakan level bagian dari komik logs
     *  @param String|comic_name    merupakan optional param for get comic refrence to comic name
     *  @param bool|count_result    merupakan penanda apakah return value = jumlah hasil query yg di dapatkan
     */
    public function getComicLogsByPart($part, $comic_name, $chapter_name, $count_result = false)
    {
        $this->db->order_by("_kl_date", "DESC");
        if (!empty($comic_name)) $this->db->where("_kl_komik_name", $comic_name);
        if (!empty($chapter_name)) $this->db->where("_kl_name", $chapter_name);
        $this->db->where("_kl_part", $part);
        return ($count_result) ? $this->db->get("_komik_logs")->num_rows() : $this->db->get("_komik_logs")->result_array();
    }

    /**
     * ---------------------------------------------------------------------------------
     *       Untuk Mendapatkan Comic Logs Dengan Bagian Tertentu Dan Membatasinya
     * ---------------------------------------------------------------------------------
     *  @param int|limit                merupakan batasan content yang dihasikan terhadap query ke db
     *  @param int|offset               merupakan posisi content yang berhubungab dengan limit
     *  @param String|comic_name        merupakan optional param for get comic that refrence to comic name
     *  @param String|where_part        merupakan sebuah level penanda logs terhadap komik
     *  @return AssocArray
     * 
     *  ex - for limit and offset:
     *      *   limit   = 10
     *      *   offset  = 3
     *      it's mean the pagination will start from 3rd data 
     *      and it'll end in 10 data
     * 
     */
    public function getComicLogsByLimit($limit, $offset, $comic_name, $chapter_name,  $where_part)
    {
        $this->db->limit($limit, $offset);
        if (!empty($comic_name)) $this->db->where("_kl_komik_name", $comic_name);
        if (!empty($chapter_name)) $this->db->where("_kl_name", $chapter_name);
        $this->db->where("_kl_part", $where_part);
        $this->db->order_by("_kl_date", "DESC");
        return $this->db->get("_komik_logs")->result_array();
    }

    //------------------------------------------------Getter-Part------------------------------------------------

    /**
     *  This Function Will Check Whether Is Has
     *  Already Chapter in databases
     * 
     *  @param string $params 
     *  @param string $comic_name|$comic_slug
     *  @param boolean  $is_urls : is params is urls or not
     *  @return int     its hold a number of affected rows
     */
    public function check_existance($params, $comic_name, $is_urls = false)
    {
        if ($is_urls) {
            $arr = explode("://", $params);
            $new_chapter_dir = implode("://www.", $arr);
            $this->db->where("chapter_dir", $new_chapter_dir);
            $this->db->or_where("chapter_dir", $params);
        } else {
            $this->db->where("chapter_slug", $params);
        }
        $this->db->where("comic_slug", $comic_name);
        return $this->db->get($this->_chapter_table)->num_rows();
    }

    // ---Comic Sect---
    /** 
     * @param name => String
     * @param komik_name => bool
     * @return komik_data => array
     */
    public function get_comic_cover($comic_slug)
    {
        return $this->db->get_where($this->_comic_table, ["comic_slug" => $comic_slug])->row_array()["comic_cover"];
    }


    /** 
     * @param name => String
     * @param comic_url => bool
     * @return array => array
     */
    public function get_comic_slug($comic_url)
    {
        $this->db->select("comic_slug, comic_name");
        return $this->db->get_where($this->_comic_table, ["comic_url_source" => $comic_url])->row_array()["comic_slug"];
    }


    /** 
     * 
     * Check if comic exists or not
     * 
     * @param name => String
     * @param komik_name => bool
     * @return komik_data => array
     */
    public function check_comic_existance($source)
    {
        return $this->db->get_where($this->_comic_table, "comic_url_source = '$source'")->num_rows();
    }


    /**
     * ------------------ get_comic_limit() ------------------
     *  Berfungsi Unutk Mengambil Comic Yang Diquery 
     *  Terahkir Kali.
     *  @param  String              Limit  | Jumlah Content Perhalaman      | Nilai Default = ""
     *  @param  int                 Offset | Lokasi Dimulainya Pagination   | Nilai Default = 0
     *  @return Assoc_Array         Comic Berdasarkan Pencarian Jika Keyword Dimasukkan Atau Semua Comic Dengan Limit Yg Sudah Ditetapkan
     */
    public function get_comic_limit($limit = 7, $offset = 0, $keyword = "", $order_name, $order_type, $specieal_orders) // [-Passed-]
    {
        $limit          = intval($limit);
        $offset         = intval($offset);
        $keyword        = htmlspecialchars($keyword);
        $clause         = "";
        $like           = "";
        $or_like        = "";

        if ($keyword !== "") {
            $like   = " `comic_name` LIKE '$keyword%' ";
            $or_like = " `comic_author` LIKE '$keyword%' ";
        }

        /**
         * @category Explanations - About This Algorythm
         *     Tunjuan Untuk Mendapatkan Komik 18plus Dengan Nama/Author
         *  Sama Dengan Inputan Yang User Masukkan  : "Under Observation"
         *  ----------------- Sample Code -----------------
         *  @var    $keyword = "Under Observation";
         *  if ($order_name === "comic_18plus") {
         *   $where = " `comic_18plus` = '1' ";
         *   $clause   =  ($keyword !== "") ?
         *       " WHERE $where AND $like OR $where AND $or_like" :
         *       " WHERE $where ";
         *  } 
         * 
         */

        // $specieal_orders = [
        //     "comic_project" => "1",
        //     "comic_active"  => "0",
        //     "comic_18plus"  => "1"
        // ];

        foreach ($specieal_orders as $k => $v) {
            if ($order_name === $k) {
                $where = " `$k` = '$v' ";
                $clause   = ($keyword !== "") ?
                    " WHERE $where AND $like OR $where AND $or_like" :
                    " WHERE $where";
            }
        }

        if ($clause === "") {
            $clause = ($keyword !== "") ?
                " WHERE $like OR $or_like ORDER BY `$order_name` $order_type" :
                " ORDER BY `$order_name` $order_type";
        }

        // var_dump($clause);
        $queries    =   "SELECT comic_name, comic_author, comic_status, 
                        comic_slug, comic_url_source, comic_web_source,
                        comic_active, comic_posted_by, comic_date, comic_update,
                        comic_chapters FROM {$this->_comic_table} $clause LIMIT $offset ,$limit";



        $d = $this->db->query($queries)->result_array();
        return $d;
        /// 
        /// [-] Final Result
        ///     ex: SELECT `comic_name`,`comic_author`,`comic_18plus` FROM _komik 
        ///     [WHERE  `comic_18plus` = 1 AND `comic_author` LIKE 'tia%']
        ///     [OR `comic_18plus` = 1 AND `comic_name` LIKE 'tia%'];
        ///  
        ///

    }


    /**
     * ------------------ getLatestComicQuery() ------------------
     *  Berfungsi Unutk Mengambil Jumlah Comic Yang Diquery 
     *  Terahkir Kali.
     *  @param  String      Keyword Pencarian
     *  @return int         Total Komik
     */
    public function getLatestComicQuery(String $keyword = null)
    {

        if ($this->session->userdata("ss")) {

            $data = explode("|", $this->session->userdata("ss"));

            $this->db->where($data[0], $data[1]);
            $this->db->like("comic_name", $keyword, "after");
            $this->db->or_where($data[0], $data[1]);
            $this->db->like("comic_author", $keyword, "after");
        } else {
            $this->db->like("comic_name", $keyword, "after");
            $this->db->or_like("comic_author", $keyword, "after");
        }

        $this->db->from($this->_comic_table);
        return $this->db->count_all_results();
    }
    /**
     *  ------------------ switch_server() ------------------
     * 
     *  Berfungsi untuk mengubah server
     *  @return boolean
     */
    public function switch_server(string $target_name) // [-Done-]
    {
        $result = $this->db->update("_komik_config", ["_kc_value" => $target_name, "_kc_update" => time()], ["_kc_name" => "active_server"]);
        var_dump($result, $target_name);
        if (!$result) return false;
        $current_active =   $this->set_active_server($target_name);
        var_dump($current_active);
        if ($current_active !== $target_name) return false;
        // die;
        return true;
    }

    /**
     *  ------------------ get_all_comic() ------------------
     * 
     *  - Mendapatkan Comic Berdasarkan `Slug-nya` 
     *  @return Array dari comic berdasarkan `Slug-nya`
     */
    public function get_all_comic($comic_slug)
    {
        return $this->db->get_where($this->_comic_table, ["comic_slug" => $comic_slug])->row_array();
    }

    /**
     *  ------------------ get_all_genre() ------------------
     * 
     *  - Mendapatkan Genre Dari Comic 
     *  @return Array dari comic genre
     */
    public function get_all_genre() // [-Done-]
    {
        $this->db->order_by("name", "ASC");
        return $this->db->get("_komik_genre")->result_array();
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

    /**
     *  ------------------ get_target_data() ------------------
     * 
     *  - Mendapatkan data lenkap dri web target
     *  @return Array dari scrape source
     */
    public function get_target_data($target_name)
    {
        $this->db->where(["ws_komik_name" => $target_name]);
        return $this->db->get("_komik_ws")->row_array();
    }

    //------------------------------------------------Count-Data------------------------------------------------

    public function countAllComic()
    {
        return $this->db->get($this->_comic_table)->num_rows();
    }

    //------------------------------------------------Private-function------------------------------------------------

    private function _imageResize($image_source)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_source;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;
        $config['width']         = 800;
        $config['height']       = 1100;
        $this->load->library('image_lib', $config);
        return $this->image_lib->resize();
    }



    public function get_total_chapter($comic_slug)
    {
        $this->db->where("comic_slug", $comic_slug);
        $this->db->from($this->_chapter_table);
        return $this->db->count_all_results();
    }
}
