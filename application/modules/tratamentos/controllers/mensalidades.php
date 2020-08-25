<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CONTROLLER Mensalidades
 *
 * Controller para gerenciamento de Mensalidades
 * @author DALLO, Gabriel <gabriel_dalo@hotmail.com>
 * @copyright VEG Tecnologia
 * @package Tratamentos
 * @version 1.0
 */
class Mensalidades extends CI_Controller{
    private $_layout = 'layout';
    private $_dados = array();

    public function __construct(){
        parent::__construct();

        if(!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');

        }else{
            $this->_usuario = $this->ion_auth->user()->row();
        }

        $this->load->model('Mensalidades_model');
        $this->load->helper('Exp_regular_helper');
    }

    /**
     * Listagem de registros
     */
    public function index($offset = 0){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class, $this->router->method, 'Mensalidades');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $filtro = array(
                'nome' => format_pesquisa($this->input->post('nome')),
            );

            if(isset($_POST['limpar']) && $_POST['limpar'] == '1'){
                $this->session->set_userdata('filtro_mensalidade', false);
                redirect(base_url().'tratamentos/mensalidades');

            }else if(isset($_POST['filtrar']) && $_POST['filtrar'] == '1'){
                $this->session->set_userdata('filtro_mensalidade', $filtro);
            }
        }

        $per_page = 20;
        $nregistros = $this->Mensalidades_model->get_num_rows('tratamento_mensalidade', array(), $this->session->userdata('filtro_mensalidade'));

        $config = array(
            'base_url' => base_url().'tratamentos/mensalidades/index',
            'total_rows' => $nregistros,
            'per_page' => $per_page,
            'uri_segment' => 4,
            'num_links' => 10
        );

        $this->pagination->initialize($config);

        $this->_dados['paginacao'] = $this->pagination->create_links();
        $this->_dados['total_registros']  = $nregistros;
        $this->_dados['mensalidades'] = $this->Mensalidades_model->get_all(array(), $this->session->userdata('filtro_mensalidade'), $offset, $per_page, 'id', 'desc');
        $this->_dados['pagina'] = 'mensalidades/index';
        $this->_dados['titulo'] = 'Mensalidades';

        $this->load->view($this->_layout, $this->_dados);
    }

    /**
     * Registras novas mensalidades
     */
    public function novo(){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class, $this->router->method, 'Registrar Mensalidades');

        $this->form_validation->set_rules('nome', 'Nome', 'trim|xss_clean|required');
        $this->form_validation->set_rules('valor', 'Valor', 'trim|xss_clean|required');
        $this->form_validation->set_rules('recuperar_mes', 'Quantas sessões pode recuperar no mês', 'trim|xss_clean');
        $this->form_validation->set_rules('dias_recuperar', 'Dias para recuperar uma sessão', 'trim|xss_clean');

        if($this->form_validation->run()){
            $dados = array(
                'nome' => $this->input->post("nome"),
                'valor' => str_replace(array('.', ','), array('', '.'), $this->input->post('valor')),
                'recuperar_mes' => ($this->input->post("recuperar_mes") ? $this->input->post("recuperar_mes") : null),
                'dias_recuperar' => ($this->input->post("dias_recuperar") ? $this->input->post("dias_recuperar") : null),
            );

            if($this->Mensalidades_model->incluir($dados)){
                $alert = array('message' => 'Mensalidade registrada com sucesso.', 'return' => 'alert-success');

            }else{
                $alert = array('message' => 'Houve um erro ao inserir mensalidade, entre em contato com o suporte.', 'return' => 'alert-danger');
            }

            $this->session->set_flashdata('alert',$alert);
            redirect(base_url().'tratamentos/mensalidades');

        }else{
            $this->_dados['pagina'] = 'mensalidades/form';
            $this->_dados['titulo'] = 'Nova Mensalidade';

            $this->_dados['nome'] = array('type' => 'text', 'name' => 'nome', 'id' => 'nome', 'required' => 'required', 'class' => 'form-control');
            $this->_dados['valor'] = array('type' => 'text', 'name' => 'valor', 'id' => 'valor', 'required' => 'required', 'class'=>'form-control money');
            $this->_dados['recuperar_mes'] = array('type' => 'text', 'name' => 'recuperar_mes', 'id' => 'recuperar_mes', 'class' => 'form-control numero');
            $this->_dados['dias_recuperar'] = array('type' => 'text', 'name' => 'dias_recuperar', 'id' => 'dias_recuperar', 'class' => 'form-control numero');

            $this->_dados['submit'] = array('name' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit', 'value' => 'Salvar');

            $this->load->view($this->_layout, $this->_dados);
        }
    }

    /**
     * Editar dados da mensalidade já registrada
     */
    public function editar($id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class, $this->router->method, 'Editar Mensalidades');

        $this->form_validation->set_rules('nome', 'Nome', 'trim|xss_clean|required');
        $this->form_validation->set_rules('valor', 'Valor', 'trim|xss_clean|required');
        $this->form_validation->set_rules('recuperar_mes', 'Quantas sessões pode recuperar no mês', 'trim|xss_clean');
        $this->form_validation->set_rules('dias_recuperar', 'Dias para recuperar uma sessão', 'trim|xss_clean');

        if($this->form_validation->run()){
            $dados = array(
                'nome' => $this->input->post("nome"),
                'valor' => str_replace(array('.', ','), array('', '.'), $this->input->post('valor')),
                'recuperar_mes' => ($this->input->post("recuperar_mes") ? $this->input->post("recuperar_mes") : null),
                'dias_recuperar' => ($this->input->post("dias_recuperar") ? $this->input->post("dias_recuperar") : null),
            );

            if($this->Mensalidades_model->atualizar($id, $dados)){
                $alert = array('message' => 'Mensalidade editada com sucesso.', 'return' => 'alert-success');

            }else{
                $alert = array('message' => 'Houve um erro ao editar mensalidade. Entre em contato com o suporte técnico', 'return' => 'alert-danger');
            }

            $this->session->set_flashdata('alert',$alert);
            redirect(base_url().'tratamentos/mensalidades');

        }else{
            $mensalidade = $this->Mensalidades_model->get_row(array('id' => $id), 'tratamento_mensalidade');

            $this->_dados['pagina'] = 'mensalidades/form';
            $this->_dados['titulo'] = 'Editar Mensalidade';

            $this->_dados['nome'] = array('type' => 'text', 'name' => 'nome', 'id' => 'nome', 'required' => 'required', 'class' => 'form-control', 'value' => $mensalidade->nome);
            $this->_dados['valor'] = array('type' => 'text', 'name' => 'valor', 'id' => 'valor', 'required' => 'required', 'class'=>'form-control money', 'value' => number_format($mensalidade->valor, 2,",","."));
            $this->_dados['recuperar_mes'] = array('type' => 'text', 'name' => 'recuperar_mes', 'id' => 'recuperar_mes', 'class' => 'form-control numero', 'value' => $mensalidade->recuperar_mes);
            $this->_dados['dias_recuperar'] = array('type' => 'text', 'name' => 'dias_recuperar', 'id' => 'dias_recuperar', 'class' => 'form-control numero', 'value' => $mensalidade->dias_recuperar);

            $this->_dados['submit'] = array('name' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit', 'value' => 'Salvar');

            $this->load->view($this->_layout, $this->_dados);
        }
    }

    /**
     * Metodo para exclusão de mensalidades registradas
     */
    public function excluir($id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class, $this->router->method, 'Remover Mensalidades');

        $check = $this->Mensalidades_model->check_row( $id );

        if( $check > 0 ){
            $alert = array( 'message' => 'Não é possivel excluir registro. Mensalidade relacionada com um ou mais tratamentos!', 'return' => 'alert-danger' );
        } else {
            //Removendo as comissões relacionadas nos profissionais
            $this->Mensalidades_model->remove($id, 'tratamento_mensalidade_comissao', 'tratamento_mensalidade_id');

            if($this->Mensalidades_model->remove($id, 'tratamento_mensalidade')){
                $alert = array('message' => 'Mensalidade removida com sucesso.', 'return' => 'alert-success');

            }else{
                $alert = array('message' => 'Erro ao remover mensalidade. Contate o Suporte', 'return' => 'alert-warning');
            }
        }

        $this->session->set_flashdata('alert',$alert);
        redirect(base_url().'tratamentos/mensalidades');
    }
}