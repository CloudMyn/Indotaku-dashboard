<?php

/**
 *  ------------------ _templates() ------------------
 *  - Fungsi Yang Menyediakan Templates Dari Setiap
 *  Component View Yang Terpisah2
 *  @param Assoc_array  data
 *  @param String       component tampilan halaman utama
 */
function _templates($data, $contentPage)
{
    $ci = get_instance();
    $ci->load->view("templates/header.php", $data);
    $ci->load->view("templates/sidebar.php", $data);
    $ci->load->view("templates/topbar.php", $data);
    $ci->load->view($contentPage, $data);
    $ci->load->view("templates/footer.php", $data);
    $ci->load->view("templates/lowerbody.php", $data);
}

//-Filter Url Parameters Dari %20 karakter
function _filterParams($param)
{
    $params = explode("%20", $param);
    $param = "";
    foreach ($params as $p) {
        $param .= " " . $p;
    }
    return trim($param);
}

/**
 *  ------------------ _filterParamsByUs() ------------------
 * 
 * - adalah fungsi untuk memfilter string yang mempunya `_`
 *  ex: `My_Name` => `My Name`
 *  @param      String  Varible String Yang Mempunya `_`
 *  @return     String  Variable String Yang Dihilankan `_`
 * 
 */
function _filterParamsByUs($param)
{
    $params = explode("-", $param);
    return implode(" ", $params);
}

function _joinParamsByUs($param)
{
    $param = explode(" ", $param);
    return strtolower(implode("_", $param));
}


/**
 *  ------------------------------------ 
 *    Dapatkan Data User Dari Session 
 *  ------------------------------------ 
 * 
 *  - Mendapatkan User Data Berdasarkan Query Terhadap
 *  Email Pengguna
 *  @return Assoc_array user_data
 */
function _getUserData()
{
    $ci = get_instance();
    $email = $ci->session->userdata("user_email");
    return $ci->db->get_where("users_account", ["email" => $email])->row_array();
}

/**
 *  ------------------ _checkUserAcces() ------------------
 * 
 * - adalah fungsi untuk mengecek dan memvalidasi 
 * halaman yang diakses oleh user apakah apakah boleh
 * diakses atau tidak.
 * 
 */
function _checkUserAccess()
{
    $ci = get_instance();                               // Init perpustakaan codeigniter
    $email = $ci->session->userdata("user_email");           // ambil email dari session login
    $role_id = $ci->session->userdata("user_role");       // ambil role_id dari session login

    // Check Jika Tidak Ada Session Maka User Akan Dilempar Ke Halaman Login
    if (empty($email)) {
        redirect("auth");
        exit;
    } else {
        //-Ambil Menu Yang Sekarang Diakses User
        $current_menu = $ci->uri->segment(1);
        // var_dump($current_menu);
        //-Query Ke Database Berdasarkan Menu Yang Diakses User, Tujuan Mendapatkan Menu_id
        $menu_id = $ci->db->get_where('user_menu', ['menu_name' => $current_menu])->row_array();
        // var_dump($menu_id);
        //-Query Ke Database Berdasarkan Menu_id Yang Diakses User
        $get_user_access = $ci->db->get_where('user_access_menu', ['menu_id' => $menu_id["menu_id"], 'role_id' => $role_id])->num_rows();
        // - Jika UserAccess Sama Dengan 0 Maka Akses Akan DiBlock
        // var_dump($get_user_access);
        // die;
        if ($get_user_access === 0) {
            redirect("block");
            exit;
        }
    }
    return true;
}

/**
 *  ------------------ _flashMessage() ------------------
 * 
 * - adalah fungsi untuk membuat tampilan alert dialog
 *  @param      String  Tipe Dari Alert Dialog
 *  @param      String  Pesan Yang Akan Ditampilkan
 *  @return     String  Alert Dialog
 * 
 */
function _flashMessage($type, $message)
{
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
            ' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button></div>';
}



function _filterString(String $delimiter, String $string)
{
    $arr    = explode($delimiter, $string);
    return end($arr);
}

function replace(String $string, String $target = " ", String $to = "-", bool $tolower = true): String
{
    $_ar = explode($target, $string);
    $n  = join($to, $_ar);
    return strtolower($n);
}
