<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'vendor/autoload.php';
/**
 * CONTROLLER Contacts
 *
 * Controller para gerenciamento do Controller Contacts
 * @author HUTTEL, André <andreluizhuttel@hotmail.com>
 * @package Contacts
 */

class Contacts extends CI_Controller {
    private $_layout = 'layout';
    private $_dados = array();

    public function __construct() {
        parent::__construct();

        if(!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');

        }else{
            $this->_usuario = $this->ion_auth->user()->row();
        }

        $this->load->helper('data');
    }

    /**
     * Responsável pela Exibição da pagina Contacts
     */
    public function index(){
        
        $this->_dados['pagina'] = 'contacts/index';
        $this->_dados['titulo'] = 'Contacts';
        $this->load->library('unirest');
        $headers = array('Accept' => 'application/json');

        $response = Unirest\Request::get('http://localhost:8000/api/contacts', $headers);
        $this->_dados['contacts'] = $response->body;
        $this->_dados['count_results'] = count($response->body);

        $this->load->view( $this->_layout, $this->_dados );
    }

    public function create(){
        $data = array(
            'nome'              => $this->input->post( 'nome' ),
            'data_nascimento'   => data2famericano($this->input->post( 'data_nascimento' )),
            'cpf'               => $this->input->post( 'cpf' ),
            'identidade'        => $this->input->post( 'identidade' ),
            'endereco'          => $this->input->post( 'endereco' ),
            'telefone'          => $this->input->post( 'telefone' ),
            'celular'           => $this->input->post( 'celular' )
        );
        
        $this->load->library('unirest');
        $headers = array('Accept' => 'application/json');

        $response = Unirest\Request::post('http://localhost:8000/api/contacts', $headers, $data);

        if($response->code == 201){
            echo json_encode(array('return' => true));
        }else{
            echo json_encode(false);
        }

    }

    public function delete( $id = 0 ){
        $this->load->library('unirest');
        $headers = array('Accept' => 'application/json');

        $response = Unirest\Request::delete('http://localhost:8000/api/contact/'.$id, $headers);
        if($response->code == 200){
            $alert = array( 'message' => 'Contact removed successfully!', 'return' => 'alert-success' );
        }else{
            $alert = array( 'message' => 'Error removing contact!', 'return' => 'alert-danger' );
        }
        $this->session->set_flashdata( 'alert', $alert );
        redirect( base_url() . 'contacts/' );

    }


}