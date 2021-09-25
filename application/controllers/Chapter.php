<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * -------------------------------------------------------------------------------------------------------------------------------------------
 *                                                *CONTROLLER FOR ALL Chapter PAGES AND LOGIC*
 * -------------------------------------------------------------------------------------------------------------------------------------------
 */
class Chapter extends CI_Controller
{

    // Constructor To Initialize BaseConfiguration For Comic Controller
    public function __construct()
    {
        parent::__construct();
        // Validasi Akses User
        _checkUserAccess();
        // Init CI PlugIn And Library
        $this->load->model("Chapter_page/Chapter_model", "chapter_model");  // Load Model And PrevixName is chapter_model
        $this->load->library("form_validation");                            // Load Form Validation Lib
        // Set Error Delimiter For Form Validation Lib
        $this->form_validation->set_error_delimiters('<h6 class="alert alert-danger my-0 mb-2">', '</h6>');
    }



    /**
     * ------------------------------------------------------------------------
     *                  Page Controller To Preview Chapters
     * ------------------------------------------------------------------------
     */
    public function view($slug)
    {
        $data["title"]      = "Chapter View";
        $data["user_data"]  = _getUserData();
        $data["chapter"]    = $this->chapter_model->get_chapter_slug($slug);
        $data["images"]     = explode("|", $data["chapter"]["chapter_images"]);
        if ($images = $this->input->post("images")) {
            redirect("chapter/view/$slug");
            exit;
            // $this->load->library("zipper");
            // $current_chapter = explode(" ", $this->input->post("current_chapter"));
            // $current_chapter = end($current_chapter);
            // $current_index = 1;
            // foreach ($images as $image_url) {
            //     $file_name = $this->chapter_model->format_numbers($current_index++, count($images));
            //     $config["file_name"]       = $file_name;
            //     $config["is_download"]     = true;
            //     $config["allowed_types"]   = "image";
            //     $config["allowed_ext"]     = "jpg|jpeg|png";
            //     $this->zipper->zip_files($image_url, $config);
            // }
            // $this->zipper->get_zip_file("$slug .zip");
        }
        _templates($data, "chapter_page/chapter_view.php");
    }


    /**
     * ------------------------------------------------------------------------
     *    Page Controller To Add New Chapter And Store Into Server Storage
     * ------------------------------------------------------------------------
     */
    public function add_new_chapter($comic_slug = "")
    {
        if ($comic_slug === "") {
            redirect("comic/");
            exit;
        }
        /**
         * ------------------------------------
         *          Data To Display 
         * ------------------------------------
         */
        $data["title"]          = "Add New Chapter ";                                   // - Title Dart Setiap Halaman
        $data["user_data"]      = _getUserData();                                       // - User Data Diambil Dari HelperFunction _getUserData()
        // $data["comic"]          = $this->chapter_model->get_comic($comic_slug);   // - Dapatkan Data Comic Dengan Nama `$comic_slug`
        $data["comic_slug"]     = $comic_slug;
        $data["latest_chapter"] = $this->chapter_model->get_latest_chapter($comic_slug);

        /**
         * ------------------------------------
         *     Set Form Validation Rules
         * ------------------------------------
         */
        $this->form_validation->set_rules("chapter_name", "Chapter Number", "trim|regex_match[/^[0-9 - .]+$/]");

        if (!$this->form_validation->run()) _templates($data, "chapter_page/chapter_add_view.php");
        else {
            $result = $this->chapter_model->add_new_chapter($comic_slug);
            /**
             * ------------------------------------
             *          Result Configuration
             * ------------------------------------
             */
            $config_res["result"]                   = $result;
            $config_res["success_flash_type"]       = "success";
            $config_res["success_flash_msg"]        = "Berhasil!";
            $config_res["redirect_success"]         = "chapter/add-new-chapter/$comic_slug";
            $config_res["failed_flash_type"]        = "danger";
            $config_res["failed_flash_msg"]         = $this->upload->display_errors();
            $config_res["redirect_failed"]          = "chapter/add-new-chapter/$comic_slug";
            $this->_checkResult($config_res);
        }
    }

    /**
     * ------------------------------------------------------------------------
     *                  Page Controller To Update New Chapter
     * ------------------------------------------------------------------------
     */
    public function update($comic_slug)
    {
        /**
         * ------------------------------------
         *          Data To Display 
         * ------------------------------------
         */
        $data["title"]              = "Add New Chapter";                                    // - Title Dart Setiap Halaman
        $data["user_data"]          = _getUserData();                                       // - User Data Diambil Dari HelperFunction _getUserData()
        $data["comic"]              = $this->chapter_model->get_comic($comic_slug);          // - Dapatkan Data Comic Dengan Nama `$comic_slug`
        $data["scrape_source"]      = $this->chapter_model->get_scrape_target();            // - Sumber Data Scrape

        /**
         * ------------------------------------
         *     Set Form Validation Rules
         * ------------------------------------
         */
        $this->form_validation->set_rules("comic-url", "Comic URL", "trim|required");
        $this->form_validation->set_rules("selector", "Selector", "trim|required");
        $this->form_validation->set_rules("web-saveopt", "Save Option", "trim|required");

        if (!$this->form_validation->run()) _templates($data, "chapter_page/scrape_chapter_view.php");
        else {
            if ($comic_slug === NULL || $comic_slug === "") redirect("comic");
            $this->_scrape_chapters($comic_slug);
            redirect("comic/update-comic/$comic_slug");
        }
    }

    /**
     *  this function will scrape chapter to the web target
     *  with requiring an arguments
     *  
     *  @param string   comic_slug
     *  @return void    wich mean nothings
     */
    private function _scrape_chapters(string $comic_slug)
    {
        $comic_url          =   $this->input->post("comic-url");
        $chapter_selector   =   $this->input->post("selector");
        $image_selector     =   $this->input->post("second-selector");
        $save_option        =   $this->input->post("web-saveopt");
        $this->load->library("neoscraper");
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

        /// * Update Comic date
        $this->comic_model->update_time($comic_slug);

        foreach ($chapters_data as $chapter) {
            // var_dump($chapter);
            $data = [];
            $data["chapter_name"]       =   $chapter["chapter_name"];
            $data["chapter_url"]        =   $chapter["chapter_url"];
            $data["chapter_slug"]       =   $chapter["chapter_slug"];
            $data["image_selector"]     =   $image_selector;
            $result_data = $this->neoscraper->scrape_chapter_images($data, $comic_slug, $save_option, true);
            // var_dump($result_data);
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
        // die;

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
    }


    public function delete_chapter($comic_slug, $chapterId)
    {
        ($this->chapter_model->remove_chapter($comic_slug, $chapterId)) ? $this->session->set_flashdata("message", _flashMessage("success", "data berhasil dihapus")) : $this->session->set_flashdata("message", _flashMessage("danger", "data gagal dihapus"));
        redirect("comic/update-comic/$comic_slug");
        exit;
    }
    public function ajax_delete_chapter($comic_slug, $chapterId)
    {
        ($this->chapter_model->remove_chapter($comic_slug, $chapterId)) ? $this->session->set_flashdata("message", _flashMessage("success", "data berhasil dihapus")) : $this->session->set_flashdata("message", _flashMessage("danger", "data gagal dihapus"));
    }

    public function download_selected_chapter()
    {
        $this->load->library("neoscraper");
        $checkboxes = $this->input->post("checkbox");
        $comic_slug = explode("|", $checkboxes[0]);
        redirect("comic/update-comic/" . $comic_slug[0]);
    }

    ///----------------------------------------------------------PRIVATE-FUNCTION----------------------------------------------------------


    /**
     * ------------------ _checkResult() ------------------
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
     *  - $config_res["redirect_failed"]          = "addComic";
     */
    private function _checkResult($config_res = [])
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
}


































    // /**
    //  * -------------------------------------------------
    //  *              Scrape Comic Chapter
    //  * -------------------------------------------------
    //  *      - `Merupakan` Fungsi Untuk Menyimpan Comic Chapter
    //  *      Ke Database Yang Baru Saja Di Ambil|Scrape Dari Web
    //  *  -------------`Parameter`-------------
    //  *      - URL                   =>  Comic URL
    //  *      - Chapter Selector      =>  CSS Selector To Get List Of Comic Chapter
    //  *      - Image Selector        =>  CSS Selector TO Get Chapter Images
    //  *      - Comic Name            =>  Required For Saving Chapter And As Logs Info
    //  * 
    //  *  @param  String   URL
    //  *  @param  String   Chapter Selector
    //  *  @param  String   Image Selector
    //  *  @param  String   comic_name
    //  *  @param  int      Save Option
    //  */
    // private function _srape_chapters(String $url, String $chapter_selector, String $image_selector, String $comic_slug, $save_option = 1)
    // {
    //     /**
    //      * -------------------------------------------------
    //      *       Scrape Comic Chapter Data And Save It
    //      * -------------------------------------------------
    //      */
    //     // Load neoscraper Librarie
    //     $this->load->library("neoscraper");
    //     $result = false;

    //     $is_chapter_has_false           = false;
    //     $chapters_data                  = [];
    //     $chapters_data                  = $this->neoscraper->get_chapter_url($url, $chapter_selector, $comic_slug);

    //     if (empty($chapters_data)) {
    //         $this->session->set_flashdata("message", _flashMessage("danger", "Target Selector Chapter ['$chapter_selector'] Tidak Di Temukan"));
    //         redirect("comic/addChapterViaScrapingWebsite/$comic_slug");
    //         exit;
    //     }

    //     /// * Update Comic date
    //     $this->comic_model->update_time($comic_slug);

    //     // Loop untuk mendapatkan gambar dari setiap chapter
    //     foreach ($chapters_data as $chapter_data) {
    //         $chapter_name   = $chapter_data["chapter_name"];
    //         $chapter_url    = $chapter_data["chapter_url"];
    //         //Check Jika Comic Chapter Sudah Ada
    //         if ($this->comic_model->checkChapterURL($chapter_url, $comic_slug) == 0) {

    //             // Data For get_chapter_link()
    //             $data = []; // Buat Array Data Kembali Kosong
    //             $data["image_selector"]       = $image_selector;
    //             $data["chapter_url"]          = $chapter_url;
    //             $data["chapter_name"]         = $chapter_name;
    //             $data["comic_name"]           = $comic_slug;


    //             $images_url = $this->neoscraper->get_chapter_link($data, $save_option);
    //             if ($this->neoscraper->is_has_false) $is_chapter_has_false = true; // tentang neoscraper

    //             // Data For save_scraped_data()
    //             $data = []; // Buat Array Data Kembali Kosong
    //             $data["comic_slug"]           = $comic_slug;
    //             $data["chapter_name"]         = $chapter_name;
    //             $data["chapter_images"]       = $images_url;
    //             $data["chapter_url"]          = $chapter_url;
    //             $data["comic_url"]            = $url;
    //             $result = $this->chapter_model->save_scraped_data($data);
    //         }
    //     }
    //     if (!$result) {
    //         $data_kl["_kl_msg"]           =  " Gagal Menambahkan Chapter Baru! : at-" . $comic_slug;
    //         $data_kl["_kl_type"]          =  0;
    //     } else {
    //         if ($is_chapter_has_false) {
    //             $data_kl["_kl_msg"]           =  "Terdapat Sebuah Isu Di Dalam Comic " . $comic_slug;
    //             $data_kl["_kl_type"]          =  0;
    //         } else {
    //             $data_kl["_kl_msg"]           =  "Berhasil Menambahkan Comic " . $comic_slug;
    //             $data_kl["_kl_type"]          =  1;
    //         }
    //     }
    //     $data_kl["_kl_name"]          =  $comic_slug;
    //     $data_kl["_kl_komik_name"]    =  $comic_slug;
    //     $data_kl["_kl_part"]          = "comic";
    //     $data_kl["_kl_date"]          =  time();
    //     $this->comic_model->addNewLogs($data_kl);
    //     $this->session->set_flashdata("message", _flashMessage("success", "Berhasil Menambahkan Chapter Baru"));

    //     return;
    // }