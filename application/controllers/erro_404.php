<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CONTROLLER Erro_404
 *
 * Controller para gerenciamento da Página 404
 * @author André, HUTTEL <andreluizhuttel@gmail.com>
 */
class Erro_404 extends CI_Controller {
    private $_layout = 'layout';
    private $_dados = array();
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Erro 404
     */
    public function index() {
        $this->_dados['titulo'] = 'Página não encontrada';
        $this->_dados['mensagem'] = 'A página solicitada não foi encontrada!';
        $this->_dados['pagina'] = '404';

        $this->load->view($this->_layout,$this->_dados);
    }
}
