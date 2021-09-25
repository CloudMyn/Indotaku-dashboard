<?php
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;

class Neoscraper
{
    protected $ci;
    protected $http;
    private $total_chapters_current_comic = null;
    public $is_has_false = false;

    public function __construct() // [Used]
    {
        $this->ci = &get_instance();
        $this->ci->load->model("Comic_page/Comic_model", "comic_model");
        $this->http = new Client();
    }

    public function get_blob_data($url) // [Used]
    {
        try {
            $result = $this->http->request("GET", $url);
            return $result->getBody()->getContents();
        } catch (Exception $err) {
            return false;
        }
    }


    /**
     * --------------------------------------------------------------------------------------------------------
     *                                          *COMIC SCRAPE METHOD*
     * --------------------------------------------------------------------------------------------------------
     */


    /**
     * ---------------------------------
     *          get_comic_url
     * ---------------------------------
     * 
     *  Untuk mendapatkan comic url dari web targer
     *
     *  @param  String   web comic url
     *  @param  String   selector for comic
     *  @param  String   comic name
     * 
     *  @return AssocArray|boolean  it`s depends on the situation if its faild it wll return false
     */
    public function get_comic_url(String $web_url, String $comic_selector) // [Used]
    {
        $result         =   null;
        $message        =   "";
        $comics_element =   [];
        $comics_url     =   [];
        try {
            $result     =   $this->http->request("get", $web_url);
            $html       =   str_get_html($result->getBody()->getContents());
            $comics_element =   $html->find($comic_selector);
            if (empty($comics_element)) throw new Exception("Bad Request : Data Comic Dengan Selector : `$comic_selector`, Tidak Ditemukan");
        } catch (\Throwable $th) {
            $message = (empty($result)) ? "Terdapat Kesahalan ketika Melakukan Request Ke URL : $web_url" : $th->getMessage();
            $data_kl["_kl_name"]          =  $web_url;
            $data_kl["_kl_komik_name"]    =  "no available";
            $data_kl["_kl_msg"]           =  $message;
            $data_kl["_kl_part"]          = "comic";
            $data_kl["_kl_type"]          =  0;
            $data_kl["_kl_date"]          =  time();
            $this->ci->comic_model->addNewLogs($data_kl);
            return false;
        }

        // Get All Element With Attribute `href`
        // Inside Scraped Data
        foreach ($comics_element as $comic_element) {
            $comics_url[] = $comic_element->getAttribute("href");
        }
        return $comics_url;
    }

    /**
     * 
     *  get all scraped comic data
     * 
     *  @param  string  comic_url
     *  @return string|boolean if its faild than return false
     */
    public function get_comic_data(String $comic_url) // [Used]
    {
        try {
            $http = $this->http->request("GET", $comic_url);
            return $http->getBody()->getContents();
        } catch (\Throwable $th) {
            return false;
        }
    }

    // public function get_total_chapter() // [Usedless]
    // {
    //    return $this->total_chapters_current_comic;
    // }




    /**
     * -------------------------------------------------
     *              Scrape Comic Chapter
     * -------------------------------------------------
     *      - `Merupakan` Fungsi Untuk Menyimpan Comic Chapter
     *      Ke Database Yang Baru Saja Di Ambil|Scrape Dari Web
     *  -------------`Parameter`-------------
     *      - URL                   =>  Comic URL
     *      - Chapter Selector      =>  CSS Selector To Get List Of Comic Chapter
     *      - Image Selector        =>  CSS Selector TO Get Chapter Images
     *      - Comic Name            =>  Required For Saving Chapter And As Logs Info
     * 
     *  @param  String   URL
     *  @param  String   Chapter Selector
     *  @param  String   Image Selector
     *  @param  String   comic_name
     *  @param  array      Save Option
     */
    public function scrape_chapter_images(array $chapter_data, String $comic_slug, $save_option = 1)
    {

        $chapter_name       =   $chapter_data["chapter_name"];
        $chapter_url        =   $chapter_data["chapter_url"];
        $image_selector     =   $chapter_data["image_selector"];
        $chapter_slug       =   $chapter_data["chapter_slug"];

        $data_to_return["chapter_message"]  =   "Chapter : $chapter_name sudah ada";
        $data_to_return["is_success"]       =   FALSE;

        //Check Jika Comic Chapter Belum Sudah Ada
        if ($this->ci->comic_model->check_existance($chapter_slug, $comic_slug, false) == 0) {


            /// * Update Comic date
            $this->ci->comic_model->update_time($comic_slug);

            // Data For _get_chapter_images()
            $data = []; // Buat Array Data Kembali Kosong
            $data["image_selector"]       = $image_selector;
            $data["chapter_url"]          = $chapter_url;
            $data["chapter_name"]         = $chapter_name;
            $data["comic_name"]           = $comic_slug;

            $images_url = $this->_get_chapter_images($data, $save_option);
            if ($this->is_has_false)
                $data_to_return["chapter_message"] = "Peringatan! : Terdapat Sebuah Masalah Ketika Mengambil Data : related comic = $comic_slug";
            else $data_to_return["chapter_message"] = "Berhasil: Mendapatkan Data Chapter Dari Comic : $comic_slug";
            $data_to_return["chapter_images"]   =  $images_url;
            $data_to_return["is_success"]       =   TRUE;
        }
        return $data_to_return;
    }



    /**
     * ---------------------------------
     *          get_chapter_url
     * ---------------------------------
     * 
     *  Untuk Mendapatkan Chapter Url Dari
     *  Komik Yang Discrape Sekarang
     * 
     *  @param  String   comic url
     *  @param  String   selector for comic chapter
     *  @param  String   comic name
     *  @return AssocArray
     */
    public function get_chapter_url(String $url, String $selector, String $comic_slug, bool $revers_data) // [Used]
    {
        $error_message  = null;
		$html           = null;
		
        try {
            $result = $this->http->request("GET", $url);
            $result = $result->getBody()->getContents();
            $html = str_get_html($result);
        } catch (Exception $err) {
			var_dump("error");
            $error_message = "Comic " . _filterParamsByUs($comic_slug) . " Dengan ChapterURL : $url, Tidak Ditemukan";
            $data["_kl_name"]          =  $url;
            $data["_kl_komik_name"]    =  $comic_slug;
            $data["_kl_msg"]           =  $err->getCode() . " | " . $error_message;
            $data_kl["_kl_part"]       = "comic";
            $data["_kl_type"]          =  0;
            $data["_kl_date"]          =  time();
            $this->ci->comic_model->addNewLogs($data);
            return null;
        }



        $index  = 0;    // - init index of array
        $data   = [];   // - init array data
        try {
			$chapters_list                          = $html->find($selector);   // - Dapatkan Elemen Yang Diinginkan
            $this->total_chapters_current_comic     = count($chapters_list);    // - Simpan Jumlah Komik Sekarang
            if ($revers_data)
                $chapters_list =   array_reverse($chapters_list);                   // - Balik Array
            foreach ($chapters_list as $current_chapter) {
                $data[$index]["chapter_name"]   = trim($current_chapter->plaintext);
                $data[$index]["chapter_slug"]   = strtolower($comic_slug . "-" . str_replace(" ", "-", $current_chapter->plaintext));
                $data[$index++]["chapter_url"]  = $current_chapter->getAttribute("href");
            }
        } catch (\Throwable $er) {
        }

        return $data;
    }



    /**
     * Berfungsi Untuk Medapatkan Gambar Pada
     * Chapter Tertentu
     * 
     * @param      link_chapter      Required | ex: http:// or https://
     * @param      selector          Required | ex: #chapter-image.img
     * @param      chapter_name      Opt = string
     * @param      selector          Opt = boolean
     * @return     mixed
     *
     */
    private function _get_chapter_images($data, $save_option = 0) // [Used]
    {
        $selector_second    = $data["image_selector"];
        $chapter_url        = $data["chapter_url"];
        $chapter_name       = $data["chapter_name"];
        $comic_name         = $data["comic_name"];
        $error_message      = null;
        $html               = null;
        // var_dump($data);
        try {
            $result = $this->http->request("GET", $chapter_url);
            // var_dump($result);
            $result                 = $result->getBody()->getContents();
            $html                   = str_get_html($result);
        } catch (Exception $err) {
        }
        // var_dump($html);
        $images_chapter_list    = $html->find($selector_second);
        if ($images_chapter_list == null || $images_chapter_list == "") {
            $error_message = "There is no elemet has selected, with selector $selector_second";
            $data_kl["_kl_name"]          =  $chapter_name;
            $data_kl["_kl_komik_name"]    =  $comic_name;
            $data_kl["_kl_msg"]           =  $error_message;
            $data_kl["_kl_part"]          = "chapter";
            $data_kl["_kl_type"]          =  0;
            $data_kl["_kl_date"]          =  time();
            $this->ci->comic_model->addNewLogs($data_kl);
            return false;
        }

        // var_dump($images_chapter_list);
        $total_images_chapter   = count($images_chapter_list);
        $index = 0;
        // var_dump($total_images_chapter);
        $is_has_false = false;
        $chapters = "";  // Sediakan Variable Untuk Menampung Gambar Chapter
        foreach ($images_chapter_list as $image) {
            $image_element = $image->getAttribute("src");
            // die;
            // if (!$image_element) {
            //     $image_element = $image->getAttribute("data-src");
            // }

            if ($save_option == 0) {
                $image_element = $image->getAttribute("data-lazy-src");
                if (!$image_element) {
                    $image_element = $image->getAttribute("src");
                }
                $chapters .= $image_element . "|";
            }

            // var_dump($image_element);
            // Validate url
            $re = '/^(?:(?<scheme>[^:\/?#]+):)?(?:\/\/(?<authority>[^\/?#]*))?(?<path>[^?#]*\/)?(?<file>[^?#]*\.(?<extension>[Jj][Pp][Ee]?[Gg]|[Pp][Nn][Gg]|[Gg][Ii][Ff]))(?:\?(?<query>[^#]*))?(?:#(?<fragment>.*))?$/m';


            if (!preg_match_all($re, $image_element, $matches, PREG_SET_ORDER, 0)) {
                continue;
            }

            // var_dump($image_element);
            // Check Jika Chapter Di Save
            if ($save_option == 1) {
                $data["chapter_name"]   = $chapter_name;
                $data["comic_name"]     = $comic_name;
                $data["image_source"]   = $image_element;
                $data["total_images"]   = $this->_fancy_number($index++, $total_images_chapter);
                $image_path             = $this->_save_chapter_images($data);
                // if (!$image_path) $is_has_false = true;
                $chapters .= $image_path . "|";
            }
        }
        // die;

        if ($is_has_false == true) {
            $error_message = "There is some issue about requested image chapter";
            $data_kl["_kl_name"]          =  $chapter_name;
            $data_kl["_kl_komik_name"]    =  $comic_name;
            $data_kl["_kl_msg"]           =  $error_message;
            $data_kl["_kl_part"]          = "chapter";
            $data_kl["_kl_type"]          =  0;
            $data_kl["_kl_date"]          =  time();
            $this->ci->comic_model->addNewLogs($data_kl);
            $this->is_has_false = $is_has_false;
        }

        $arr_images = explode("|", $chapters);   // Pecah Url Image Chapter
        array_pop($arr_images);                  // Delete Last Index Of Array
        return implode("|", $arr_images);        // Gabungkan Array Dengan Penyambung '|'
    }

    private function _save_chapter_images($data) // [Useless]
    {
        // Required Data
        $chapter_name     = $data["chapter_name"];
        $comic_name       = $data["comic_name"];
        $image_uri        = $data["image_source"];
        $total_images     = $data["total_images"];

        $image_url = trim($image_uri);
        $blobs_file = null;

        try {
            $response = $this->http->get($image_url);
            $blobs_file = $response->getBody()->getContents();
        } catch (Exception $err) {
        }


        // Cek jika tidak ada data yang dihasilkan
        if (empty($blobs_file) || $blobs_file == null) {
            $error_message  = "Error! There was no data has obtained from request to image url : $image_url";
            $data_kl["_kl_name"]          =  $chapter_name;
            $data_kl["_kl_image_link"]    =  $image_url;
            $data_kl["_kl_komik_name"]    =  $comic_name;
            $data_kl["_kl_msg"]           =  $error_message;
            $data_kl["_kl_part"]          = "image";
            $data_kl["_kl_type"]          =  0;
            $data_kl["_kl_date"]          =  time();
            $this->ci->comic_model->addNewLogs($data_kl);
            return false;
        }

        // Get File Extention
        $arr_type = explode(".", $image_url);
        $file_ext = end($arr_type);
        // Get Index Chapter
        $chapter_name_arr = explode(" ", $chapter_name);
        $chapter_name = "Chapter-" . end($chapter_name_arr);
        // Init Path To Store
        $comic_path = "assets/image/komik-chapters/$comic_name/";
        $chapter_path = $comic_path . $chapter_name . "/";
        if (!file_exists(FCPATH . $comic_path)) mkdir(FCPATH . $comic_path);               // Check Directory Comic
        if (!file_exists(FCPATH . $chapter_path)) mkdir(FCPATH . $chapter_path);           // Check Directory Chapter
        // Init Full Path Image
        $full_path = $chapter_path . $total_images . ".jpg";
        fopen($full_path, 'w');                      // Create Image File
        file_put_contents($full_path, $blobs_file);  // Store Blobs Data In Created Image File 
        // var_dump($full_path);
        return $full_path;                           // Return Full Path To Store In Database
    }


    public function _fancy_number($current_number, $total_number = 10000)
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
}
