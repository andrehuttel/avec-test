<?php

class Auth_model extends CI_Model {
    private $_chave_primaria = "cod";

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function __destruct(){
        $this->db->close();
    }
}
