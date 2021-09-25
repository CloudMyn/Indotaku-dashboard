<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("form_validation");
        $this->form_validation->set_error_delimiters('<div class="text-danger p-0 m-0"><p class="m-0 p-0 ml-3">', '</p></div>');
    }
    // --View For Login Page--
    public function index()
    {
        if( !empty($this->session->userdata("user_email")) ){
            redirect("user");
        }
        // --Init-Model
        $this->load->model("Auth_page/Login_model", "login");


        $data['title'] = "Login";
        // --Form-Validation--
        $this->form_validation->set_rules("email", "Email", "required|valid_email");
        $this->form_validation->set_rules("password", "Password", "required");

        if (!$this->form_validation->run()) {
            $this->load->view("auth_page/login_view.php", $data);
        } else {
            // Check Email Account
            if ($this->login->_validateAccount("email") < 1) {
                $this->session->set_flashdata(
                    "message",
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    This Email Is Not Been Registered.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>'
                );
                redirect("auth");
                exit;
            }
            //Check If Account Has Been Activated
            if ($this->login->_validateAccount("isActive") == "0") {
                $this->session->set_flashdata(
                    "message",
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    This Email Is Not Been Activated
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>'
                );
                redirect("auth");
                exit;
            }
            //Check Password is Match
            if (!$this->login->_validateAccount("password")) {
                $this->session->set_flashdata(
                    "message",
                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Password Is Not Match!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>'
                );
                redirect("auth");
            }
            //Create A Session And Ridirect To Dasboard
            $dataSession = [
                "user_email" => $this->input->post("email"),
                "user_role" => $this->login->_getRoleId(),
            ];
            $this->session->set_userdata($dataSession);
            redirect("admin");
        }
    }
    // --View For Registration Page--
    // public function registration()
    // {
    //     if( !empty($this->session->userdata("user_email")) ){
    //         redirect("user");
    //     }
    //     //---Init-plugin---
    //     $this->load->model("Auth_page/Regist_model", "reg");


    //     //---Data-Parse
    //     $data['title'] = "Registration";


    //     // ---Form-Validation---
    //     $this->form_validation->set_rules('fullname', 'Full Name', 'trim|required|min_length[3]|max_length[30]|regex_match[/^([a-zA-Z]){3,30}$/]', [
    //         "min_length" => "Password To Short",
    //         "max_length" => "Password To Long",
    //     ]);
    //     $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[30]|regex_match[/^([a-zA-Z\-0-9 _\.])*$/]', [
    //         "min_length" => "Password To Short",
    //         "max_length" => "Password To Long",
    //     ]);
    //     $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users_account.email]', [
    //         "is_unique" => "Email Is Already Registered, Use Another Email",
    //     ]);
    //     $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[rPassword]', [
    //         "matches" => "Password Don't Match",
    //     ]);
    //     $this->form_validation->set_rules('rPassword', 'Confirm Password', 'trim|required|matches[password]');

    //     if (!$this->form_validation->run()) {
    //         $this->load->view("auth_page/regist_view.php", $data);
    //     } else {
    //         $isSuccess = $this->reg->setNewAccount();
    //         if ($isSuccess === false) {
    //             $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    //                 Failed, To Add New Account.
    //                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    //                 <span aria-hidden="true">&times;</span>
    //                 </button>
    //             </div>');
    //             redirect("auth/registration");
    //             exit;
    //         } else {
    //             $this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    //                 Successfully Craete New Account!, Check Your Email To Activate Your Account!
    //                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    //                 <span aria-hidden="true">&times;</span>
    //                 </button>
    //             </div>');
    //             redirect("auth");
    //             exit;
    //         }
    //     }
    // }
    public function logOut()
    {
        $this->session->unset_userdata("user_email");
        $this->session->unset_userdata("user_role");
        $_SESSION = [];
        $this->session->set_flashdata(
            "message",
            '<div class="alert alert-success alert-dismissible fade show" role="alert">
            You Have Been Logout
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>'
        );
        redirect("admin");
    }
}
