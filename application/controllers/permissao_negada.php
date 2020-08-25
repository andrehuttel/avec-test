<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CONTROLLER Permissao_negada
 *
 * Controller para gerenciamento da Página permissão negada
 * @author André, HUTTEL <andreluizhuttel@gmail.com>
 */
class Permissao_negada extends CI_Controller {
    private $_layout = 'layout';
    private $_dados = array();
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Permissão negada
     */
    public function index() {
        $this->_dados['titulo'] = 'Permissão Negada';
        $this->_dados['mensagem'] = 'Você não possui permissão para acessar essa página.';
        $this->_dados['pagina'] = 'permissao_negada';
        
        $this->load->view($this->_layout,$this->_dados);
    }
}
