<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CONTROLLER Combos
 *
 * Controller para gerenciamento dos Combos
 * @author BERNS SIMÃO, Alexandre Augusto <alexandre.b.simao@gmail.com>
 * @author DALLO, Gabriel <gabriel_dalo@hotmail.com>
 * @copyright VEG Tecnologia
 * @version 1.0
 * @package Tratamentos
 */
class Combos extends CI_Controller {
    private $_layout = 'layout';
    private $_dados = array();

    public function __construct() {
        parent::__construct();

        if(!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');

        }else{
            $this->_usuario = $this->ion_auth->user()->row();
        }

        $this->load->model('Combos_model');
        $this->load->helper('Exp_regular_helper');
    }

    /**
     * Listagem de registros
     */
    public function index($offset = 0){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Combos');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $filtro = array(
                'nome' => format_pesquisa($this->input->post('nome')),
            );

            if(isset($_POST['limpar']) && $_POST['limpar'] == '1'){
                $this->session->set_userdata('filtro_pacote_combo', false);
                redirect(base_url().'tratamentos/combos');

            }else if(isset($_POST['filtrar']) && $_POST['filtrar'] == '1'){
                $this->session->set_userdata('filtro_pacote_combo', $filtro);
            }
        }

        $per_page = 20;
        $nregistros = $this->Combos_model->get_num_rows('pacote_combo', array(), $this->session->userdata('filtro_pacote_combo'));

        $config = array(
            'base_url' => base_url().'tratamentos/combos/index',
            'total_rows' => $nregistros,
            'per_page' => $per_page,
            'uri_segment' => 4,
            'num_links' => 10
        );

        $this->pagination->initialize($config);

        $this->_dados['paginacao'] = $this->pagination->create_links();
        $this->_dados['total_registros']  = $nregistros;
        $this->_dados['pacotes_combo'] = $this->Combos_model->get_all(array(), $this->session->userdata('filtro_pacote_combo'), $offset, $per_page, 'id', 'desc');
        $this->_dados['pagina'] = 'combos/index';
        $this->_dados['titulo'] = 'Combos';

        $this->load->view($this->_layout, $this->_dados);
    }

    /**
     * Registras novos combos
     */
    public function novo(){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Registrar Combos');

        $this->form_validation->set_rules('nome','Nome','trim|xss_clean|required');
        $this->form_validation->set_rules('procedimentos[]','Procedimentos','required');
        $this->form_validation->set_rules('sessoes[]','Sessões','required');

        if($this->form_validation->run()){
            $procedimentos  = $this->input->post('procedimentos');
            $sessoes = $this->input->post('sessoes');

            $pacote = array(
                'nome' => $this->input->post('nome'), 
                'desconto' => str_replace(array('.', ','), array('', '.'), $this->input->post('combo_desconto')),
            );

            $procedimentos_combo = array();

            foreach($procedimentos as $k => $p){
                $procedimentos_combo[] = array(
                    'procedimento_id' => $p,
                    'sessao' => $sessoes[$k]
                );
            }

            if($this->Combos_model->insert_pacote_combo($pacote, $procedimentos_combo)){
                $alert = array('message' => 'Combo cadastrado com sucesso.', 'return' => 'alert-success');

            }else{
                $alert = array('message' => 'Houve um erro ao inserir combo.', 'return' => 'alert-danger');
            }

            $this->session->set_flashdata('alert',$alert);
            redirect(base_url().'tratamentos/combos');

        }else{

            $this->_dados['pagina'] = 'combos/form';
            $this->_dados['titulo'] = 'Novo Combo';

            $this->_dados['procedimentos']  = $this->Combos_model->get_list_procedimento();
            $this->_dados['nome'] = array('type'=>'text','name'=>'nome','id'=>'nome','required'=>'required','class'=>'form-control');
            $this->_dados['submit'] = array('name' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit', 'value' => 'Salvar');

            $this->load->view($this->_layout, $this->_dados);
        }
    }

    /**
     * Editar dados dos combos registrados
     */
    public function editar($id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Editar Combos');

        $this->form_validation->set_rules('nome','Nome','trim|xss_clean|required');

        if($this->form_validation->run()){
            $procedimentos = $this->input->post('procedimentos');
            $sessoes = $this->input->post('sessoes');

            $pacote = array(
                'nome' => $this->input->post('nome'),
                'desconto' => str_replace(array('.', ','), array('', '.'), $this->input->post('combo_desconto')),
            );

            $procedimentos_combo = array();

            foreach($procedimentos as $k => $p){
                if($p && $sessoes[$k]){
                    $procedimentos_combo[] = array(
                        'procedimento_id' => $p,
                        'sessao' => $sessoes[$k]
                    );
                }
            }

            if($this->Combos_model->update_pacote_combo($pacote, $procedimentos_combo, $procedimentos, $id)){
                $alert = array('message' => 'Combo editado com sucesso.', 'return' => 'alert-success');
            }else{
                $alert = array('message' => 'Houve um erro ao editar combo.', 'return' => 'alert-danger');
            }

            $this->session->set_flashdata('alert',$alert);
            redirect(base_url().'tratamentos/combos/editar/'.$id);

        }else{
            $combo = $this->Combos_model->get_combo($id);

            $this->_dados['pagina'] = 'combos/form';
            $this->_dados['titulo'] = 'Editar Combo';
            $this->_dados['combo'] = $combo;
            $this->_dados['procedimentos'] = $this->Combos_model->get_list_procedimento();
            $this->_dados['nome'] = array('type'=>'text','name'=>'nome','id'=>'nome','required'=>'required','class'=>'form-control','value' => $combo->nome);
            $this->_dados['submit'] = array('name' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit', 'value' => 'Salvar');

            $this->load->view($this->_layout, $this->_dados);
        }
    }

    /**
     * Metodo para exclusão de combos registrados
     */
    public function excluir($id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Remover Combos');

        if($this->Combos_model->excluir($id)){
            $alert = array('message'=>'Combo removido com sucesso.','return'=>'alert-success');

        }else{
            $alert = array('message'=>'Erro ao remover combo. Contate o Suporte','return'=>'alert-warning');
        }

        $this->session->set_flashdata('alert',$alert);
        redirect(base_url().'tratamentos/combos');
    }

    /**
     * Retorna o procedimento em especifico
     */
    public function get_procedimento(){
        $id = $this->input->post('id'); 

        echo json_encode($this->Combos_model->get_row(array('id' => $id), 'procedimento'));
    }

    public function get_combo(){
        $id = $this->input->post('id');
        $procedimentos = $this->Combos_model->get_all_table('pacote_combo_procedimento', array('pacote_combo_id'=>$id));

        echo json_encode($procedimentos);
    }

    public function get_pacote_combo(){
        $id = $this->input->post('id');
        $pacote_combo = $this->Combos_model->get_row(array('id' => $id), 'pacote_combo');

        echo json_encode($pacote_combo);
    }
}