<?php

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        _checkUserAccess();
        $this->load->model("Api_page/Api_model", "api");
    }
    public function key($id = "")
    {
        // Search Keyword
        if ($this->input->post("submit")) {
            $data["keyword"] = $this->input->post("keyword", true);
            $this->session->set_userdata("keyword-apikey", $data["keyword"]);
        } else {
            $data["keyword"] = $this->session->userdata("keyword-apikey");
        }
        // Load
        $this->load->library("pagination");

        // Config
        $config["base_url"] = base_url("comic/index");
        $config["total_rows"] = $this->api->getLatestApiKeyQuery($data["keyword"]);
        $config["per_page"] = 6;
        $config['num_links'] = 3;

        //-Pagination Links Name Below
        $config["first_link"] = 1;
        $config["last_link"] = $config["total_rows"];

        // Init
        $this->pagination->initialize($config);

        $data["user_data"] = _getUserData();
        $data["title"] = "Key";
        $data["limit"] = $config["per_page"];
        if ($config["total_rows"] <= $config["per_page"]) {
            $data["start"] = 0;
        } else {
            $data["start"] = $this->uri->segment(3);
        }
        $data["totals_result"] = $config["total_rows"];
        $data["api_keys"] = $this->api->getApiKeyLimitBy($config["per_page"], $data["start"], $data["keyword"]);
        _templates($data, "api_page/Key_view.php");
    }

    public function addApiKey(){
        $data["user_data"] = _getUserData();
        $data["title"] = "Add Key";

        _templates($data, "api_page/Add_Key_view.php");
    }
}
