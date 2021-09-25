<?php 

defined('BASEPATH') or exit('No direct script access allowed');

class Logs extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        _checkUserAccess();
        $this->load->model("Logs_page/Logs_model", "log");
    }

    public function index(){

           /**
         * Check :
         *  `if` Tombol Cari Ditekan Maka data['Keyword'] = Nilai Dari Inputan User
         *  `else` Maka data['keyword'] Diambil Dari Session Jika Sudah Ada
         */
        if ($this->input->post("submit")) {
            $data["keyword"] = $this->input->post("keyword", true);         // Ambil Inputan User & Masukkan Ke data['keyword']
            $this->session->set_userdata("keyword", $data["keyword"]);      // Buat Session Dari Inputan User
        } else $data["keyword"] = $this->session->userdata("keyword");      // Ambil Data Dari Session Jika Sudah Dibuat



        /**
         * ------------------------------------
         *        Ordering Comic Data
         * ------------------------------------
         */

        if (isset($_POST["order-btn"])) {
            $order_name = $this->input->post("order-name", true);
            $order_type = $this->input->post("order-type", true);
            $this->session->set_userdata("order-sess", "$order_name|$order_type");
        } else {
            if ($this->session->userdata("order-sess"))
                $data_sess = $this->session->userdata("order-sess");
            else $data_sess = "comic_update|DESC";
            $data_sess = explode("|", $data_sess);
            $order_name =   $data_sess[0];
            $order_type =   $data_sess[1];
        }

        $specieal_orders = [
            "comic_project" => "1",
            "comic_active"  => "0",
            "comic_18plus"  => "1"
        ];
        $order_ss = false;
        foreach ($specieal_orders as $k => $v) {
            if ($order_name === $k) {
                $order_ss = true;
                $this->session->set_userdata("ss", "$k|$v");
            }
        }

        if (!$order_ss) {
            // var_dump("Session ss terhapus");
            $this->session->unset_userdata("ss");
        }



        /**
         * ------------------------------------
         *       Config For Pagination 
         * ------------------------------------
         */
        $this->load->library("pagination");                 // Load Library Pagination Dari CI
        $config["per_page"]     = 7;                        // Jumlah Content Per Halaman
        $config['num_links']    = 2;                        // Jumlah Digit Tombol Pagination Di Kiri & Kanan
        $config["first_link"]   = "first";                  // Tombol Awal Pagination
        $config["last_link"]    = "last";                   // Tombol Akhir Pagination
        $config["base_url"]     = base_url("comic/index");  // Set Halaman Yang Akan Di Pasangkan Pagination
        $config["total_rows"]   = $this->comic_model->getLatestComicQuery($data["keyword"]); // Total Baris Diamil Dari getLatestComicQuery()
        // Load Dan Init Semua Konfigurasi Pagination
        $this->pagination->initialize($config);

        /**
         * ------------------------------------
         *          Data To Display
         * ------------------------------------
         */
        $data["title"]          = "Comic Logs";                 // - Title Dari Setiap Halaman
        $data["user_data"]      = _getUserData();               // - User Data Diambil Dari HelperFunction _getUserData()
        $data["limit"]          = $config["per_page"];          // - Limit Merupakan Batasan Content Di Setiap Halaman
        $data["totals_result"]  = $config["total_rows"];        // - Jumlah Hasil Dari Pencarian Comic
        /**
         * ------------ data['start'] ------------
         *  Merupakan  Content
         *  ------------------------------------
         *              ==Check==
         *  ------------------------------------
         *  `jika` Jumlah Baris Lebih Kecil Sama Dengan Jumlah 
         *  Content Perhalaman Maka Pagination Akan Mulai i Content Ke 0
         *  `else` Maka Halaman Pagination Yang Sekarang Di Akses
         *  Di Ambil Dari URI di Segment ke `3`
         * 
         *   ex `URI`: 'https://localhost/comic/index/`halaman-sekarang`' 
         * 
         *  ------------ data['comicx'] ------------
         *  Ambil Comic Dari Comic Model Dengan Method get_comic_limit()
         */


        $data["order_menu"] = [
            "comic_name" => "Name",
            "comic_upvotes" => "Upvotes",
            "comic_18plus" => "18+",
            "comic_active" => "UnActive",
            "comic_chapters" => "Total Chapters",
            "comic_project" => "My Project",
            "comic_visited" => "Visited",
            "comic_update" => "Update",
            "comic_date" => "Published",
        ];
        $data["order_menu_type"] = ["ASC", "DESC"];
        $data["order_menu_active"]  = $order_name;
        $data["order_type_active"]  =   $order_type;


        $data["start"]          = ($config["total_rows"] <= $config["per_page"]) ? 0 : $this->uri->segment(3);
        $data["comics"]         = $this->comic_model->get_comic_limit(
            $config["per_page"],
            $data["start"],
            $data["keyword"],
            $order_name,
            $order_type,
            $specieal_orders
        );
        _templates($data, "logs_page/comic_logs_view.php");
    }
}