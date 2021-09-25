<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Satukan Semua File Yang Terkait Satu SamaLain

/**
 * -------------------------------------------------------------------------------------------------------------------------------------------
 *                                                 *CONTROLLER FOR ALL COMIC PAGES AND LOGIC*
 * -------------------------------------------------------------------------------------------------------------------------------------------
 */
class Comic extends CI_Controller
{
    // Constructor To Initialize BaseConfiguration For Comic Controller
    public function __construct()
    {
        parent::__construct();
        // Validasi Akses User
        _checkUserAccess();
        // Init CI PlugIn And Library
        $this->load->model("Chapter_page/Chapter_model", "chapter_model");
        $this->load->model("Comic_page/Comic_model", "comic_model");  // Load Model And PrevixName is comic_model
        $this->load->library("form_validation");                    // Load Form Validation Lib
        // Set Error Delimiter For Form Validation Lib
        $this->form_validation->set_error_delimiters('<h6 class="alert alert-danger my-0 mb-2">', '</h6>');
    }

    public function delete_search($redirect = "comic")
    {
        $this->session->unset_userdata("ss");
        $this->session->unset_userdata("keyword");
        $this->session->unset_userdata("order-sess");
        redirect($redirect);
        exit;
    }

    /**
     * ------------------------------------------------------------------------
     *                Main Page Of This Controller Is Index()
     * ------------------------------------------------------------------------
     * Merupakan Halaman Utama Dari Comic Controller.
     */
    public function index() // [-Done-]
    {

        // echo $this->time_elapsed_string('2013-05-01 00:22:35');
        // echo $this->time_elapsed_string('@1367367755'); # timestamp input
        // echo $this->time_elapsed_string('2013-05-01 00:22:35', true);
        // die;
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
        $data["title"]          = "My Comic";                   // - Title Dari Setiap Halaman
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
        _templates($data, "comic_page/comic_view.php");
    }




    public function update($comic_slug = NULL)
    {

        if ($comic_slug !== NULL) {
        }


        /**
         * Check :
         *  `if` Tombol Cari Ditekan Maka data['Keyword'] = Nilai Dari Inputan User
         *  `else` Maka data['keyword'] Diambil Dari Session Jika Sudah Ada
         */
        if ($this->input->post("submit")) {
            $data["keyword"] = $this->input->post("keyword", true);         // Ambil Inputan User & Masukkan Ke data['keyword']
            $this->session->set_userdata("keyword-update", $data["keyword"]);      // Buat Session Dari Inputan User
        } else $data["keyword"] = $this->session->userdata("keyword-update");      // Ambil Data Dari Session Jika Sudah Dibuat



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
        $data["title"]          = "Update Comic";                   // - Title Dari Setiap Halaman
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

        $data["scrape_targets"]  =   $this->comic_model->get_scrape_target();

        /**
         * ------------------------------------
         *     Set Form Validation Rules
         * ------------------------------------
         */
        $this->form_validation->set_rules("scrape-target", "Web Target", "trim|required");
        // $this->form_validation->set_rules("update-all", "Update All", "trim|required");
        $this->form_validation->set_rules("update-only", "Update Rule", "trim|required");
        $this->form_validation->set_rules("save-chapter-images", "Save Rule", "trim|required");

        if (!$this->form_validation->run()) _templates($data, "comic_page/comic_update_view.php");
        else {

            $target_server          =   htmlspecialchars($this->input->post("scrape-target", true));
            // $update_all             =   intval(htmlspecialchars($this->input->post("update-all", true)));
            $update_only            =   intval(htmlspecialchars($this->input->post("update-only", true)));
            $save_opt               =   htmlspecialchars($this->input->post("save-chapter-images", true));
            $_POST = [];

            // Switch active server to the current target
            $result = $this->comic_model->switch_server($target_server);
            if (!$result) redirect("comic/update");     // if its faild than abort entire operation

            $web_data   =   $this->comic_model->get_target_data($target_server);
            // var_dump($this->comic_model->get_active_table());
            $_POST['chapter-selector']      =   $web_data["ws_komik_selector_chap"];
            $_POST['image-selector']        =   $web_data["ws_komik_selector_img"];
            $_POST['web-saveopt']           =   $save_opt;
            $_POST['web-sources']           =   $web_data["ws_komik_name"];

            $this->_start_scrape_comic($web_data["ws_komik_update_page"], $web_data["ws_komik_selector"], $update_only);
            $this->_redirect("comic/update");
        }
    }

    public function check_comic_update()
    {
        $comic_urls = $this->comic_model->get_active_comic();
        $this->load->library("neoscraper");

        // $required_data = $this->comic_model->ge
        // var_dump($comic_urls[0]);die;
        foreach ($comic_urls as $comic_url) {

            $current_target = ($comic_url['comic_web_source'] === 'komicast.com') ? 'komikcast.com' : $comic_url['comic_web_source'];
            $target_data = $this->comic_model->get_target_data($current_target);


            $_POST['chapter-selector']   =   $target_data["ws_komik_selector_chap"];
            $_POST['image-selector']     =   $target_data["ws_komik_selector_img"];
            $_POST['web-saveopt']        =   intval($comic_url["comic_storage"]);
            $_POST['web-sources']        =   $current_target;

            $this->_start_scraping($comic_url['comic_url_source'], true);
        }
    }




    /**
     * ------------------------------------------------------------------------
     *                Controller To Scrape Comic Data From Web
     * ------------------------------------------------------------------------
     */
    public function scrapeComics()
    {

        /**
         * ------------------------------------
         *          Data To Display 
         * ------------------------------------
         */
        $data["title"]              = "Add New Chapter";                                    // - Title Dart Setiap Halaman
        $data["user_data"]          = _getUserData();                                       // - User Data Diambil Dari HelperFunction _getUserData()
        $data["scrape_source"]     = $this->comic_model->get_scrape_target();                   // - Sumber Data Scrape

        /**
         * ------------------------------------
         *     Set Form Validation Rules
         * ------------------------------------
         */
        $this->form_validation->set_rules("web-url", "Web Comic URL", "trim|required");
        $this->form_validation->set_rules("comic-selector", "Comic Selector", "trim|required");
        $this->form_validation->set_rules("chapter-selector", "Chapter Selector", "trim|required");
        $this->form_validation->set_rules("image-selector", "Image Selector", "trim|required");
        $this->form_validation->set_rules("web-saveopt", "Save Option", "trim|required");
        $this->form_validation->set_rules("web-sources", "Web Target", "trim|required");
        $this->form_validation->set_rules("update-only", "Update Rule", "trim|required");

        if (!$this->form_validation->run()) _templates($data, "comic_page/comics_scrape_view.php");
        else $this->_scrapeComics();
    }


    /**
     * ------------------------------------------------------------------------
     *                     Logic For Controller Scrape Comics
     * ------------------------------------------------------------------------
     *  - Bertujuan Mengambil Daftar Data Komik Dari Suatu Website Penyedia
     */
    private function _scrapeComics()
    {
        $web_url            =   $this->input->post("web-url", true);
        $comic_selector     =   $this->input->post("comic-selector", true);
        $update_only        =   intval(htmlspecialchars($this->input->post("update-only", true)));

        $this->_start_scrape_comic($web_url, $comic_selector, $update_only);
        $this->_redirect("comic");
    }

    private function _start_scrape_comic(string $web_url, string $comic_selector, int $update_only): void
    {

        $this->load->library("neoscraper");

        ///     [1] Get comic urls from web target
        $comics_url     = $this->neoscraper->get_comic_url($web_url, $comic_selector);
        if (!$comics_url || $comics_url === []) redirect("comic/update");

        ///     [2] loop through the array to get the comic url
        foreach ($comics_url as $comic_url) {
            $is_exists = $this->comic_model->check_comic_existance($comic_url);
            // if is exists and only update = 1 
            // which mean dont add if not exists
            if ($is_exists == 0 && $update_only == 1) continue;
            else if ($is_exists == 1 && $update_only == 1) $update_only = true;
            else $update_only = false;

            $this->_start_scraping($comic_url, $update_only, ($is_exists == 0) ? false : true);
        }
    }


    /**
     * ------------------------------------------------------------------------
     *                  Comic Controller To Scrape Comic Data
     * ------------------------------------------------------------------------
     *  - Merupakan Controller Atau Tampilan Dari Halaman `ScrapeComic`
     *  - Bertujuan Meng-Scrape Data Comic Dari Suatu Website
     */
    public function scrape_comic()
    {

        /**
         * ------------------------------------
         *          Data To Display 
         * ------------------------------------
         */
        $data["title"]              = "Scrape Comic";                                       // - Title Dart Setiap Halaman
        $data["user_data"]          = _getUserData();                                       // - User Data Diambil Dari HelperFunction _getUserData()
        $data["scrape_source"]     = $this->comic_model->get_scrape_target();                   // - Sumber Data Scrape

        /**
         * ------------------------------------
         *     Set Form Validation Rules
         * ------------------------------------
         */
        $this->form_validation->set_rules("comic-url", "Comic URLs", "trim|required");
        $this->form_validation->set_rules("chapter-selector", "Chapter Selector", "trim|required");
        $this->form_validation->set_rules("image-selector", "Image Selector", "trim|required");
        $this->form_validation->set_rules("web-saveopt", "Save Option", "trim|required");
        $this->form_validation->set_rules("web-sources", "Web Target", "trim|required");


        if (!$this->form_validation->run()) _templates($data, "comic_page/comic_scrape_view.php");
        else {

            $comic_url          =   $this->input->post("comic-url");

            $this->_start_scraping($comic_url);

            redirect("comic/scrape-comic/");
        }
    }

    private function _start_scraping(string $comic_url, bool $update_only = false, $is_comic_exist = false): void
    {

        $chapter_selector   =   $this->input->post("chapter-selector");
        $image_selector     =   $this->input->post("image-selector");
        $save_option        =   $this->input->post("web-saveopt");
        $web_source         =   $this->input->post("web-sources");

        /**
         *  --------------------------------------
         *               Comic Part
         *  --------------------------------------
         */

        if ($update_only || $is_comic_exist) {
            $comic_slug     =   $this->comic_model->get_comic_slug($comic_url);
        } else {
            $comic                  =   $this->_get_scraping_data($comic_url, $save_option, $web_source);
            $comic_slug             =   $comic["comic_slug"];
            $comic["comic_cover"]   =   $this->_save_comic_cover($comic["comic_cover"], $comic_slug);
            $result                 =   $this->comic_model->new_comic($comic);   // - Jalankan Fungsi Tambah Comic Dengan Parameter Data Dari Comic

            if (!$result) {
                return;
            }
        }

        /**
         *  --------------------------------------
         *             End of Comic Part
         *  --------------------------------------
         */


        /**
         *  --------------------------------------
         *              Chapter Part
         *  --------------------------------------
         */

        /**
         *  Means of this variable is the data that will accepted
         *  is an array that have ordering like this 
         *  ex: 
         *      -   Chapter 03      ==>     Chapter 01
         *      -   Chapter 02      ==>     Chapter 02
         *      -   Chapter 01      ==>     Chapter 03
         *  So the array that we accepted is gnna be like this
         *  we dont want this happens so we deciede to reverse
         *  the array back to normal positions
         */
		$reverse_data   =   true;
		// var_dump($comic_url, $chapter_selector);
        $chapters_data  =   $this->neoscraper->get_chapter_url($comic_url, $chapter_selector, $comic_slug, $reverse_data);

        // Cek Jika Tidak Ada Chapter Maka Redirect
        if (empty($chapters_data)) {
            $this->session->set_flashdata(
                "message",
                _flashMessage("danger", "Target Selector Chapter ['$chapter_selector'] Tidak Di Temukan")
            );
            // redirect("comic/addChapterViaScrapingWebsite/$comic_slug");
            // exit;
        }


        $result = false;
        foreach ($chapters_data as $chapter) {
            $data = [];
            $data["chapter_name"]       =   $chapter["chapter_name"];
            $data["chapter_url"]        =   $chapter["chapter_url"];
            $data["chapter_slug"]       =   $chapter["chapter_slug"];
            $data["image_selector"]     =   $image_selector;
            // var_dump($chapter);
            $result_data = $this->neoscraper->scrape_chapter_images($data, $comic_slug, $save_option, true);

            if (!$result_data["is_success"]) {
                $data_kl["_kl_msg"]  =  $result_data["chapter_message"];
                $data_kl["_kl_type"] =  0;
                continue;
            };

            // Data For save_scraped_data()
            $data = []; // Buat Array Data Kembali Kosong
            $data["comic_slug"]           = $comic_slug;
            $data["chapter_slug"]         = $chapter["chapter_slug"];
            $data["chapter_name"]         = $chapter["chapter_name"];
            $data["chapter_images"]       = $result_data["chapter_images"];
            $data["chapter_url"]          = $chapter["chapter_url"];
            $data["comic_url"]            = $comic_url;
            $result = $this->chapter_model->save_scraped_data($data);
        }

        /**
         *  --------------------------------------
         *           End OF Chapter Part
         *  --------------------------------------
         */

        if (!$result) {
            $data_kl["_kl_msg"]           =  " Gagal Menambahkan Chapter Baru! : at-" . $comic_slug;
            $data_kl["_kl_type"]          =  0;
        }
        $data_kl["_kl_name"]          =  $comic_slug;
        $data_kl["_kl_komik_name"]    =  $comic_slug;
        $data_kl["_kl_part"]          = "comic";
        $data_kl["_kl_date"]          =  time();
        $this->comic_model->addNewLogs($data_kl);
        $this->session->set_flashdata("message", _flashMessage("success", "Berhasil Menambahkan Chapter Baru"));


        // Aktifkan Komik
        $this->comic_model->updateComicStatus($comic_slug, 1);
        return;
    }

    /**
     * Fungsi Untuk Medapatkan Data Komik
     */
    private function _get_scraping_data(String $comic_url, String $save_option, String $web_source): array
    {

        /**
         * -------------------------------------------------
         *           Scrape Comic Data And Save It
         * -------------------------------------------------
         */

        //  TODO : kumpulkan data komik dari sumber scrape comic
        // Init neoscraper Library
        $this->load->library("neoscraper");
        $this->load->helper("scraper");

        $html           = new simple_html_dom();
        $comic_data     = $this->neoscraper->get_comic_data($comic_url);
        $comic_data     = $html->load($comic_data);

        // $comic = collect_comic_info($comic_data, $comic_url, $web_source);
        if ($web_source == "komikcast.com") {
            $comic = _komikcast_com($comic_data, $comic_url, $web_source);
        } elseif ($web_source == "kiryuu.co") {
            $comic = _kiryuu_co($comic_data, $comic_url, $web_source);
        } else {
            $comic = _mock($comic_data, $comic_url, $web_source);
        }

        if ($comic["comic_name"] == "") return [];

        if ($save_option == 1 || $save_option == "1") {
            // $comic["comic_web_source"]    =   "default";
        }
        return $comic;
    }

    /**
     * Fungsi untuk mendapatkan blobs file
     * lalu menyimpanya di localstorage
     * 
     *  @param string   [cover_url]
     *  @param string   [comic_slug]
     */
    private function _save_comic_cover(string $cover_url, string $comic_slug): string
    {
        $blobs_cover    = $this->neoscraper->get_blob_data($cover_url);
        return $this->comic_model->save_comic_cover($cover_url, $comic_slug, $blobs_cover);
    }





    /**
     * ------------------------------------------------------------------------
     *                      Page Controller To Add New Comic
     * ------------------------------------------------------------------------
     */
    public function new_comic() // [-Done-]
    {
        /**
         * ------------------------------------ 
         *           Data To Display 
         * ------------------------------------
         */
        $data["title"]      = "Add Comic";                              // - Title Dart Setiap Halaman
        $data["user_data"]  = _getUserData();                           // - User Data Diambil Dari HelperFunction _getUserData()
        $data["genres"]     = $this->comic_model->get_all_genre();      // - Ambil Genre Dari Comic Model di Methode get_all_genre()
        $data["sources"]    = $this->comic_model->get_scrape_target();  // - Ambil Sumber Scrape Data Dari Web Yang Tersedia
        for ($i = 1.0; $i <= 10; $i += 0.5) $data["rating"][] = $i;     // - Buat Rating Comic Darti 1-10
        $this->_validate_form();                                        // - Validasi Inputan User

        /**
         *  ------------------------------------
         *              ==Check==
         *  ------------------------------------
         *  `if` Validasi Gagal Tampilkan Halaman Comic
         *  `else` Jalankan Logic Tambah Comic
         */
        if (!$this->form_validation->run()) _templates($data, "comic_page/comic_add_view.php");
        else {
            $comic          = [];                                   // - Init Empty Array
            $comic          = $this->_collect_comic_data();           // - Ambil Semua Data Comic Dari Inputan User
            $comic["comic_cover"] = $this->comic_model->uploadImage();      // - Jalankan Fungsi Upload Image Dan Kembalikan Nama Imagenya
            $result         = $this->comic_model->new_comic($comic);   // - Jalankan Fungsi Tambah Comic Dengan Parameter Data Dari Comic

            /**
             * ------------------------------------
             *        Result Configuration
             * ------------------------------------
             */
            $config_res["result"]                   = $result;
            $config_res["success_flash_type"]       = "success";
            $config_res["success_flash_msg"]        = "Successfuly Add New Comic";
            $config_res["redirect_success"]         = "comic";
            $config_res["failed_flash_type"]        = "danger";
            $config_res["failed_flash_msg"]         = "Failed To Add New Comic";
            $config_res["redirect_failed"]          = "new_comic";
            $this->_display_result($config_res);
        }
    }

    /**
     * ------------------------------------------------------------------------
     *                  Page Controller To Update New Comic
     * ------------------------------------------------------------------------
     */
    public function update_comic($comic_slug = null)
    {
        if (empty($comic_slug))  $this->_redirect("comic");

        /**
         * ------------------------------------
         *          Data To Display 
         * ------------------------------------
         */
        $data["title"]          = "Comic " . $comic_slug;            // - Title Dart Setiap Halaman
        $data["user_data"]      = _getUserData();                                           // - User Data Diambil Dari HelperFunction _getUserData()
        $data["genres"]         = $this->comic_model->get_all_genre();                      // - Dapatkan Genre Dari Comic Model di Methode get_all_genre()
        $data["sources"]        = $this->comic_model->get_scrape_target();                  // - Dapatkan Sumber Scrape Data Dari Web Yang Tersedia
        $data["comic"]          = $this->comic_model->get_all_comic($comic_slug);           // - Dapatkan Data Comic Dengan Nama `$comic_slug`
        $comic_slug             = $data["comic"]["comic_slug"];                             // - Dapatkan `komik_name` Dari Hasil Quert getComicByKomikName()
        $data["chapters"]       = $this->chapter_model->get_comic_chapter($comic_slug);     // - Dapatkan Chapter Comic Berdasarkan `komik_name`
        $data["total_chapters"] = $this->chapter_model->get_total_chapter($comic_slug);     // - Dapatkan Jumlah Total Chapter Dari Comic 
        $this->_validate_form();                                                            // - Validasi Inputan User

        /**
         *  ------------------------------------
         *              ==Check==
         *  ------------------------------------
         *  `if` Validasi Gagal Tampilkan Halaman Comic Detail
         *  `else` Jalankan Logic Update Comic
         */
        if (!$this->form_validation->run()) _templates($data, "comic_page/comic_detail_view.php");
        else {
            /**
             * ------------------   update_comic()   ------------------
             * 
             *  @param array    Data Comic Didapatkan Query Ke Database
             *  @param array    Data Comic Genre
             * 
             */
            $result = $this->comic_model->update_comic($comic_slug, $this->_collect_genre());

            /**
             * ------------------------------------
             *          Result Configuration
             * ------------------------------------
             */
            $config_res["result"]                   = $result;
            $config_res["success_flash_type"]       = "success";
            $config_res["success_flash_msg"]        = "Successfuly Update " . $data["comic"]["comic_name"];
            $config_res["redirect_success"]         = "comic/update-comic/" . $comic_slug;
            $config_res["failed_flash_type"]        = "danger";
            $config_res["failed_flash_msg"]         = "Failed To Update Comic " . $data["comic"]["comic_name"];
            $config_res["redirect_failed"]          = "comic/update-comic/" . $comic_slug;
            $this->_display_result($config_res);
        }
    }

    /**
     * ------------------------------------------------------------------------
     *                   Function To Delete Choised Comic
     * ------------------------------------------------------------------------
     */
    public function delete_comic($comic_slug, $cover = "null")
    {
        $name           =   _filterParams($comic_slug);
        $cover          =   $this->comic_model->get_comic_cover($comic_slug);
        $result         =   $this->comic_model->delete_comic($comic_slug, $cover);
        /**
         * ------------------------------------
         *          Result Configuration
         * ------------------------------------
         */
        $config_res["result"]                   = $result;
        $config_res["success_flash_type"]       = "success";
        $config_res["success_flash_msg"]        = "Successfuly Delete Comic $name";
        $config_res["redirect_success"]         = "comic/";
        $config_res["failed_flash_type"]        = "danger";
        $config_res["failed_flash_msg"]         = "Failed To Delete Comic $name";
        $config_res["redirect_failed"]          = "comic/";
        $this->_display_result($config_res);
    }

    ///----------------------------------------------------------PRIVATE-FUNCTION----------------------------------------------------------


    /**
     * ------------------ _display_result() ------------------
     * 
     *  - Berfungsi Unutk Menampilkan Pesan Dan Memindahkan 
     *  Ke Halaman Yang Sesuai Dengan Kondisi.
     *  @param  Assoc_Array  Data Configuration
     * 
     * ------------------ Example ------------------
     * 
     *  - $config_res["result"]                   = `boolean`;
     *  - $config_res["success_flash_type"]       = "success";
     *  - $config_res["success_flash_msg"]        = "Successfuly add new comic";
     *  - $config_res["redirect_success"]         = "comic";
     *  - $config_res["failed_flash_type"]        = "danger"
     *  - $config_res["failed_flash_msg"]         = "Failed To Add New Comic"
     *  - $config_res["redirect_failed"]          = "new_comic";
     */
    private function _display_result($config_res = [])
    {
        if ($config_res["result"]) {
            $this->session->set_flashdata("message", _flashMessage($config_res["success_flash_type"], $config_res["success_flash_msg"]));
            $this->_redirect($config_res["redirect_success"]);
        } else {
            $this->session->set_flashdata("message", _flashMessage($config_res["failed_flash_type"], $config_res["failed_flash_msg"]));
            $this->_redirect($config_res["redirect_failed"]);
        }
    }

    /**
     * ------------------ _redirect() ------------------
     * 
     *  - Berfungsi Untuk Mengalihkan Atau Memindahkan 
     *  User Ke Halaman Yang Di Inginkan.
     *  @param  String  Location
     */
    private function _redirect(String $location)
    {
        redirect($location);
        exit;
    }

    //--Form-Validation
    private function _validate_form()
    {
        // $regex = "/^[\w\d\s ,.'\"]+$/";
        // $regexAuthor = "/^[\w\d\s ,.'\"]*\$/";
        // $regexWithNum = "/^[A-Za-z-0-9 \s \"\' -.\/. ?!#$%&()]*\$/";
        $this->_check_genre();
        $this->form_validation->set_rules("comic_name", "Name", "trim|required|min_length[3]|max_length[100]");
        $this->form_validation->set_rules("comic_author", "Author", "trim|required|min_length[3]|max_length[50]");
        $this->form_validation->set_rules("comic_desc", "Description", "trim|required|min_length[3]");
        $this->form_validation->set_rules("comic_active", "Is Active", "trim|required|numeric");
        $this->form_validation->set_rules("comic_web_source", "Comic Source", "trim|required");
        $this->form_validation->set_rules("comic_status", "Status", "trim|required|numeric");
        $this->form_validation->set_rules("comic_storage", "Storage", "trim|required|numeric");
        $this->form_validation->set_rules("image-placeholder", "Image", "required", [
            "required" => "<strong>Please!</strong> Choose Image Cover"
        ]);
        $this->form_validation->set_rules("comic_rating", "Rating", "trim|required|numeric|less_than_equal_to[10.00]|decimal", [
            "required" => "<strong>Please!</strong> Give Rating Of This Comic"
        ]);
        $this->form_validation->set_rules("comic_type", "Type", "trim|required|min_length[3]", [
            "required" => "<strong>Please!</strong> Choose Comic Type"
        ]);
        $this->form_validation->set_rules("comic_genre", "Is Genre Exist", "trim|required", [
            "required" => "<strong>Please!</strong> Choose Min One Genre"
        ]);
    }

    //--Fungsi untuk mengumpulkan user input data
    private function _collect_comic_data()
    {
        $comic["comic_cover"]        = $_FILES["comic_cover"]["name"];
        $comic["comic_name"]        = $this->input->post("comic_name", true);
        $comic["comic_desc"]        = $this->input->post("comic_desc", true);
        $comic["comic_author"]      = $this->input->post("comic_author", true);
        $comic["comic_status"]      = $this->input->post("comic_status", true);
        $comic["comic_rating"]      = $this->input->post("comic_rating", true);
        $comic["comic_posted_by"]   = _getUserData()["email"];
        $comic["comic_genre"]       = $this->_collect_genre();
        //--From "exam name" To "exam-name"
        $arr = explode(" ", $this->input->post("comic_name", true));
        $comic["comic_slug"]        = strtolower(join("-", $arr));
        $comic["comic_type"]        = $this->input->post("comic_type", true);
        $comic["comic_web_source"]  = $this->input->post("comic_web_source", true);
        $comic["comic_storage"]     = $this->input->post("comic_storage", true);
        $comic["comic_date"]        = time();
        $comic["comic_update"]      = time();
        $comic["comic_project"]     = $this->input->post("comic_project", true);
        $comic["comic_18plus"]      = $this->input->post("comic_18plus", true);
        $comic["comic_active"]      = $this->input->post("comic_active", true);
        $comic["comic_chapters"]   = $this->chapter_model->get_total_chapter($comic["comic_slug"]);
        return $comic;
    }

    //--Fungsi untuk mengecek jika genre ada
    private function _check_genre()
    {
        $check_genre = $this->_collect_genre();
        //--Check Jika genre tidak ada maka nilai baliknya adalah false, sebalikanya.
        return ($check_genre == "" || empty($check_genre)) ? false : true;
    }

    //--Ambil Semua Genre Yang Terpilih
    private function _collect_genre()
    {
        $comic_genre = [];
        $genres = $this->comic_model->get_all_genre();
        foreach ($genres as $genre) {
            if (!empty($_POST[strtolower($genre["name"])])) {
                $_POST["comic_genre"] = "true";
                $comic_genre[] = $this->input->post(strtolower($genre["name"]), true);
            }
        }
        return join(",", $comic_genre);
    }

    ///----------------------------------------------------------AJAX-FUNCTION----------------------------------------------------------
    public function ajax_get_target($name)
    {
        $result = $this->db->get_where("_komik_ws", ["ws_komik_name" => $name])->row_array();
        echo json_encode($result);
    }
}


















    // public function ajaxGetTableLimitBy($limit = "", $offset = 0, $current_page = 1)
    // {
    //     $this->db->order_by("name", "ASC");
    //     $comics = $this->db->get("_komik", $limit, $offset)->result_array();
    //     $total_data = $this->db->count_all_results("_komik");
    //     $resp = [
    //         "status" => 1,
    //         "message" => "Success",
    //         "current_page" => $current_page,
    //         "total_pages" => ceil($total_data / $limit),
    //         "totals" => count($comics),
    //         "comics" => $comics
    //     ];
    //     echo json_encode($resp);
    // }

    // public function ajaxSearchComic($search_key = "")
    // {
    //     $search_key = _filterParams($search_key);
    //     $this->db->like("name", $search_key, 'after');
    //     $comics = $this->db->get("_komik")->result_array();
    //     $resp = [];
    //     if (empty($search_key)) {
    //         $resp = [
    //             "status" => 2,
    //             "message" => "found all",
    //             "totals" => count($comics),
    //             "comics" => $comics
    //         ];
    //     } else if (empty(!$comics)) {
    //         $resp = [
    //             "status" => 1,
    //             "message" => "found " . count($comics),
    //             "totals" => count($comics),
    //             "comics" => $comics
    //         ];
    //     } else {
    //         $resp = [
    //             "status" => 0,
    //             "message" => "found all",
    //             "totals" => count($comics),
    //             "comics" => $comics
    //         ];
    //     }
    //     echo json_encode($resp);
    // }
