<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CONTROLLER Tratamentos
 *
 * Controller para gerenciamento dos Tratamentos
 * @author BERNS SIMÃO, Alexandre Augusto <alexandre.b.simao@gmail.com>
 * @copyright VEG Tecnologia
 * @version 1.0
 * @package Tratamentos
 */

class Tratamentos extends CI_Controller {
    private $_layout = 'layout';
    private $_dados = array();

    public function __construct() {
        parent::__construct();

        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        } else {
            $this->_usuario = $this->ion_auth->user()->row();
        }

        $this->load->model('Tratamentos_model');
        $this->load->helper('Exp_regular_helper');
        $this->load->library('Log_financeiro');
    }

    public function index( $offset = 0 ){
        $per_page = 20;

        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Tratamentos');

        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

            $filtro = array(
                'pc.nome'               => format_pesquisa($this->input->post( 'nome' )),
                't.especialidade_id'    => $this->input->post( 'especialidade_id' ),
                't.profissional_id'     => $this->input->post( 'profissional_id' ),
                'pc.matricula'          => format_pesquisa($this->input->post( 'matricula' )),
                't.convenio_id'         => $this->input->post( 'convenio_id' ),
                't.num_guia'            => format_pesquisa($this->input->post( 'num_guia' )),
                't.paciente_id'         => $this->input->post( 'id_paciente_filtro' )
                );

            if( isset($_POST['limpar']) && $_POST['limpar'] == '1' ){
                $this->session->set_userdata( 'filtro_tratamento', false );
                redirect( base_url() . 'tratamentos' );
            }else if( isset($_POST['filtrar']) && $_POST['filtrar'] == '1' ){
                $this->session->set_userdata( 'filtro_tratamento', $filtro );
            }

        }
        
        $this->_dados['pagina']     = 'tratamentos/index';
        $this->_dados['titulo']     = 'Tratamentos';

        $this->_dados['tratamentos']    = $this->Tratamentos_model->get_all( array(), $this->session->userdata('filtro_tratamento'), $offset, $per_page, 't.id', 'desc' );

        $this->_dados['profissionais']  = $this->Tratamentos_model->get_all_table( 'profissional', array( 'status' => 1 ), 0, 0, 'nome', 'asc' );
        $this->_dados['especialidades'] = $this->Tratamentos_model->get_all_table( 'especialidade', array('deleted' => 0), 0, 0, 'nome', 'asc' );
        $this->_dados['convenio']       = $this->Tratamentos_model->get_all_table( 'convenio', array('deleted' => '0'), 0, 0, 'nome', 'asc' );

        $count_results = $this->Tratamentos_model->get_num_rows( 'tratamento', array(), $this->session->userdata('filtro_tratamento') );

         $config = array(
            'base_url'      => base_url().'tratamentos/index',
            'total_rows'    => $count_results,
            'per_page'      => $per_page,
            'uri_segment'   => 3,
            'num_links'     => 10
            );

        $this->pagination->initialize($config);

        $this->_dados['paginacao'] = $this->pagination->create_links();
        $this->_dados['count_results'] = $count_results;

        $this->load->view( $this->_layout, $this->_dados );
    }

    /**
     * Novo tratamento
     */
    public function novo( $id_agenda = null ){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Novo Tratamento');
        
        $this->form_validation->set_rules('label_paciente', 'Paciente', '');
        $this->form_validation->set_rules('paciente', 'Paciente', 'required|xss_clean');
        $this->form_validation->set_rules('profissional', 'Profissional', 'required|xss_clean');
        $this->form_validation->set_rules('especialidade', 'Especialidade', 'required|xss_clean');
        $this->form_validation->set_rules('necessidade', 'Necessidade', '');
        $this->form_validation->set_rules('num_guia', 'Número da Guia', '');
        $this->form_validation->set_rules('cid', 'CID', '');
        $this->form_validation->set_rules('sessoes_fisioterapia', 'Sessões', '');

        if($this->input->post('categoria') == 5) {
            $this->form_validation->set_rules('procedimento[]', 'Procedimentos', 'required|xss_clean');
        }

        $dados_agenda = null;

        if($id_agenda){
            $dados_agenda = $this->Tratamentos_model->get_dados_agenda($id_agenda);
        }

        if( $this->form_validation->run() ){
            $get_paciente = $this->Tratamentos_model->get_by_id_table( 'paciente', array( 'id' => $this->input->post('paciente') ) );
            $agenda = $this->Tratamentos_model->get_by_id_table( 'agenda', array( 'tipo_agendamento_id' => 2, 'paciente_id' => $this->input->post('paciente'), 'profissional_id' => $this->input->post('profissional'), 'data_inicio' => date('Y-m-d') ) );

            $paciente           = $this->input->post('paciente') ? $this->input->post('paciente') : NULL;
            $convenio_id        = $this->input->post('convenio') ? $this->input->post('convenio') : NULL;
            $categoria          = $this->input->post('categoria') ? $this->input->post('categoria') : NULL;
            $necessidade        = $this->input->post('necessidade') ? $this->input->post('necessidade') : NULL;
            $especialidade      = $this->input->post('especialidade') ? $this->input->post('especialidade') : NULL;
            $profissional       = $this->input->post('profissional') ? $this->input->post('profissional') : NULL;
            $procedimento       = $this->input->post('procedimento') ? $this->input->post('procedimento') : NULL;
            $sessoes            = $this->input->post('sessoes_fisioterapia') ? $this->input->post('sessoes_fisioterapia') : 1;
            $sessoes_retorno = $this->input->post('sessoes_retorno') ? $this->input->post('sessoes_retorno') : 0;
            $tipo_pagamento     = $this->input->post('tipo_pagamento') ? $this->input->post('tipo_pagamento') : NULL;
            $forma_pagamento    = $this->input->post('forma_pagamento');
            $data_tiss_inicio = $this->input->post('data_tiss_inicio') ? $this->input->post('data_tiss_inicio') : NULL;
            $hora_tiss_inicio = $this->input->post('hora_tiss_inicio') ? $this->input->post('hora_tiss_inicio') : NULL;
            $hora_tiss_fim = $this->input->post('hora_tiss_fim') ? $this->input->post('hora_tiss_fim') : NULL;
            $dias_semana_tiss = $this->input->post('dias_semana_tiss') ? $this->input->post('dias_semana_tiss') : NULL;
            $num_guia           = $this->input->post('num_guia') ? $this->input->post('num_guia') : NULL;
            $cid           = $this->input->post('cid') ? $this->input->post('cid') : NULL;
            $medico             = $this->input->post('medico') ? $this->input->post('medico') : NULL;
            $lancar_fluxo       = $this->input->post('lancar_fluxo') ? $this->input->post('lancar_fluxo') : NULL;
            $plano              = $this->input->post('plano') ? $this->input->post('plano') : NULL;
            $autorizacao        = $this->input->post('autorizacao');
            $data_autorizacao   = data2famericano($this->input->post('data_autorizacao'));
            $senha              = $this->input->post('senha');
            $valor              = $this->input->post('valor') ? str_replace(array('.', ','), array('', '.'), $this->input->post('valor')) : 0;
            $total              = $this->input->post('total') ? str_replace(array('.', ','), array('', '.'), $this->input->post('total')) : 0;
            $desconto           = $this->input->post('desconto') ? str_replace(array('.', ','), array('', '.'), $this->input->post('desconto')) : 0;
            $desconto_porcento           = $this->input->post('desconto_porcento') ? str_replace(array('.', ','), array('', '.'), $this->input->post('desconto_porcento')) : 0;
            $acrescimo           = $this->input->post('acrescimo') ? str_replace(array('.', ','), array('', '.'), $this->input->post('acrescimo')) : 0;

            $vencimento_autorizacao = $this->input->post('vencimento_autorizacao') ? data2famericano($this->input->post('vencimento_autorizacao')) : null;
            $mensalidade = $this->input->post('mensalidade') ? $this->input->post('mensalidade') : NULL;

            $limite_fj_periodo = $this->input->post('limite_fj_periodo') ? $this->input->post('limite_fj_periodo') : NULL;
            $periodo_bloqueio = $this->input->post('periodo_bloqueio') ? $this->input->post('periodo_bloqueio') : NULL;

            $data_inicio        = data2famericano($this->input->post('data_inicio'));
            $sessoes_procedimento   = $this->input->post('sessoes_procedimento');
            $total_procedimento     = $this->input->post('total_procedimento');
            $comissao_prof = $this->input->post('comissao_prof');

            //Configuração para verificar se o tipo de tratamento convenio terá opção de lançar no fluxo de caixa
            $convenio_financeiro = $this->Tratamentos_model->get_row(array('id' => 42), 'configuracao');
            
            $data_limite = null;
            if($this->input->post('data_limite')){
                $dl = explode('/', $this->input->post('data_limite'));
                $data_limite = $dl[2] . '-' . $dl[1] . '-' . $dl[0];
            }

            $valor_total = $valor;

            if($categoria == 2 || $categoria == 1 || $categoria == 4){
                $valor_total = $valor * $sessoes;
            }

            if($categoria == 1){
                $valor = $valor * $sessoes;
            }

            if($categoria == 5){
                $valor_total = ($total + $desconto) - $acrescimo;
                $valor = ($total + $desconto) - $acrescimo;
            }

            $data_vencimento = null;
            if($this->input->post('data_vencimento')){
                $d = explode('/', $this->input->post('data_vencimento'));
                $data_vencimento = $d[2] . '-' . $d[1] . '-' . $d[0];
            }

            $plano_saude = $this->Tratamentos_model->get_by_id_table('plano', array('id'=>$get_paciente->plano_id));

            //Caso mensalidade, concatenar os dias da semana para inserção no BD
            $dias_semana_concat = '';

            if($categoria == 4 && !$convenio_financeiro->valor){
                $matricula  = $get_paciente->matricula;
                $plano_id   = $get_paciente->plano_id ? $get_paciente->plano_id : NULL;

            }else{
                $plano_convenio_id = $get_paciente->plano_id ? $get_paciente->plano_id : null;

                $matricula = ($categoria == 4 ? $get_paciente->matricula : null);
                $plano_id = ($categoria == 4 ? $plano_convenio_id : 1);
                $tp = $this->Tratamentos_model->get_by_id_table('tipo_pagamento', array('id'=>$tipo_pagamento));

                $controle = array();

                if($this->input->post('controle') == 'sim' && $this->input->post('categoria') == 3){
                    $controle = array(
                        'dias'           => $this->input->post('dias_semana'),
                        'data_inicio'    => $this->input->post('data_inicio'),
                        'plano'          => (int) $this->input->post('plano'),
                    );

                    $dias_semana = $this->input->post('dias_semana');

                    foreach($dias_semana as $key => $dia){
                        $dias_semana_concat .= $dia.',';
                    }

                    if($dias_semana_concat){
                        $count = strlen($dias_semana_concat) - 1;
                        $dias_semana_concat = substr($dias_semana_concat, 0, $count);
                    }
                }

                $fp = $this->formasPagamento($categoria, $valor_total, $desconto, $acrescimo, $plano, $data_vencimento, $controle);
            }

            //Somando os retorno no total de sessoes
            $sessoes_tot = $sessoes; 

            if($sessoes_retorno){
                $sessoes_tot = $sessoes + $sessoes_retorno;
            }
           
            $dados = array(
                'data'                  => date('Y-m-d'),
                'hora'                  => date('H:i:s'),
                'paciente_id'           => $paciente,
                'matricula'             => $matricula,
                'plano_id'              => $plano_id,
                'convenio_id'           => $convenio_id,
                'sessoes_totais' => $sessoes_tot,
                'sessoes_restantes' => $sessoes_tot,
                'sessoes_retorno' => $sessoes_retorno,
                'profissional_id'       => $profissional,
                'diagnostico'           => isset($agenda->diagnostico) ? $agenda->diagnostico : null,
                'especialidade_id'      => $especialidade,
                'necessidade_id'        => $necessidade ? $necessidade : null,
                'categoria_tratamento_id'  => $categoria ? $categoria : null,
                'medico_id'             => $medico,
                'num_guia'              => $num_guia,
                'cid'                   => $cid,
                'valor'                 => $valor,
                'desconto'              => $desconto,
                'desconto_porcento'     => $desconto_porcento,
                'acrescimo'             => $acrescimo,
                'plano'                 => $plano,
                'observacao'            => $this->input->post('observacoes_fluxo'),
                'data_limite'           => $data_limite,
                'data_inicio'           => $data_inicio,
                'dias_semana'           => ($dias_semana_concat ? $dias_semana_concat : null),
                'autorizacao'           => $autorizacao,
                'data_autorizacao'      => $data_autorizacao,
                'senha'                 => $senha,
                'vencimento_autorizacao' => $vencimento_autorizacao,
                'tratamento_mensalidade_id' => $mensalidade,

                'limite_fj_periodo' => $limite_fj_periodo,
                'periodo_bloqueio' => $periodo_bloqueio,

                'data_vencimento'   => $this->input->post('data_vencimento') ? data2famericano($this->input->post('data_vencimento')) : null,
                'tipo_pagamento_id' => $tipo_pagamento ? $tipo_pagamento : null,
                'forma_pagamento'   => $forma_pagamento !== '' && isset($fp[$forma_pagamento]['forma']) ? $fp[$forma_pagamento]['forma'] : null,
                'clinica_id'        => $this->input->post('clinica') ? $this->input->post('clinica') : null,
                'tipo_plano_conta_id' => $this->input->post('tipo_plano_conta') ? $this->input->post('tipo_plano_conta') : null,
                'plano_conta_id'    => $this->input->post('plano_conta') ? $this->input->post('plano_conta') : null,
                'conta_id'          => $this->input->post('conta') ? $this->input->post('conta') : null,
                'cartao_credito'    => $this->input->post('cartao_padrao') ? $this->input->post('cartao_padrao') : null,
                'lancamento'        => $this->input->post('fluxo_caixa') ? $this->input->post('fluxo_caixa') : 0,
                'comissao' => $this->input->post('comissao_prof_mensalidade') ? $this->input->post('comissao_prof_mensalidade') : 0,
                'usuario_id'        => $this->session->userdata('user_id'),

                //Campos TISS
                'tiss_carater_id' => $this->input->post('carater') ? $this->input->post('carater') : null,
                'tiss_tipo_atendimento_id' => $this->input->post('tipo_atendimento') ? $this->input->post('tipo_atendimento') : null,
                'tiss_acidente_id' => $this->input->post('acidente') ? $this->input->post('acidente') : null,
                'tiss_indicacao_clinica' => $this->input->post('indicacao_clinica') ? $this->input->post('indicacao_clinica') : null,
            );

            $insert_tratamento = $this->Tratamentos_model->insert( $dados );

            if( $insert_tratamento ){
                $id = $insert_tratamento;

                if($procedimento){
                    foreach ($procedimento as $key => $proced) {
                        $procedimentos_tratamento[] = array(
                            'tratamento_id'     => $id,
                            'procedimento_id'   => $proced
                            );
                    }

                    $this->Tratamentos_model->insert_table_batch('tratamento_has_procedimento', $procedimentos_tratamento);
                }

                //Realizar lançamento no fluxo de caixa
                $id_lancamento = null; 

                if($this->input->post('fluxo_caixa')){
                    $lancamento_dados = $this->realizar_lancamento_caixa($fp[$forma_pagamento]['forma'], $id);

                    $id_lancamento = $lancamento_dados['id_lancamento'];
                    $msg_liquidar = $lancamento_dados['msg_liquidar'];
                
                //Criando a tabela de mensalidade caso não seja lançado no financeiro
                }else if($this->input->post('controle') == 'sim' && $this->input->post('categoria') == 3){
                    $dias_m = $this->input->post('dias_semana');
                    $data_inicio_m = $this->input->post('data_inicio');
                    $plano_m = (int) $this->input->post('plano');
                    $competencia_m = data2famericano($data_inicio_m);
                    $data_vencimento_m = ($this->input->post('data_vencimento') ? data2famericano($this->input->post('data_vencimento')) : null);

                    $controle_m = $this->controleSessoes($dias_m, $data_inicio_m, $plano_m);
                    $mensalidade_m = array();

                    foreach($controle_m['meses'] as $mes_m => $sessoes_m){
                        $mensalidade_m[] = array(
                            'vencimento' => $data_vencimento_m,
                            'competencia' => $competencia_m,
                            'sessoes' => $sessoes_m,
                            'valor' => $valor - $desconto,
                            'tratamento_id' => $id
                        );

                        //Competencia
                        $data_m = DateTime::createFromFormat('Y-m-d', $competencia_m);
                        $data_m->add(new DateInterval('P1M'));
                        $competencia_m = $data_m->format('Y-m-d');

                        //Vencimento
                        if($data_vencimento_m){
                            $data_v = DateTime::createFromFormat('Y-m-d', $data_vencimento_m);
                            $data_v->add(new DateInterval('P1M'));
                            $data_vencimento_m = $data_v->format('Y-m-d');
                        }
                    }

                    if(isset($mensalidade_m) && $mensalidade_m){
                        $this->Tratamentos_model->insert_table_batch('mensalidade', $mensalidade_m);
                    }
                }

                $evolucoes = array();

                for($s = 1; $s <= $sessoes_tot; $s++){
                    $data = '0000-00-00';
                    $agenda_id = null;

                    //Se possuir id agenda por paramento, relacionar a primeira sessao com o agendamento
                    if($id_agenda && $s == 1){
                        $agenda = $this->db->where('id', $id_agenda)->get('agenda')->row();

                        //Modificar agendamento de consulta para tratamento
                        if($agenda->reserva_agenda != null){
                            $this->Tratamentos_model->update_table('agenda', array('tratamento_id' => $id), array('reserva_agenda' => $agenda->reserva_agenda, 'data_inicio >='=> $agenda->data_inicio, 'paciente_id' => $agenda->paciente_id));

                        }else{
                            $this->Tratamentos_model->update_table('agenda', array('tratamento_id' => $id), array('id' => $agenda->id));
                        }

                        //Caso o agendamento redirect seja do tipo 20 Reserva de agenda
                        if($agenda->tipo_agendamento_id == 20){
                            $this->Tratamentos_model->update_table('agenda', array('tipo_agendamento_id' => 1, 'especialidade_id' => $especialidade), array('id' => $agenda->id));
                        }

                        $data = $agenda->data_inicio;
                        $agenda_id = $agenda->id;
                    }

                    $evolucoes[] = array(
                        'sessao'            => $s,
                        'profissional_id'   => $profissional,
                        'tratamento_id'     => $id,
                        'status'            => '',
                        'data'              => $data,
                        'agenda_id'         => $agenda_id
                    );
                }

                $this->Tratamentos_model->insertEvolucoes($evolucoes, $procedimento);

                //Inserção das datas TISS
                if($data_tiss_inicio && $dias_semana_tiss){
                    $total_sessoes = 0;

                    for($i = 0; $total_sessoes < $sessoes_tot; $i++){
                        $verifica_data = new DateTime(data2famericano($data_tiss_inicio));
                        $verifica_data->add(new DateInterval('P'.$i.'D'));

                        if(in_array($verifica_data->format('w'), $dias_semana_tiss)) {
                            $total_sessoes++;
                            $evolucao_tratamento = $this->Tratamentos_model->get_row(array('tratamento_id' => $id, 'sessao' => $total_sessoes), 'evolucao');

                            $info = array(
                                'data' => $verifica_data->format('Y-m-d'),
                                'evolucao_id' => $evolucao_tratamento->id,
                                'hora_inicio' => $hora_tiss_inicio,
                                'hora_fim' => $hora_tiss_fim,
                            );

                            $this->Tratamentos_model->insert_table('tiss_evolucao', $info);
                        }
                    }
                }


                if($categoria == 5){
                    $combo = array();
                    foreach ($sessoes_procedimento as $id_procedimento => $sessao) {
                        $combo[] = array(
                            'procedimento_id' => $id_procedimento,
                            'tratamento_id'   => $id,
                            'sessoes_totais'  => $sessao,
                            'sessoes_restantes' => $sessao,
                            'valor'           => str_replace(array('.',','),array('','.'),$total_procedimento[$id_procedimento])
                            );
                    }
                    if($combo){
                        $this->Tratamentos_model->insert_table_batch('combo', $combo);
                    }
                }

                //Caso mensalidade, verificar divisão de comissão para mais de um profissional
                if($categoria == 3){
                    $tratamento_profissional = array();

                    if($comissao_prof){
                        foreach($comissao_prof as $id_profissional => $comissao_pr){
                            $tratamento_profissional[] = array(
                                'profissional_id' => $id_profissional,
                                'tratamento_id' => $id,
                                'porcentagem' => str_replace(array('.',','), array('','.'), $comissao_pr)
                            );
                        }
                    }

                    if($tratamento_profissional){
                        $this->Tratamentos_model->insert_table_batch('tratamento_mensalidade_profissional', $tratamento_profissional);
                    }
                }

                //Inserindo no historico de credito do paciente
                $credito_utilizado = ($this->input->post('credito_utilizado') ? str_replace(array('.',','), array('','.'), $this->input->post('credito_utilizado')) : null);

                if($credito_utilizado){
                    //Consultar o último histórico de crédito para pegar o saldo atual
                    $historico_credito = $this->Tratamentos_model->get_row(array('paciente_id' => $paciente), 'paciente_historico_credito', 'id', 'desc');

                    if($historico_credito){
                        $dados_paciente_credito = array(
                            'usuario_id' => $this->session->userdata('user_id'),
                            'data_hora' => date('Y-m-d H:i:s'),
                            'paciente_id' => $paciente,
                            'tratamento_id' => $id, 
                            'valor' => $credito_utilizado,
                            'tipo' => 1, 
                            'saldo' => $historico_credito->saldo - $credito_utilizado
                        );

                        $this->Tratamentos_model->insert_table('paciente_historico_credito', $dados_paciente_credito);
                    }
                }

                //Efetuando a transação com a ZOOP :)
                $mensagem_transacao = null;

                if($this->input->post('cartao_padrao') == 99 && $this->input->post('agilpay_vendedor_id') && $this->input->post('fluxo_caixa')){
                    $zoop_return = $this->emitir_transacao_cartao($id_lancamento, $id);

                    //Montar mensagem da transacao
                    if($zoop_return['status'] == 3){
                        $mensagem_transacao = '
                        <div style="color: #a94442">
                            <br><br>
                            <strong><span class="glyphicon glyphicon-exclamation-sign"></span> Transação:</strong><br>
                            A transação não foi efetuada. Edite o tratamento e tente novamente.<br>
                            '.($zoop_return['mensagem'] ? 'Motivo: '.$zoop_return['mensagem'] : '').'
                        </div>';
                    
                    }else if($zoop_return['status'] == 2){
                        $mensagem_transacao = '
                        <div>
                            <br><br>
                            <strong><span class="glyphicon glyphicon-ok-sign"></span> Transação:</strong><br>
                            A transação foi efetuada com sucesso.<br>
                        </div>';

                    }else if($zoop_return['status'] == 1){
                        $mensagem_transacao = '
                        <div style="color: #8a6d3b">
                            <br><br>
                            <strong><span class="glyphicon glyphicon-exclamation-sign"></span> Transação:</strong><br>
                            A transação encontra-se pendente.<br>
                        </div>';
                    }
                }

                if(isset($msg_liquidar) && $msg_liquidar){
                    $alert = array('message' => 'O lançamento foi efetuado, porém o liquidar automático do cartão não pode ser efetuado corretamente, o caixa já foi fechado. '.$msg_liquidar, 'return' => 'alert-danger');
                
                }else{
                    $alert = array( 'message' => 'Tratamento inserido com sucesso.', 'return' => 'alert-success' );
                }

                //Caso tenha transação concatenar a mensagem1
                if($mensagem_transacao){
                    $alert['message'] = $alert['message'].$mensagem_transacao;
                }

                if($dados_agenda){
                    $redirect = base_url().'agenda/index/'.$dados_agenda->clinica_id.'/P/'.$dados_agenda->profissional_id.'/'.$dados_agenda->data_inicio.'/'.$id_agenda;
                    
                }else{
                    $redirect = base_url() . 'tratamentos';
                }

            }else{
                $alert = array( 'message' => 'Houve um erro ao inserir o tratamento.', 'return' => 'alert-danger' );
                $redirect = base_url() . 'tratamentos';
            }

            //Verificar se a solicitação está vindo da agenda ou diretamente do tratamento
            if(!$this->input->post('form_tratamento_agenda')){
                $this->session->set_flashdata( 'alert', $alert );

                if($this->input->post('fluxo_caixa') && $this->input->post('pagar_lancamento') && $id_lancamento){
                    redirect('financeiro/caixa/fluxo/'.$id_lancamento);

                }else{
                    redirect($redirect);
                }

            }else{
                $lancamento_redirect = ($this->input->post('fluxo_caixa') && $this->input->post('pagar_lancamento') && $id_lancamento ? $id_lancamento : 0);

                echo json_encode(array('status' => true, 'id' => $id, 'id_lancamento' => $lancamento_redirect, 'mensagem_transacao' => ($mensagem_transacao ? $alert['message'] : null)));
            }

        }else{
            $this->_dados['pagina']                     = 'tratamentos/form';
            $this->_dados['titulo']                     = 'Novo Tratamento';

            $this->_dados['convenios']['']              = 'Selecione...';
            $this->_dados['especialidades']['']         = 'Selecione...';
            $this->_dados['medicos']['']                = 'Selecione...';
            $this->_dados['profissionais']['']          = 'Selecione...';
            $this->_dados['necessidades']['']           = 'Selecione...';
            $this->_dados['categorias']['']             = 'Selecione...';
            $this->_dados['categorias_conta']['']       = 'Selecione...';
            $this->_dados['subcategorias']['']          = 'Selecione...';
            $this->_dados['contas']['']                 = 'Selecione...';
            $this->_dados['combos']['']                 = 'Selecione...';
            $this->_dados['mensalidades'][''] = 'Selecione...';

            $convenio   = $this->Tratamentos_model->get_all_table( 'convenio', array('deleted' => '0'), 0, 0, 'nome', 'asc' );
            foreach( $convenio as $c ) $this->_dados['convenios'][$c->id] = $c->nome;

            $profissionais          = $this->Tratamentos_model->get_all_table( 'profissional', array('status' => 1), 0, 0, 'nome', 'asc' );
            foreach ($profissionais as $p) $this->_dados['profissionais'][$p->id] = $p->nome;

            $especialidades         = $this->Tratamentos_model->get_all_table( 'especialidade', array('deleted' => 0), 0, 0, 'nome', 'asc' );
            foreach ($especialidades as $t) $this->_dados['especialidades'][$t->id] = $t->nome;

            $medicos                = $this->Tratamentos_model->get_all_table( 'medico', array(), 0, 0, 'nome', 'asc' );
            foreach ($medicos as $m) $this->_dados['medicos'][$m->id] = $m->nome;

            $necessidades           = $this->Tratamentos_model->get_all_table( 'necessidade', array(), 0, 0, 'nome', 'asc' );
            foreach ($necessidades as $p) $this->_dados['necessidades'][$p->id] = $p->nome;

            $categorias           = $this->Tratamentos_model->get_all_table( 'categoria_tratamento', array('id !=' => 1) );
            foreach ($categorias as $c) $this->_dados['categorias'][$c->id] = $c->nome;

            // $procedimentos           = $this->Tratamentos_model->get_all_table( 'procedimento', array('convenio_id'=>1, 'deleted' => 0), 0, 0, 'status', 'desc' );

            // foreach ($procedimentos as $c) {
            //     $status_proc = '';

            //     if($c->deleted == 1){
            //         $status_proc = ' (Removido)';

            //     }elseif($c->status == 0){
            //         $status_proc = ' (Desativado)';
            //     }

            //     $this->_dados['procedimentos'][$c->id] = $c->codigo . ' - ' . $c->procedimento.$status_proc;
            // }

            $tipos_pagamento           = $this->Tratamentos_model->get_all_table( 'tipo_pagamento' );
            foreach ($tipos_pagamento as $c) $this->_dados['tipos_pagamento'][$c->id] = $c->nome;

            $categoria           = $this->Tratamentos_model->get_all_table( 'tipo_plano_conta', array('id !=' => 3, 'deleted' => 0) );
            foreach ($categoria as $c) $this->_dados['categorias_conta'][$c->id] = $c->nome;

            $conta           = $this->Tratamentos_model->get_all_table( 'conta' );
            foreach ($conta as $c) $this->_dados['contas'][$c->id] = $c->nome;

            // $this->_dados['planos'] = array(
            //     '' => 'Selecione...',
            //     1 => '1 Mês',
            //     2 => '2 Meses',
            //     3 => '3 Meses',
            //     4 => '4 Meses',
            //     5 => '5 Meses',
            //     6 => '6 Meses',
            //     7 => '7 Meses',
            //     8 => '8 Meses',
            //     9 => '9 Meses',
            //     10 => '10 Meses',
            //     11 => '11 Meses',
            //     12 => '12 Meses'
            // );

            // $this->_dados['dias_semana'] = array(
            //     1 => 'Segunda-Feira',
            //     2 => 'Terça-Feira',
            //     3 => 'Quarta-Feira',
            //     4 => 'Quinta-Feira',
            //     5 => 'Sexta-Feira',
            //     6 => 'Sábado',
            //     );

            $this->_dados['convenios_modal']          = $this->Tratamentos_model->get_all_table( 'convenio', array( 'deleted' => '0' ) );
            $this->_dados['planos_modal']             = $this->Tratamentos_model->get_all_table( 'plano', array( 'id' => 1 ) );

            $combos = $this->Tratamentos_model->get_all_table('pacote_combo', array(), 0, 0, 'nome', 'asc');
            foreach ($combos as $c) $this->_dados['combos'][$c->id] = $c->nome;

            $mensalidades = $this->Tratamentos_model->get_all_table('tratamento_mensalidade', array(), 0, 0, 'nome', 'asc');

            foreach($mensalidades as $m){
                $valor_m = number_format($m->valor, 2, ',', '.');

                $this->_dados['mensalidades'][$m->id.'"valor="'.$valor_m.'" recuperar-mes="'.$m->recuperar_mes.'" dias-recuperar="'.$m->dias_recuperar.'" '] = $m->nome.' (R$ '.$valor_m.')';
            }

            // $this->_dados['data_inicio']        = array( 'name' => 'data_inicio', 'class' => 'form-control date', 'id' => 'data_inicio', 'value' => set_value('data_inicio') );
            $this->_dados['paciente']           = array( 'name' => 'label_paciente', 'class' => 'form-control pull-left', 'style' => 'width: 92%; border-bottom-right-radius: 0px;border-top-right-radius: 0px;', 'id' => 'busca_nome', 'status' => '1', 'required' => 'required', 'placeholder' => 'Digite o nome e selecione abaixo...', 'value' => set_value('label_paciente',  isset($dados_agenda) ? $dados_agenda->paciente : null), 'status' => '1' );
            $this->_dados['id_paciente']        = array( 'type' => 'hidden', 'name' => 'paciente', 'class' => 'pacientes', 'id' => 'pacientes', 'required' => 'required', 'value' => set_value('paciente', isset($dados_agenda) ? $dados_agenda->id_paciente : null) );
            // $this->_dados['num_guia']           = array( 'name' => 'num_guia', 'class' => 'form-control', 'id' => 'num_guia', 'value' => set_value('num_guia') );
            $this->_dados['cid']           = array( 'name' => 'cid', 'class' => 'form-control', 'id' => 'cid', 'value' => set_value('cid') );
            $this->_dados['convenio']           = array( 'name' => 'convenio', 'class' => 'form-control', 'id' => 'convenio', 'readonly'=>'readonly', 'value' => set_value('convenio', isset($dados_agenda) ? $dados_agenda->convenio : null) );
            // $this->_dados['sessoes_fisioterapia']= array( 'name' => 'sessoes_fisioterapia', 'class' => 'form-control', 'id' => 'sessoes_fisioterapia', 'maxlength' => '3', 'value' => set_value('sessoes_fisioterapia') );

            // $this->_dados['valor']              = array( 'name' => 'valor', 'id' => 'valor', 'class' => 'form-control money', 'value' => set_value('valor') );
            // $this->_dados['desconto']           = array( 'name' => 'desconto', 'id' => 'desconto', 'class' => 'form-control money', 'value' => set_value('desconto') );            
            // $this->_dados['acrescimo']           = array( 'name' => 'acrescimo', 'id' => 'acrescimo', 'class' => 'form-control money', 'value' => set_value('acrescimo') );            
            // $this->_dados['data_autorizacao']   = array( 'name' => 'data_autorizacao', 'id' => 'data_autorizacao', 'class' => 'form-control data', 'value' => set_value('data_autorizacao') );
            // $this->_dados['senha']              = array( 'name' => 'senha', 'id' => 'senha', 'class' => 'form-control', 'value' => set_value('senha') );
            // $this->_dados['vencimento_autorizacao']   = array( 'name' => 'vencimento_autorizacao', 'id' => 'vencimento_autorizacao', 'class' => 'form-control data', 'value' => set_value('vencimento_autorizacao') );

            $this->_dados['submit']             = array( 'name' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit', 'value' => 'Salvar' );

            //Gerenciamento de clinicas
            $this->controle_clinicas();
           
            $this->load->view($this->_layout, $this->_dados);
        }
    }

    /**
     * Opção de realizar um lançamento no fluxo de caixa
     */
    private function realizar_lancamento_caixa($parcelas, $id_tratamento, $data_competencia = null){
        $id_cartao = $this->input->post('cartao_padrao') ? $this->input->post('cartao_padrao') : null;
        $msg_liquidar = '';

        $dados_lancamento = array(
            'observacoes' => $this->input->post('observacoes_fluxo'),
            'tipo' => 0,
            'competencia' => ($data_competencia ? $data_competencia : date('Y-m-d')),
            'parcelas' => $parcelas,
            'plano_conta_id' => $this->input->post('plano_conta'),
            'contabil' => 'S',
            'tipo_pagamento' => $this->input->post('tipo_pagamento'),
            'paciente_id' => $this->input->post('paciente') ? $this->input->post('paciente') : null,
            'usuario_id' => $this->session->userdata('user_id'),
            'conta_id' => $this->input->post('conta'),
            'tratamento_id' => $id_tratamento, 
            'cartao_id' => $id_cartao,
            'clinica_id' => $this->input->post('clinica'),
        );

        $insert_lancamento_caixa = $this->Tratamentos_model->insert_table('lancamento_caixa', $dados_lancamento);

        if($insert_lancamento_caixa){
            $id_lancamento = $insert_lancamento_caixa;

            # Adiciona Log
            $this->log_financeiro->gerar_log_tabela('log_lancamento_caixa', $dados_lancamento, 'i', $id_lancamento);

            //Verificando as entradas
            $total_entrada = 0;

            if($this->input->post('entradas')){
                $nova_forma_dados = $this->nova_forma_pagamento($id_lancamento);

                $total_entrada = $nova_forma_dados['total_entrada'];
                $msg_liquidar = $nova_forma_dados['msg_liquidar'];
            }

            //Caso for mensalidade
            if($this->input->post('categoria') == 3){
                $dias = $this->input->post('dias_semana');
                $data_inicio = $this->input->post('data_inicio');
                $plano = (int) $this->input->post('plano');

                if($this->input->post('controle') == 'sim'){
                    $controle = $this->controleSessoes($dias, $data_inicio, $plano);
                }

                $valor = str_replace(array('.', ','), array('', '.'), $this->input->post('valor'));
                $descontos = str_replace(array('.', ','), array('', '.'), $this->input->post('desconto'));
                $acrescimos = str_replace(array('.', ','), array('', '.'), $this->input->post('acrescimo'));

                $data_vencimento = $this->input->post('data_vencimento') ? $this->input->post('data_vencimento') : date('d/m/Y');
                $data_vencimento = data2famericano($data_vencimento);

                $parcela = 1;
                $competencia = data2famericano($data_inicio);
                $list_financeiro = array();

                $valor = $valor + $acrescimos;

                $valor_parcela  = $valor;
                $valor_pagar = ($valor-$descontos);

                //Entradas
                $valor_parcela = $valor_parcela - ($total_entrada / $plano);
                $valor_pagar = $valor_pagar - ($total_entrada  / $plano);

                $valor_parcela = $valor_parcela * $plano / $parcelas;
                $valor_pagar = $valor_pagar * $plano / $parcelas;

                //Calcular valor do cartão
                $taxa_cartao = 0; 

                if($id_cartao){
                    $dados_cartao = $this->desconto_cartao($id_cartao, $valor_pagar, $parcelas, $data_vencimento);

                    //Valores ajustados
                    $valor_pagar = $dados_cartao['valor_pagar'];
                    $data_vencimento = $dados_cartao['data_vencimento'];
                    $taxa_cartao = $dados_cartao['taxa_cartao'];
                }

                $data_vencimento = explode('-', $data_vencimento);
                $dia_vencimento = $data_vencimento[2];
                $mes_vencimento = $data_vencimento[1];
                $ano_vencimento = $data_vencimento[0];

                for($i=0; $i < $parcelas; $i++){ 
                    if($mes_vencimento > 12){
                        $mes_vencimento = 1;
                        $ano_vencimento = $ano_vencimento+1;
                    }

                    //Situação fevereiro
                    if($mes_vencimento == 2 && $dia_vencimento >= 29){
                        $dia_vencimento_ant = $dia_vencimento;
                        $dia_vencimento = 29;

                        //Verificação de data válida
                        if(!checkdate($mes_vencimento, $dia_vencimento, $ano_vencimento)){
                            $dia_vencimento = 28;
                        }
                    }

                    //Verificação de data válida
                    if(!checkdate($mes_vencimento, $dia_vencimento, $ano_vencimento)){
                        $dia_vencimento = 30;
                    }

                    $data_vencimento_format = $ano_vencimento."-".$mes_vencimento."-".$dia_vencimento;

                    $data_util = $ano_vencimento."-".$mes_vencimento."-".$dia_vencimento;

                    if($id_cartao){
                        //Adicionando dias para recebimento nas datas de vencimento
                        $data_util = DateTime::createFromFormat('Y-m-d', $ano_vencimento."-".$mes_vencimento."-".$dia_vencimento);

                        //Verificar dia ultil da data
                        $data_util = $this->verificar_dia_util($data_util);
                        $data_util = $data_util->format('Y-m-d');
                    }

                    $liquidar_aut = (isset($dados_cartao['liquidar_automatico']) ? $dados_cartao['liquidar_automatico'] : null);

                    $dados_pagamento = array(
                        'data_vencimento' => $data_util,
                        'num_parcela' => $parcela,
                        'valor_parcela' => $valor_parcela,
                        'descontos' => $descontos,
                        'valor_pagar' => $liquidar_aut == 0 ? $valor_pagar : 0,
                        'taxa_cartao' => $taxa_cartao,
                        'lancamento_caixa_id' => $id_lancamento,
                    );

                    $insert_pagamento_caixa = $this->Tratamentos_model->insert_table('pagamento_caixa', $dados_pagamento);
                    $id_pagamento = $insert_pagamento_caixa;

                    # Adiciona Log
                    $this->log_financeiro->gerar_log_tabela('log_pagamento_caixa', $dados_pagamento, 'i', $id_pagamento);

                    //Caso o cartão selecionado tenha a opção de liquidar automatico
                    if($id_cartao && $liquidar_aut == 1){
                        if($this->verificar_data_fechamento_caixa($data_util, $this->input->post('conta'))){

                        $dados_historico = array(
                            'data' => $data_util,
                            'hora' => date('H:i:s'), 
                            'valor' => $dados_cartao['valor_pagar'], 
                            'pagamento_caixa_id' => $id_pagamento, 
                            'status' => 0,
                            'descontos' => $descontos + $taxa_cartao,
                            'conta_id' => $this->input->post('conta'),
                            'tipo_pagamento' => $this->input->post('tipo_pagamento'),
                            'dados_cheque' => null,
                            'usuario_id' => $this->session->userdata('user_id'),
                        );

                        $insert_historico_pagamento_caixa = $this->Tratamentos_model->insert_table('historico_pagamento_caixa', $dados_historico);
                        $id_historico_pagamento = $insert_historico_pagamento_caixa;

                        # Adiciona Log
                        $this->log_financeiro->gerar_log_tabela('log_historico_pagamento_caixa', $dados_historico, 'i', $id_historico_pagamento);

                        }else{

                        //Ajustando o valor a ser pago
                        $this->Tratamentos_model->update_table('pagamento_caixa', array('valor_pagar' => $valor_pagar), array('id' => $id_pagamento));

                        $msg_liquidar .= 'Data de Liquidação: '.format_data($data_util).' - Parcela: '.$parcela.'<br>';

                        }
                    }

                    //Situação fevereiro
                    if(isset($dia_vencimento_ant)){
                        $dia_vencimento = $dia_vencimento_ant;
                    }

                    $mes_vencimento = $mes_vencimento+1;
                    $parcela++;

                    //Retirando os dias adicionais do cartão para informar o vencimento original
                    $data_vencimento_real = DateTime::createFromFormat('Y-m-d', $data_vencimento_format);

                    if($id_cartao && isset($dados_cartao)){
                        if($dados_cartao['dias']){
                            $data_vencimento_real->sub(new DateInterval('P'.$dados_cartao['dias'].'D'));
                        }
                    }

                    $list_financeiro[] = array('vencimento' => $data_vencimento_real->format('Y-m-d'), 'valor_pagar' => $valor_pagar);
                }

                if($this->input->post('controle') == 'sim'){
                    $i_mensalidade = 0;

                    foreach ($controle['meses'] as $mes => $sessoes) {
                        $mensalidade[] = array(
                            'vencimento'        => ($list_financeiro[$i_mensalidade] ? $list_financeiro[$i_mensalidade]['vencimento'] : null),
                            'competencia'       => $competencia,
                            'sessoes'           => $sessoes,
                            'valor'             => ($list_financeiro[$i_mensalidade] ? $list_financeiro[$i_mensalidade]['valor_pagar'] : null),
                            'tratamento_id'     => $id_tratamento
                        );

                        $data = DateTime::createFromFormat('Y-m-d', $competencia);
                        $data->add(new DateInterval('P1M'));

                        $competencia = $data->format('Y-m-d');
                        $i_mensalidade++;
                    }

                    if(isset($mensalidade) && $mensalidade){
                        $this->Tratamentos_model->insert_table_batch('mensalidade', $mensalidade);
                    }
                }

            //Caso for diferente de mensalidade
            }else{
                $qnt_parcelas = (int) $parcelas;
                $parcela = 1;

                if($this->input->post('categoria') == 2 || $this->input->post('categoria') == 4){
                    $valor = str_replace(array('.', ','), array('', '.'), $this->input->post('subtotal'));
                
                }else{
                    $valor = str_replace(array('.', ','), array('', '.'), $this->input->post('valor'));
                }

                if($this->input->post('categoria') == 1){
                    $valor = $valor * (int) $this->input->post('sessoes_fisioterapia');
                }

                if($this->input->post('categoria') == 5){
                    $valor = str_replace(array('.', ','), array('', '.'), $this->input->post('subtotal'));
                }

                //Entradas
                $valor = $valor - $total_entrada;
                
                $descontos = str_replace(array('.', ','), array('', '.'), $this->input->post('desconto'));
                $acrescimos = str_replace(array('.', ','), array('', '.'), $this->input->post('acrescimo'));
                $valor = $valor + $acrescimos;

                $valor_pagar = $valor-$descontos;

                $data_vencimento = $this->input->post('data_vencimento') ? $this->input->post('data_vencimento') : date('d/m/Y');
                $data_vencimento = data2famericano($data_vencimento);

                //Calcular valor do cartão
                $taxa_cartao = 0; 
                
                if($id_cartao){
                    $dados_cartao = $this->desconto_cartao($id_cartao, $valor_pagar, $parcelas, $data_vencimento);

                    //Valores formatados
                    $valor_pagar = $dados_cartao['valor_pagar'];
                    $data_vencimento = $dados_cartao['data_vencimento'];
                    $taxa_cartao = $dados_cartao['taxa_cartao'];
                }

                //Pacote ou combo
                if($this->input->post('categoria') == 2 || $this->input->post('categoria') == 5 || $this->input->post('categoria') == 4){
                    $valor = $valor / $qnt_parcelas;
                    $descontos = $descontos / $qnt_parcelas;
                    $valor_pagar = $valor_pagar / $qnt_parcelas;
                    $taxa_cartao = $taxa_cartao / $qnt_parcelas;
                }

                $data_vencimento = explode('-', $data_vencimento);
                $dia_vencimento = $data_vencimento[2];
                $mes_vencimento = $data_vencimento[1];
                $ano_vencimento = $data_vencimento[0];

                while($parcela <= $qnt_parcelas){                    
                    if($mes_vencimento > 12){
                        $mes_vencimento = 1;
                        $ano_vencimento = $ano_vencimento+1;
                    }

                    if(strlen($mes_vencimento) == 1){
                        $mes_vencimento = '0'.$mes_vencimento;
                    }

                    //Situação fevereiro
                    if($mes_vencimento == 2 && $dia_vencimento >= 29){
                        $dia_vencimento_ant = $dia_vencimento;
                        $dia_vencimento = 29;

                        //Verificação de data válida
                        if(!checkdate($mes_vencimento, $dia_vencimento, $ano_vencimento)){
                            $dia_vencimento = 28;
                        }
                    }

                    //Verificação de data válida
                    if(!checkdate($mes_vencimento, $dia_vencimento, $ano_vencimento)){
                        $dia_vencimento = 30;
                    }

                    $data_util = $ano_vencimento."-".$mes_vencimento."-".$dia_vencimento;

                    if($id_cartao){
                        //Adicionando dias para recebimento nas datas de vencimento
                        $data_util = DateTime::createFromFormat('Y-m-d', $ano_vencimento."-".$mes_vencimento."-".$dia_vencimento);

                        //Verificar dia ultil da data
                        $data_util = $this->verificar_dia_util($data_util);
                        $data_util = $data_util->format('Y-m-d');
                    }

                    $liquidar_aut = (isset($dados_cartao['liquidar_automatico']) ? $dados_cartao['liquidar_automatico'] : null);

                    $dados_pagamento = array(
                        'data_vencimento' => $data_util,
                        'num_parcela' => $parcela,
                        'valor_parcela' => $valor,
                        'descontos' => $descontos,
                        'valor_pagar' => $liquidar_aut == 0 ? $valor_pagar : 0,
                        'taxa_cartao' => $taxa_cartao,
                        'lancamento_caixa_id' => $id_lancamento,
                    );

                    $insert_pagamento_caixa = $this->Tratamentos_model->insert_table('pagamento_caixa', $dados_pagamento);
                    $id_pagamento = $insert_pagamento_caixa;

                    # Adiciona Log
                    $this->log_financeiro->gerar_log_tabela('log_pagamento_caixa', $dados_pagamento, 'i', $id_pagamento);

                    //Caso o cartão selecionado tenha a opção de liquidar automatico
                    if($id_cartao && $liquidar_aut == 1){
                        if($this->verificar_data_fechamento_caixa($data_util, $this->input->post('conta'))){

                        $dados_historico = array(
                            'data' => $data_util,
                            'hora' => date('H:i:s'), 
                            'valor' => $valor_pagar, 
                            'pagamento_caixa_id' => $id_pagamento, 
                            'status' => 0,
                            'descontos' => $descontos + $taxa_cartao,
                            'conta_id' => $this->input->post('conta'),
                            'tipo_pagamento' => $this->input->post('tipo_pagamento'),
                            'dados_cheque' => null,
                            'usuario_id' => $this->session->userdata('user_id'),
                        );

                        $insert_historico_pagamento_caixa = $this->Tratamentos_model->insert_table('historico_pagamento_caixa', $dados_historico);
                        $id_historico_pagamento = $insert_historico_pagamento_caixa;

                        # Adiciona Log
                        $this->log_financeiro->gerar_log_tabela('log_historico_pagamento_caixa', $dados_historico, 'i', $id_historico_pagamento);

                        }else{

                        //Ajustando o valor a ser pago
                        $this->Tratamentos_model->update_table('pagamento_caixa', array('valor_pagar' => $valor_pagar), array('id' => $id_pagamento));

                        $msg_liquidar .= 'Data de Liquidação: '.format_data($data_util).' - Parcela: '.$parcela.'<br>';

                        }
                    }

                    //Situação fevereiro
                    if(isset($dia_vencimento_ant)){
                        $dia_vencimento = $dia_vencimento_ant;
                    }

                    $mes_vencimento = $mes_vencimento+1;
                    $parcela++;
                }
            }
        }

        return array('id_lancamento' => $id_lancamento, 'msg_liquidar' => $msg_liquidar);
    }

    /**
     * Adicionar novas formas de pagamento ao lancar o tratamento
     */
    private function nova_forma_pagamento($id_lancamento){
        $total_entrada = 0;
        $total_parcelas = 0;
        $msg_liquidar = '';

        if($this->input->post('entradas')){
            $valor_entrada = $this->input->post('valor_entrada'); 
            $venc_entrada = $this->input->post('venc_entrada'); 
            $parcelas_entrada = $this->input->post('parcelas_entrada'); 
            $tipo_pagamento_entrada = $this->input->post('tipo_pagamento_entrada'); 
            $cartao_padrao_entrada = $this->input->post('cartao_padrao_entrada'); 
            $num_entrada = 0;

            foreach ($valor_entrada as $valor_ent) {
                $id_cartao = ($cartao_padrao_entrada[$num_entrada] ? $cartao_padrao_entrada[$num_entrada] : null);

                $val_entrada = str_replace(array('.', ','), array('', '.'), $valor_ent);
                $total_entrada = $total_entrada + $val_entrada;

                $qnt_parcelas = (int) $parcelas_entrada[$num_entrada];
                $total_parcelas = $total_parcelas + $qnt_parcelas;

                $valor_parcela = $val_entrada / $qnt_parcelas;
                $valor_pagar = $val_entrada / $qnt_parcelas;
                $parcela = 1;
                $data_vencimento = data2famericano($venc_entrada[$num_entrada]);

                //Calcular valor do cartão
                $taxa_cartao = 0; 

                if($id_cartao){
                    $dados_cartao = $this->desconto_cartao($id_cartao, $valor_parcela, $qnt_parcelas, $data_vencimento);

                    //Valores ajustados
                    $valor_pagar = $dados_cartao['valor_pagar'];
                    $data_vencimento = $dados_cartao['data_vencimento'];
                    $taxa_cartao = $dados_cartao['taxa_cartao'];

                    //Caso o cartão selecionado tenha a opção de liquidar automatico
                    if($dados_cartao['liquidar_automatico'] == 1){
                        $valor_pagar = 0;
                    }
                }

                $data_vencimento = explode('-', $data_vencimento);
                $dia_vencimento = $data_vencimento[2];
                $mes_vencimento = $data_vencimento[1];
                $ano_vencimento = $data_vencimento[0];

                while($parcela <= $qnt_parcelas){                    
                    if($mes_vencimento > 12){
                        $mes_vencimento = 1;
                        $ano_vencimento = $ano_vencimento+1;
                    }

                    if(strlen($mes_vencimento) == 1){
                        $mes_vencimento = '0'.$mes_vencimento;
                    }

                    //Situação fevereiro
                    if($mes_vencimento == 2 && $dia_vencimento >= 29){
                        $dia_vencimento_ant = $dia_vencimento;
                        $dia_vencimento = 29;

                        //Verificação de data válida
                        if(!checkdate($mes_vencimento, $dia_vencimento, $ano_vencimento)){
                            $dia_vencimento = 28;
                        }
                    }

                    //Verificação de data válida
                    if(!checkdate($mes_vencimento, $dia_vencimento, $ano_vencimento)){
                        $dia_vencimento = 30;
                    }

                    $data_util = $ano_vencimento."-".$mes_vencimento."-".$dia_vencimento;

                    if($id_cartao){
                        //Adicionando dias para recebimento nas datas de vencimento
                        $data_util = DateTime::createFromFormat('Y-m-d', $ano_vencimento."-".$mes_vencimento."-".$dia_vencimento);

                        //Verificar dia ultil da data
                        $data_util = $this->verificar_dia_util($data_util);
                        $data_util = $data_util->format('Y-m-d');
                    }

                    $liquidar_aut = (isset($dados_cartao['liquidar_automatico']) ? $dados_cartao['liquidar_automatico'] : null);

                    $dados_pagamento = array(
                        'data_vencimento' => $data_util,
                        'num_parcela' => $parcela,
                        'valor_parcela' => $valor_parcela,
                        'descontos' => 0,
                        'valor_pagar' => $liquidar_aut == 0 ? $valor_pagar : 0,
                        'taxa_cartao' => $taxa_cartao,
                        'tipo_pagamento' => $tipo_pagamento_entrada[$num_entrada],
                        'cartao_id' => $id_cartao,
                        'lancamento_caixa_id' => $id_lancamento,
                    );

                    $insert_pagamento_caixa = $this->Tratamentos_model->insert_table('pagamento_caixa', $dados_pagamento);
                    $id_pagamento = $insert_pagamento_caixa;

                    # Adiciona Log
                    $this->log_financeiro->gerar_log_tabela('log_pagamento_caixa', $dados_pagamento, 'i', $id_pagamento);

                    //Caso o cartão selecionado tenha a opção de liquidar automatico
                    if($id_cartao && $dados_cartao['liquidar_automatico'] == 1){
                        if($this->verificar_data_fechamento_caixa($data_util, $this->input->post('conta'))){

                        $dados_historico = array(
                            'data' => $data_util,
                            'hora' => date('H:i:s'), 
                            'valor' => $dados_cartao['valor_pagar'], 
                            'pagamento_caixa_id' => $id_pagamento, 
                            'status' => 0,
                            'descontos' => $taxa_cartao,
                            'conta_id' => $this->input->post('conta'),
                            'tipo_pagamento' => $tipo_pagamento_entrada[$num_entrada],
                            'dados_cheque' => null,
                            'usuario_id' => $this->session->userdata('user_id'),
                        );

                        $insert_historico_pagamento_caixa = $this->Tratamentos_model->insert_table('historico_pagamento_caixa', $dados_historico);
                        $id_historico_pagamento = $insert_historico_pagamento_caixa;

                        # Adiciona Log
                        $this->log_financeiro->gerar_log_tabela('log_historico_pagamento_caixa', $dados_historico, 'i', $id_historico_pagamento);

                        }else{

                        //Ajustando o valor a ser pago
                        $this->Tratamentos_model->update_table('pagamento_caixa', array('valor_pagar' => $dados_cartao['valor_pagar']), array('id' => $id_pagamento));

                        $msg_liquidar .= 'Data de Liquidação: '.format_data($data_util).' - Parcela: '.$parcela.'<br>';

                        }
                    }

                    //Situação fevereiro
                    if(isset($dia_vencimento_ant)){
                        $dia_vencimento = $dia_vencimento_ant;
                    }

                    $mes_vencimento = $mes_vencimento+1;
                    $parcela++;
                }

                $num_entrada++;
            }
        }

        //Somar as parcelas secundárias com as parcelas principais
        if($total_parcelas){
            $dados_lancamento = $this->db->where('id', $id_lancamento)->get('lancamento_caixa')->row();

            $this->Tratamentos_model->update_table('lancamento_caixa', array('parcelas' => $dados_lancamento->parcelas + $total_parcelas), array('id' => $id_lancamento));
        }

        return array('total_entrada' => $total_entrada, 'msg_liquidar' => $msg_liquidar);
    }

    /**
     * Verificando desconto no cartão
     */
    private function desconto_cartao($id_cartao, $valor, $parcelas, $data_vencimento){
        $cartao = $this->db->where('id', $id_cartao)->get('cartao')->row();

        //Caso o tipo do cartão for débito, a porcentagem de vezes é o mesmo que a porcentagem a vista
        if($cartao->tipo == 'd'){
            $cartao->porcentagem_vezes = $cartao->porcentagem;
        }
        
        //Calculo da porcentagem
        $desconto_cartao = $valor * ($parcelas > 1 ? $cartao->porcentagem_vezes : $cartao->porcentagem) / 100;
        $valor = $valor - $desconto_cartao;

        //Adicionando dias para recebimento nas datas de vencimento
        $data_vencimento = DateTime::createFromFormat('Y-m-d', $data_vencimento);
        $data_vencimento->add(new DateInterval('P'.$cartao->dias_recebimento.'D'));

        //Verificar dia ultil da data
        $data_vencimento = $this->verificar_dia_util($data_vencimento);

        return array('valor_pagar' => $valor, 'data_vencimento' => $data_vencimento->format('Y-m-d'), 'taxa_cartao' => $desconto_cartao, 'liquidar_automatico' => $cartao->liquidar_automatico, 'dias' => $cartao->dias_recebimento);
    }

    /**
     * Recebe uma data, caso a mesma não seja no dia util, encontra e retorna o primeiro dia util
     */
    private function verificar_dia_util($data){
        //6 = Sabado (Encontrar primeiro dia útil)
        if($data->format('w') == 6){
            $data->add(new DateInterval('P2D'));
        
        //0 = Domingo (Encontrar primeiro dia útil)
        }else if($data->format('w') == 0){
            $data->add(new DateInterval('P1D'));
        }

        return $data;
    }

    /**
     * Efetuar transação via zoop
     */
    private function emitir_transacao_cartao($id_lancamento, $id_tratamento){
        $this->load->library('Zoop');

        //Resgate dos campos
        $agilpay_vendedor_id = $this->input->post('agilpay_vendedor_id');
        $agilpay_valor_total = $this->input->post('agilpay_valor_total') ? str_replace(array('.', ','), array('', '.'), $this->input->post('agilpay_valor_total')) : 0;
        $agilpay_parcelas = (int) $this->input->post('agilpay_parcelas');

        //Dados do Cartão
        $cartao_num1 = $this->input->post('cartao_num1');
        $cartao_num2 = $this->input->post('cartao_num2');
        $cartao_num3 = $this->input->post('cartao_num3');
        $cartao_num4 = $this->input->post('cartao_num4');
        $cartao_nome_titular = $this->input->post('cartao_nome_titular');
        $cartao_validade_mes = $this->input->post('cartao_validade_mes');
        $cartao_validade_ano = $this->input->post('cartao_validade_ano');
        $cartao_ccv = $this->input->post('cartao_ccv');

        //Concat dos numeros do cartão
        $cartao_num_concat = $cartao_num1.$cartao_num2.$cartao_num3.$cartao_num4;

        //Transformando o valor em real para centavos
        $agilpay_valor_centavos = $agilpay_valor_total * 100;

        //Verificação de registros não inseridos para transação
        if(!$cartao_num1 || !$cartao_num2 || !$cartao_num3 || !$cartao_num4 || !$cartao_nome_titular || !$cartao_validade_mes || !$cartao_validade_ano || !$cartao_ccv){
            return array('status' => 3, 'mensagem' => 'Campos incompletos do cartão');
        }

        //Validando valor zerado
        if(!$agilpay_valor_centavos){
            return array('status' => 3, 'mensagem' => 'O valor total não pode estar vazio');
        }

        //Validando numero de vezes zerados também
        if(!$agilpay_parcelas){
            return array('status' => 3, 'mensagem' => 'O número de parcelas não pode estar vazio');
        }

        $transacao = [
            'amount' => $agilpay_valor_centavos, //1
            'currency' => 'BRL', 
            'description' => 'Venda pelo Clínica Ágil Pay',
            'payment_type' => 'credit', 
            'on_behalf_of' => $agilpay_vendedor_id, //'f7e9da028800487ba82194123734a87c'
            'statement_descriptor' => 'Agilpay', 
            'source' => [
                'usage' => 'single_use',
                'amount' => $agilpay_valor_centavos, //1
                'currency' => 'BRL', 
                'type' => 'card', 
                'card' => [
                    'card_number' => $cartao_num_concat, //'5405 5667 9830 5744'
                    'holder_name' => $cartao_nome_titular, //'Teste Sobrenome'              
                    'expiration_month' => $cartao_validade_mes, //'04'
                    'expiration_year' => $cartao_validade_ano, //'2021'
                    'security_code' => $cartao_ccv, //'436'
                ], 
                'installment_plan' => [
                    'number_installments' => $agilpay_parcelas //1
                ]
            ],
        ];

        $retorno = $this->zoop->emitir_pagamento($transacao);
        $mensagem = null;
        $transacao_status = 0;

        //Verificação do status da transação
        if(isset($retorno->status)){
            if($retorno->status == 'failed'){
                $transacao_status = 3;
            
            }else if($retorno->status == 'succeeded'){
                $transacao_status = 2;

            }else if($retorno->status == 'pending'){
                $transacao_status = 1;
            }
        }

        if(isset($retorno->error)){
            if($transacao_status == 0){
                $transacao_status = 3;
            }

            if(isset($retorno->error->message_display)){
                $mensagem = $retorno->error->message_display;
            }
        }

        //Esconder os dados do cartão
        $transacao['source']['card']['card_number'] = 'xxxxxxxxxxxxxxxx';
        $transacao['source']['card']['security_code'] = 'xxx';
        $transacao['source']['card']['expiration_month'] = 'xx';
        $transacao['source']['card']['expiration_year'] = 'xxxx';

        $lancamento_caixa_transacao = array(
            'data_emissao' => date('Y-m-d'),
            'hora_emissao' => date('H:i:s'), 
            'usuario_id' => $this->session->userdata('user_id'),
            'zoop_message' => $mensagem,
            'tratamento_id' => $id_tratamento,
            'lancamento_caixa_id' => $id_lancamento,
            'zoop_transaction_id' => isset($retorno->id) ? $retorno->id : null,
            'json_requisicao' => json_encode($transacao),
            'json_resposta' => json_encode($retorno),
            'status' => $transacao_status,
            'valor_total' => $agilpay_valor_total,
            'vezes' => $agilpay_parcelas,
        );

        $insert_transacao = $this->Tratamentos_model->insert_table('lancamento_caixa_transacao', $lancamento_caixa_transacao);

        return array('status' => $transacao_status, 'mensagem' => $mensagem);
    }

    /**
     * Campos para cadastro rápido de médicos
     */
    private function form_agilpay(){
        $ano_atual = date('Y');
        $anos_list = array('' => 'Ano');

        for($i = 0; $i < 16; $i++){ 
            $anos_list[$ano_atual] = $ano_atual;

            $ano_atual++;
        }

        $agilpay_vendedor_id = $this->Tratamentos_model->get_row(array('id' => 35), 'configuracao');

        $this->_dados['agilpay_vendedor_id'] = array('type' => 'hidden', 'name' => 'agilpay_vendedor_id', 'id' => 'agil-pay-vendedor-id', 'value' => $agilpay_vendedor_id->valor);
        $this->_dados['agilpay_valor_total'] = array('type' => 'hidden', 'name' => 'agilpay_valor_total', 'id' => 'agil-pay-valor-total');
        $this->_dados['agilpay_parcelas'] = array('type' => 'hidden', 'name' => 'agilpay_parcelas', 'id' => 'agil-pay-parcelas');

        $this->_dados['cartao_num1'] = array('type' => 'num', 'name' => 'cartao_num1', 'class' => 'form-control input-cart-number', 'id' => 'card-number', 'maxlength' => '4');
        $this->_dados['cartao_num2'] = array('type' => 'num', 'name' => 'cartao_num2', 'class' => 'form-control input-cart-number', 'id' => 'card-number-1', 'maxlength' => '4');
        $this->_dados['cartao_num3'] = array('type' => 'num', 'name' => 'cartao_num3', 'class' => 'form-control input-cart-number', 'id' => 'card-number-2', 'maxlength' => '4');
        $this->_dados['cartao_num4'] = array('type' => 'num', 'name' => 'cartao_num4', 'class' => 'form-control input-cart-number', 'id' => 'card-number-3', 'maxlength' => '4');
        $this->_dados['cartao_nome_titular'] = array('type' => 'text', 'name' => 'cartao_nome_titular', 'class' => 'form-control', 'id' => 'card-holder');
        $this->_dados['cartao_validade_mes'] = array('' => 'Mês',  '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12');
        $this->_dados['cartao_validade_ano'] = $anos_list;
        $this->_dados['cartao_ccv'] = array('type' => 'text', 'name' => 'cartao_ccv', 'class' => 'form-control', 'id' => 'card-ccv', 'maxlength' => '3');
    }

    /**
     * Editar tratamentos
     */
    public function editar( $id = 0 ){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Editar Tratamento');

        $this->form_validation->set_rules('label_paciente', 'Paciente', '');
        $this->form_validation->set_rules('paciente', 'Paciente', 'required|xss_clean');
        $this->form_validation->set_rules('profissional', 'Profissional', 'required|xss_clean');
        $this->form_validation->set_rules('especialidade', 'Especialidade', 'required|xss_clean');

        if($this->input->post('categoria') == 5) {
            $this->form_validation->set_rules('procedimento[]', 'Procedimentos', 'required|xss_clean');
        }

        $tratamento = $this->Tratamentos_model->get_tratamento(array('t.id'=>$id));
        $registro = $this->Tratamentos_model->get_lancamento($id);
        $historico_pagamento = null;

        if($registro){
            $historico_pagamento  = $this->Tratamentos_model->get_historico_caixa($registro->lancamento_id);
            if($historico_pagamento){
                $this->_dados['historico_pagamento_verf'] = $historico_pagamento;
            }
        }

        //Verificação de boleto gerado no tratamento
        $boleto_gerado = $this->Tratamentos_model->get_boleto_gerado($id);

        if($boleto_gerado){
            $this->_dados['boleto_gerado'] = $boleto_gerado; 
        }

        //Verificar se já tem uma transferencia com sucesso ou pendente
        $this->_dados['lancamento_transacao_sucess'] = $this->Tratamentos_model->get_row(array('tratamento_id' => $id, 'status' => 2), 'lancamento_caixa_transacao');

        //Detallhes do financeiro
        if($registro){
            $this->load->library('parcela');
            $this->_dados['registro'] = $registro;

            if($registro){
                $this->_dados['pagamento_caixa']  = $this->Tratamentos_model->get_list_pagamento_caixa($registro->lancamento_id);
                $this->_dados['historico_pagamento']  = $this->Tratamentos_model->get_historico_caixa($registro->lancamento_id);
                $this->_dados['total_pagar']  = $this->Tratamentos_model->get_total_pagar($registro->lancamento_id);
                $this->_dados['transacoes_agilpay']  = $this->Tratamentos_model->get_transacoes_agilpay($id);
                $this->_dados['config_agilpay_vendedor_id'] = $this->Tratamentos_model->get_row(array('id' => 35), 'configuracao');
            }
        }        
            
        if( $this->form_validation->run() ){
            $get_paciente = $this->Tratamentos_model->get_by_id_table( 'paciente', array( 'id' => $this->input->post('paciente') ) );


            $agenda = $this->Tratamentos_model->get_by_id_table( 'agenda', array( 'tipo_agendamento_id' => 2, 'paciente_id' => $this->input->post('paciente'), 'profissional_id' => $this->input->post('profissional'), 'data_inicio' => date('Y-m-d') ) );
            $convenio_id        = $this->input->post('convenio') ? $this->input->post('convenio') : NULL;
            $categoria          = $this->input->post('categoria') ? $this->input->post('categoria') : null;
            $paciente           = $this->input->post('paciente');
            $necessidade        = $this->input->post('necessidade') ? $this->input->post('necessidade') : NULL;
	        $valor_pacote       = str_replace(array('.', ','), array('', '.'), $this->input->post('valor_pacote'));
            $especialidade      = $this->input->post('especialidade') ? $this->input->post('especialidade') : NULL;
            $profissional       = $this->input->post('profissional') ? $this->input->post('profissional') : NULL;
            $procedimento       = $this->input->post('procedimento') ? $this->input->post('procedimento') : NULL;
            $nome_pacote        = $this->input->post('nome_pacote') ? $this->input->post('nome_pacote') : NULL;
            $sessoes            = $this->input->post('sessoes_fisioterapia') ? $this->input->post('sessoes_fisioterapia') : 0;
            $sessoes_retorno    = $this->input->post('sessoes_retorno') ? $this->input->post('sessoes_retorno') : 0;
            $tipo_pagamento     = $this->input->post('tipo_pagamento') ? $this->input->post('tipo_pagamento') : NULL;
            $forma_pagamento    = $this->input->post('forma_pagamento');
            $num_guia           = $this->input->post('num_guia') ? $this->input->post('num_guia') : NULL;
            $cid           = $this->input->post('cid') ? $this->input->post('cid') : NULL;
            $medico             = $this->input->post('medico') ? $this->input->post('medico') : NULL;
            $lancar_fluxo       = $this->input->post('lancar_fluxo') ? $this->input->post('lancar_fluxo') : NULL;
            $plano              = $this->input->post('plano') ? $this->input->post('plano') : NULL;
            $autorizacao        = $this->input->post('autorizacao');
            $data_autorizacao   = data2famericano($this->input->post('data_autorizacao'));
            $senha              = $this->input->post('senha');
            $vencimento_autorizacao = $this->input->post('vencimento_autorizacao') ? data2famericano($this->input->post('vencimento_autorizacao')) : null;
            $mensalidade = $this->input->post('mensalidade') ? $this->input->post('mensalidade') : NULL;

            $limite_fj_periodo = $this->input->post('limite_fj_periodo') ? $this->input->post('limite_fj_periodo') : NULL;
            $periodo_bloqueio = $this->input->post('periodo_bloqueio') ? $this->input->post('periodo_bloqueio') : NULL;

            $valor              = $this->input->post('valor') ? str_replace(array('.', ','), array('', '.'), $this->input->post('valor')) : 0;
            $total              = $this->input->post('total') ? str_replace(array('.', ','), array('', '.'), $this->input->post('total')) : 0;
            $desconto           = $this->input->post('desconto') ? str_replace(array('.', ','), array('', '.'), $this->input->post('desconto')) : 0;
            $desconto_porcento  = $this->input->post('desconto_porcento') ? str_replace(array('.', ','), array('', '.'), $this->input->post('desconto_porcento')) : 0;
            $acrescimo           = $this->input->post('acrescimo') ? str_replace(array('.', ','), array('', '.'), $this->input->post('acrescimo')) : 0;
            $data_inicio        = data2famericano($this->input->post('data_inicio'));
            $data_limite = null;

            //Configuração para verificar se o tipo de tratamento convenio terá opção de lançar no fluxo de caixa
            $convenio_financeiro = $this->Tratamentos_model->get_row(array('id' => 42), 'configuracao');

            if($this->input->post('data_limite')){
                $dl = explode('/', $this->input->post('data_limite'));
                $data_limite = $dl[2] . '-' . $dl[1] . '-' . $dl[0];
            }

            $valor_total = $valor;

            if($categoria == 2 || $categoria == 1 || $categoria == 4){
                $valor_total = $valor * $sessoes;
            }

            if($categoria == 1){
                $valor = $valor * $sessoes;
            }

            if($categoria == 5){
                $valor_total = ($total + $desconto) - $acrescimo;
                $valor = ($total + $desconto) - $acrescimo;
            }

            $data_vencimento = null;

            if($this->input->post('data_vencimento')){
                $d = explode('/', $this->input->post('data_vencimento'));
                $data_vencimento = $d[2] . '-' . $d[1] . '-' . $d[0];
            }

            $plano_saude = $this->Tratamentos_model->get_by_id_table('plano', array('id'=>$get_paciente->plano_id));

            //Caso mensalidade, concatenar os dias da semana para inserção no BD
            $dias_semana_concat = '';

            if($categoria == 4 && !$convenio_financeiro->valor){
                $matricula  = $get_paciente->matricula;
                $plano_id   = $get_paciente->plano_id ? $get_paciente->plano_id : NULL;

            }else{
                $plano_convenio_id = $get_paciente->plano_id ? $get_paciente->plano_id : null;

                $matricula = ($categoria == 4 ? $get_paciente->matricula : null);
                $plano_id = ($categoria == 4 ? $plano_convenio_id : 1);
                $tp = $this->Tratamentos_model->get_by_id_table('tipo_pagamento', array('id'=>$tipo_pagamento));

                $controle = array();

                if($this->input->post('controle') == 'sim' && $this->input->post('categoria') == 3){
                    $controle = array(
                        'dias'           => $this->input->post('dias_semana'),
                        'data_inicio'    => $this->input->post('data_inicio'),
                        'plano'          => (int) $this->input->post('plano'),
                    );

                    $dias_semana = $this->input->post('dias_semana');

                    foreach($dias_semana as $key => $dia){
                        $dias_semana_concat .= $dia.',';
                    }

                    if($dias_semana_concat){
                        $count = strlen($dias_semana_concat) - 1;
                        $dias_semana_concat = substr($dias_semana_concat, 0, $count);
                    }
                }

                $fp = $this->formasPagamento($categoria, $valor_total, $desconto, $acrescimo, $plano, $data_vencimento, $controle);
            }

            $sessoes_tot = $sessoes; 

            if($sessoes_retorno){
                $sessoes_tot = $sessoes + $sessoes_retorno;
            }
            
            $last_sessao = $this->Tratamentos_model->get_max_evolucao_tratamento($id);
            $sessoes_restantes = $sessoes_tot - $last_sessao->sessao;

            if($sessoes_restantes < 0){
                $sessoes_restantes = $sessoes_restantes * -1;
            }

            $dados = array(
                'matricula'             => $get_paciente->matricula,
                'medico_id'             => $medico,
                'profissional_id'       => $profissional,
                'convenio_id'           => $convenio_id,
                'diagnostico'           => isset($agenda->diagnostico) ? $agenda->diagnostico : null,
                'especialidade_id'      => $especialidade,
                'necessidade_id'        => $necessidade,
                'num_guia'              => $num_guia,
                'cid'              => $cid,
                'sessoes_restantes'     => $sessoes_restantes,
                'sessoes_totais' => $sessoes_tot,
                'sessoes_retorno' => $sessoes_retorno,
                'valor'                 => $valor,
                'desconto'              => $desconto,
                'acrescimo'             => $acrescimo,

                'data_limite'           => $data_limite,
                'data_inicio'           => $data_inicio,
                'dias_semana'           => ($dias_semana_concat ? $dias_semana_concat : null),
                'desconto_porcento'     => $desconto_porcento,
                'autorizacao'           => $autorizacao,
                'data_autorizacao'      => $data_autorizacao,
                'senha'                 => $senha,
                'observacao'            => $this->input->post('observacoes_fluxo'),
                'vencimento_autorizacao' => $vencimento_autorizacao,
                'tratamento_mensalidade_id' => $mensalidade,

                'limite_fj_periodo' => $limite_fj_periodo,
                'periodo_bloqueio' => $periodo_bloqueio,

                'data_vencimento'   => $this->input->post('data_vencimento') ? data2famericano($this->input->post('data_vencimento')) : null,
                'tipo_pagamento_id' => $tipo_pagamento ? $tipo_pagamento : null,
                'forma_pagamento'   => $forma_pagamento !== '' && isset($fp[$forma_pagamento]['forma']) ? $fp[$forma_pagamento]['forma'] : null,
                'clinica_id'        => $this->input->post('clinica') ? $this->input->post('clinica') : null,
                'tipo_plano_conta_id' => $this->input->post('tipo_plano_conta') ? $this->input->post('tipo_plano_conta') : null,
                'plano_conta_id'    => $this->input->post('plano_conta') ? $this->input->post('plano_conta') : null,
                'conta_id'          => $this->input->post('conta') ? $this->input->post('conta') : null,
                'cartao_credito'    => $this->input->post('cartao_padrao') ? $this->input->post('cartao_padrao') : null,
                'lancamento'        => $this->input->post('fluxo_caixa') ? $this->input->post('fluxo_caixa') : 0,
                'comissao' => $this->input->post('comissao_prof_mensalidade') ? $this->input->post('comissao_prof_mensalidade') : 0,
                'categoria_tratamento_id'  => $categoria ? $categoria : null,

                'plano'          => $this->input->post('plano') ? $this->input->post('plano') : null,

                //Campos TISS
                'tiss_carater_id' => $this->input->post('carater') ? $this->input->post('carater') : null,
                'tiss_tipo_atendimento_id' => $this->input->post('tipo_atendimento') ? $this->input->post('tipo_atendimento') : null,
                'tiss_acidente_id' => $this->input->post('acidente') ? $this->input->post('acidente') : null,
                'tiss_indicacao_clinica' => $this->input->post('indicacao_clinica') ? $this->input->post('indicacao_clinica') : null,
            );

            $return = 0;

            // Efetua a edição do combo
            if($categoria == 5){

                $this->Tratamentos_model->syncCombo($procedimento, $id);

                $sessoes_procedimento   = $this->input->post('sessoes_procedimento');
                $total_procedimento     = $this->input->post('total_procedimento');

                $combo = array();
                foreach ($sessoes_procedimento as $id_procedimento => $sessao) {

                    $combo_existente = $this->Tratamentos_model->get_by_id_table('combo',array('procedimento_id'=>$id_procedimento,'tratamento_id'=>$id));

                    $sessao_restante = $sessao - ($sessao - $combo_existente->sessoes_restantes);

                    if($sessao_restante <= 0){
                        $sessao_restante = 0;
                    }

                    if(!$combo_existente){
                        $combo = array(
                            'procedimento_id'   => $id_procedimento,
                            'tratamento_id'     => $id,
                            'sessoes_totais'    => $sessao,
                            'sessoes_restantes' => $sessao,
                            'valor'             => str_replace(array('.',','),array('','.'),$total_procedimento[$id_procedimento])
                            );
                        
                        $this->Tratamentos_model->insert_table('combo', $combo);
                    }else{
                        
                        $sessoes_utilizadas = $this->Tratamentos_model->getSessoesUtilizadas(array('procedimento_id'=>$id_procedimento,'tratamento_id'=>$id));

                        $combo = array(
                            'sessoes_totais'    => $sessao,
                            'sessoes_restantes' => $sessao - $sessoes_utilizadas->sessoes_utilizadas,
                            'valor'             => str_replace(array('.',','),array('','.'),$total_procedimento[$id_procedimento])
                            );

                        $this->Tratamentos_model->update_table('combo', $combo, array('procedimento_id'=>$id_procedimento,'tratamento_id'=>$id));

                    }
                }
            }

            //Caso mensalidade, verificar divisão de comissão para mais de um profissional
            if($categoria == 3){
                $return += $this->Tratamentos_model->delete('tratamento_mensalidade_profissional', array('tratamento_id' => $id));

                $comissao_prof = $this->input->post('comissao_prof');

                if($comissao_prof){
                    foreach($comissao_prof as $id_profissional => $comissao_pr){
                        $tratamento_profissional = array(
                            'profissional_id' => $id_profissional,
                            'tratamento_id' => $id,
                            'porcentagem' => str_replace(array('.',','), array('','.'), $comissao_prof[$id_profissional]),
                        );
                        
                        $return += $this->Tratamentos_model->insert_table('tratamento_mensalidade_profissional', $tratamento_profissional);
                    }
                }
            }


            $max_sessao = $this->Tratamentos_model->get_max_sessao_tratamento($id);
            $max_evo = $this->Tratamentos_model->get_max_evo_tratamento($id);

            if($procedimento){
                $aplicar_procedimento = $this->input->post('aplicar_procedimento');
                if ($aplicar_procedimento) {

                    $this->Tratamentos_model->delete_procedimento_evolucao($id);

                } else {

                    $this->Tratamentos_model->delete_procedimento_evolucao($id, $max_evo->max_evo);

                }
                $this->Tratamentos_model->verificaProcedimentos($procedimento, $id);
            }

            if($max_sessao->max_sessao < $sessoes_tot){
                for($i=$max_sessao->max_sessao+1; $i <= $sessoes_tot; $i++){
                    $evolucoes[] = array(
                        'sessao' => $i,
                        'profissional_id' => $profissional,
                        'tratamento_id' => $id,
                        'status' => '',
                        'data' => '0000-00-00'
                    );
                }

                $return += $this->Tratamentos_model->insertEvolucoes($evolucoes, $procedimento);

            }else if($max_sessao->max_sessao > $sessoes_tot){
                $session_param = $sessoes_tot;

                if($sessoes_tot < $max_evo->max_evo){
                    $session_param = $max_evo->max_evo;
                }

                $this->Tratamentos_model->delete_guia_evolucao($id, $session_param);
                $this->Tratamentos_model->delete_procedimento_evolucao($id, $session_param);
                $this->Tratamentos_model->delete_faturamento_evolucao($id, $session_param);
                $this->Tratamentos_model->delete_equipamento_evolucao($id, $session_param);
                $this->db->where(array('tratamento_id'=>$id, 'sessao >'=>$session_param, 'status'=>''))->delete('evolucao');
            }

            $this->Tratamentos_model->updateEspecialidadeAgenda($id, $especialidade);

            if($sessoes_tot < $max_evo->max_evo){
                $sessoes_restantes = $max_evo->max_evo - $last_sessao->sessao;

                if($sessoes_restantes < 0){
                    $sessoes_restantes = $sessoes_restantes * -1;
                }

                $dados['sessoes_totais'] = $max_evo->max_evo;
                $dados['sessoes_restantes'] = $sessoes_restantes;
            }

            $return += $this->Tratamentos_model->delete('tratamento_has_procedimento', array('tratamento_id'=>$id));

            if($procedimento){
                foreach ($procedimento as $key => $proced) {
                    if($proced){
                        $procedimentos_tratamento[] = array(
                            'tratamento_id'     => $id,
                            'procedimento_id'   => $proced
                            );
                    }
                }

                if(isset($procedimentos_tratamento) && $procedimentos_tratamento){
                    $return += $this->Tratamentos_model->insert_table_batch('tratamento_has_procedimento', $procedimentos_tratamento);
                }
            }

            $return += $this->Tratamentos_model->delete( 'mensalidade', array( 'tratamento_id' => $id ) );
            $return += $this->Tratamentos_model->update( $dados, $id );

            if(!$historico_pagamento && !$boleto_gerado){
                if($this->input->post('fluxo_caixa')){
                    //Verificar integridade refencial no pagamento_caixa
                    $mensagem_lancamento = $this->Tratamentos_model->verificar_relacao_caixa($id);

                    if($mensagem_lancamento){
                        $alert = array('message' => $mensagem_lancamento, 'return' => 'alert-danger' );
                        $this->session->set_flashdata('alert', $alert);
                        redirect(base_url().'tratamentos/editar/'.$id);                
                    }

                    # Consulta antes de remover
                    $dados_lancamento = $this->Tratamentos_model->get_row(array('tratamento_id' => $id), 'lancamento_caixa');

                    # Consulta as parcelas do pagamento
                    $dados_parcelas = null;
                    if($dados_lancamento){
                        $dados_parcelas = $this->Tratamentos_model->get_all_table('pagamento_caixa', array('lancamento_caixa_id' => $dados_lancamento->id));
                    }

                    //Removendo historico de pagamento
                    $this->Tratamentos_model->delete_hpc_tratamento($id);

                    //Removendo o pagamento caixa
                    $this->Tratamentos_model->delete_pc_tratamento($id);

                    //Removendo o lancamento de caixa
                    $this->Tratamentos_model->delete_lc_tratamento($id);

                    if($dados_parcelas){
                        foreach($dados_parcelas as $key => $parcela){
                            # Adiciona Log
                            $this->log_financeiro->gerar_log_tabela('log_pagamento_caixa', $parcela, 'r');
                        }
                    }

                    # Adiciona Log
                    if($dados_lancamento){
                        $this->log_financeiro->gerar_log_tabela('log_lancamento_caixa', $dados_lancamento, 'r');
                    }

		            //Realizar lançamento no fluxo de caixa
		            $id_lancamento = null; 

		            if($this->input->post('fluxo_caixa')){
		                $lancamento_dados = $this->realizar_lancamento_caixa($fp[$forma_pagamento]['forma'], $id, ($dados_lancamento ? $dados_lancamento->competencia : null));

                        $id_lancamento = $lancamento_dados['id_lancamento'];
                        $msg_liquidar = $lancamento_dados['msg_liquidar'];
		            }
            	}
            }
            
            //Criando a tabela de mensalidade caso não seja lançado no financeiro
            if(!$this->input->post('fluxo_caixa') && $this->input->post('controle') == 'sim' && $this->input->post('categoria') == 3){
                $dias_m = $this->input->post('dias_semana');
                $data_inicio_m = $this->input->post('data_inicio');
                $plano_m = (int) $this->input->post('plano');
                $competencia_m = data2famericano($data_inicio_m);
                $data_vencimento_m = ($this->input->post('data_vencimento') ? data2famericano($this->input->post('data_vencimento')) : null);

                $controle_m = $this->controleSessoes($dias_m, $data_inicio_m, $plano_m);
                $mensalidade_m = array();

                foreach($controle_m['meses'] as $mes_m => $sessoes_m){
                    $mensalidade_m[] = array(
                        'vencimento' => $data_vencimento_m,
                        'competencia' => $competencia_m,
                        'sessoes' => $sessoes_m,
                        'valor' => $valor - $desconto,
                        'tratamento_id' => $id
                    );

                    //Competencia
                    $data_m = DateTime::createFromFormat('Y-m-d', $competencia_m);
                    $data_m->add(new DateInterval('P1M'));
                    $competencia_m = $data_m->format('Y-m-d');

                    //Vencimento
                    if($data_vencimento_m){
                        $data_v = DateTime::createFromFormat('Y-m-d', $data_vencimento_m);
                        $data_v->add(new DateInterval('P1M'));
                        $data_vencimento_m = $data_v->format('Y-m-d');
                    }
                }

                if(isset($mensalidade_m) && $mensalidade_m){
                    $this->Tratamentos_model->insert_table_batch('mensalidade', $mensalidade_m);
                }
            }

            $evolucoes = $this->db->where('tratamento_id',$id)->get('evolucao')->result();

            foreach($evolucoes as $evolucao){
                $procedimentos = $this->db->where('tratamento_id',$evolucao->tratamento_id)->get('tratamento_has_procedimento')->result();

                foreach($procedimentos as $procedimento){
                    if(!$this->db->where('evolucao_id', $evolucao->id)->get('procedimento_evolucao')->num_rows()){
                        $this->db->insert('procedimento_evolucao', array(
                            'evolucao_id' => $evolucao->id,
                            'procedimento_id' => $procedimento->procedimento_id
                        ));
                    }
                }
            }

            //Registrando o usuario que efetuou a edição no tratamento
            $dados_edicao = array(
                'data' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'usuario_id' => $this->session->userdata('user_id'),
                'tratamento_id' => $id,
            );
            
            $this->Tratamentos_model->insert_table('tratamento_edicao', $dados_edicao);

            //Efetuando a transação com a ZOOP :)
            $mensagem_transacao = null;

            if($this->input->post('cartao_padrao') == 99 && $this->input->post('agilpay_vendedor_id') && $this->input->post('fluxo_caixa')){
                $zoop_return = $this->emitir_transacao_cartao($id_lancamento, $id);

                //Montar mensagem da transacao
                if($zoop_return['status'] == 3){
                    $mensagem_transacao = '
                    <div style="color: #a94442">
                        <br><br>
                        <strong><span class="glyphicon glyphicon-exclamation-sign"></span> Transação:</strong><br>
                        A transação não foi efetuada. Edite o tratamento registrado e tente novamente.<br>
                        '.($zoop_return['mensagem'] ? 'Motivo: '.$zoop_return['mensagem'] : '').'
                    </div>';
                
                }else if($zoop_return['status'] == 2){
                    $mensagem_transacao = '
                    <div>
                        <br><br>
                        <strong><span class="glyphicon glyphicon-ok-sign"></span> Transação:</strong><br>
                        A transação foi efetuada com sucesso.<br>
                    </div>';

                }else if($zoop_return['status'] == 1){
                    $mensagem_transacao = '
                    <div style="color: #8a6d3b">
                        <br><br>
                        <strong><span class="glyphicon glyphicon-exclamation-sign"></span> Transação:</strong><br>
                        A transação encontra-se pendente.<br>
                    </div>';
                }
            }    

            if($return){
                if(isset($msg_liquidar) && $msg_liquidar){
                    $alert = array('message' => 'O lançamento foi efetuado, porém o liquidar automático do cartão não pode ser efetuado corretamente, o caixa já foi fechado. '.$msg_liquidar, 'return' => 'alert-danger');
                
                }else{
                    $alert = array( 'message' => 'Tratamento editado com sucesso.', 'return' => 'alert-success' );
                }

            }else{
                $alert = array( 'message' => 'Houve um erro ao editar o tratamento.', 'return' => 'alert-danger' );
            }

            //Caso tenha transação concatenar a mensagem1
            if($mensagem_transacao){
                $alert['message'] = $alert['message'].$mensagem_transacao;
            }

            $this->session->set_flashdata( 'alert', $alert );

            if($this->input->post('fluxo_caixa') && $this->input->post('pagar_lancamento') && $id_lancamento){
                redirect('financeiro/caixa/fluxo/'.$id_lancamento);

            }else{
                redirect( base_url() . 'tratamentos/editar/' . $id );
            }

        }else{
            $this->controle_clinicas($tratamento->clinica_id);
            $this->form_novo_medico();

            //Campos Pagamento Online
            $this->form_agilpay();

            $this->_dados['pagina']                     = 'tratamentos/editar';
            $this->_dados['titulo']                     = 'Editar Tratamento';

            $this->_dados['convenios']['']              = 'Selecione...';
            $this->_dados['necessidades']['']           = 'Selecione...';
            $this->_dados['especialidades']['']         = 'Selecione...';
            $this->_dados['medicos']['']                = 'Selecione...';
            $this->_dados['profissionais']['']          = 'Selecione...';
            $this->_dados['tipo_procedimentos']['']     = 'Selecione...';
            $this->_dados['categorias']['']             = 'Selecione...';
            $this->_dados['categorias_conta']['']       = 'Selecione...';
            $this->_dados['subcategorias']['']          = 'Selecione...';
            $this->_dados['contas']['']                 = 'Selecione...';
            $this->_dados['tipos_pagamento']['']        = 'Selecione...';
            $this->_dados['carater'][''] = 'Selecione...';
            $this->_dados['acidente'][''] = 'Selecione...';
            $this->_dados['tipo_atendimento'][''] = 'Selecione...';
            $this->_dados['mensalidades'][''] = 'Selecione...';
            $this->_dados['set_tipo_pagamento'] = 10;

            $this->_dados['procedimentos_combo']  = $this->Tratamentos_model->get_procedimentos_combo(array('tratamento_id'=>$id));
            $combo_utilizado = $this->Tratamentos_model->get_procedimentos_combo(array('tratamento_id'=>$id, '(c.sessoes_totais - c.sessoes_restantes) >' => 0));
            $this->_dados['combo_utilizado'] = "";
            if ($combo_utilizado) {
                foreach ($combo_utilizado as $key => $combo) {
                    $this->_dados['combo_utilizado'] .= $combo->procedimento_id.",";
                }
                $this->_dados['combo_utilizado'] = substr($this->_dados['combo_utilizado'], 0, -1);
            }
            
            $convenio   = $this->Tratamentos_model->get_all_table( 'convenio', array('deleted' => '0'), 0, 0, 'nome', 'asc' );
            foreach( $convenio as $c ) $this->_dados['convenios'][$c->id] = $c->nome;

            $necessidades           = $this->Tratamentos_model->get_all_table( 'necessidade', array(), 0, 0, 'nome', 'asc' );
            foreach ($necessidades as $p) $this->_dados['necessidades'][$p->id] = $p->nome;

            $profissionais        = $this->Tratamentos_model->get_profissionais( array('tp.especialidade_id' => $tratamento->especialidade_id) );
            foreach ($profissionais as $p) $this->_dados['profissionais'][$p->id] = $p->nome;

            $especialidades        = $this->Tratamentos_model->get_all_table( 'especialidade', array('deleted' => 0), 0, 0, 'nome', 'asc' );
            foreach ($especialidades as $t) $this->_dados['especialidades'][$t->id] = $t->nome;

            $medicos                = $this->Tratamentos_model->get_all_table( 'medico', array(), 0, 0, 'nome', 'asc' );
            foreach ($medicos as $m) $this->_dados['medicos'][$m->id] = $m->nome;

            $categorias           = $this->Tratamentos_model->get_all_table( 'categoria_tratamento', array('id !=' => 1) );
            foreach ($categorias as $c) $this->_dados['categorias'][$c->id] = $c->nome;

            $procedimentos = $this->Tratamentos_model->get_all_table('procedimento', array('convenio_id' => $tratamento->convenio_id, 'deleted' => 0), 0, 0, 'ordem', 'asc');

            foreach ($procedimentos as $c) {
                $status_proc = '';
                
                if($c->deleted == 1){
                    $status_proc = ' (Removido)';

                }elseif($c->status == 0){
                    $status_proc = ' (Desativado)';
                }

                $this->_dados['procedimentos'][$c->id] = $c->codigo . ' - ' . $c->procedimento. ($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? ' (R$ '.$c->valor.')' : '').$status_proc;
            }

            $profissionais_combo = $this->Tratamentos_model->get_tratamento_mensalidade_profissional(array('tratamento_id' => $id));

            $this->_dados['profissionais_combo'] = $profissionais_combo;

            if($profissionais_combo){
                foreach($profissionais_combo as $c){
                    $this->_dados['set_profissionais_porcentagem'][$c->profissional_id] = $c->profissional_id;
                }
            }

            $trata_proced           = $this->Tratamentos_model->get_all_table( 'tratamento_has_procedimento', array('tratamento_id'=>$id) );
            foreach ($trata_proced as $c) $this->_dados['trata_proced'][$c->procedimento_id] = $c->procedimento_id;

            foreach ($trata_proced as $key => $proc) {
                if (!isset($this->_dados['procedimentos'][$proc->procedimento_id])) {
                    $proc_deleted = $this->Tratamentos_model->get_row(array('id' => $proc->procedimento_id), 'procedimento');

                $status_proc = '';

                if($proc_deleted->deleted == 1){
                    $status_proc = ' (Removido)';

                }elseif($proc_deleted->status == 0){
                    $status_proc = ' (Desativado)';
                }

                    $this->_dados['procedimentos'][$proc_deleted->id] = $proc_deleted->codigo . ' - ' . $proc_deleted->procedimento. ($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? ' (R$ '.$proc_deleted->valor.')' : '').$status_proc;
                }
            }

            $tipos_pagamento           = $this->Tratamentos_model->get_all_table( 'tipo_pagamento' );
            foreach ($tipos_pagamento as $c) $this->_dados['tipos_pagamento'][$c->id] = $c->nome;

            $categoria           = $this->Tratamentos_model->get_all_table( 'tipo_plano_conta', array('id !=' => 3, 'deleted' => 0) );
            foreach ($categoria as $c) $this->_dados['categorias_conta'][$c->id] = $c->nome;

            $subcategoria           = $this->Tratamentos_model->get_all_table( 'plano_conta', array('tipo_plano_conta_id' => $tratamento->tipo_plano_conta_id) );
            foreach ($subcategoria as $s) $this->_dados['subcategorias'][$s->id] = $s->nome;

            $conta           = $this->Tratamentos_model->get_all_table( 'conta', array('status'=>1) );
            foreach ($conta as $c) $this->_dados['contas'][$c->id] = $c->nome;

            $carater = $this->Tratamentos_model->get_all_table( 'tiss_carater', array(), 0, 0, 'nome', 'asc' );
            foreach ($carater as $car) $this->_dados['carater'][$car->id] = $car->nome.' ('.$car->codigo.')';

            $tipo_atendimento = $this->Tratamentos_model->get_all_table( 'tiss_tipo_atendimento', array(), 0, 0, 'id', 'asc' );
            foreach ($tipo_atendimento as $ta) $this->_dados['tipo_atendimento'][$ta->id] = $ta->codigo.' - '.$ta->nome;

            $acidente = $this->Tratamentos_model->get_all_table( 'tiss_acidente', array(), 0, 0, 'id', 'asc' );
            foreach ($acidente as $ac) $this->_dados['acidente'][$ac->id] = $ac->codigo.' - '.$ac->nome;

            $this->_dados['indicacao_clinica'] = array('name' => 'indicacao_clinica', 'class' => 'form-control', 'id' => 'indicacao_clinica', 'value' => set_value('indicacao_clinica', $tratamento->tiss_indicacao_clinica));

            $this->_dados['avaliacao_row'] = $this->Tratamentos_model->get_avaliacao_row( $id );

            $this->_dados['planos'] = array(
                '' => 'Selecione...',
                1 => '1 Mês',
                2 => '2 Meses',
                3 => '3 Meses',
                4 => '4 Meses',
                5 => '5 Meses',
                6 => '6 Meses',
                7 => '7 Meses',
                8 => '8 Meses',
                9 => '9 Meses',
                10 => '10 Meses',
                11 => '11 Meses',
                12 => '12 Meses'
            );

            $this->_dados['dias_semana'] = array(
                1 => 'Segunda-Feira',
                2 => 'Terça-Feira',
                3 => 'Quarta-Feira',
                4 => 'Quinta-Feira',
                5 => 'Sexta-Feira',
                6 => 'Sábado',
            );

            $this->_dados['set_dias_semana'] = null;

            if($tratamento->dias_semana){
                $dias_semana_exp = explode(',', $tratamento->dias_semana);

                $this->_dados['set_dias_semana'] = $dias_semana_exp;
            }

            $mensalidades = $this->Tratamentos_model->get_all_table('tratamento_mensalidade', array(), 0, 0, 'nome', 'asc');

            foreach($mensalidades as $m){
                $valor_m = number_format($m->valor, 2, ',', '.');
                $selected_m = '';

                if($m->id == $tratamento->tratamento_mensalidade_id){
                    $selected_m = 'selected="selected';
                }

                $this->_dados['mensalidades'][$m->id.'"valor="'.$valor_m.'" recuperar-mes="'.$m->recuperar_mes.'" dias-recuperar="'.$m->dias_recuperar.'" '.$selected_m] = $m->nome.' (R$ '.$valor_m.')';
            }

            $paciente = $this->Tratamentos_model->get_paciente(array('p.id' => $tratamento->paciente_id));

            switch ($tratamento->categoria_tratamento_id) {
                case 1:
                    $this->_dados['form'] = 'tratamentos/_form/avista';
                    break;
                case 2:
                    $this->_dados['form'] = 'tratamentos/_form/pacote';
                    break;
                case 3:
                    $this->_dados['form'] = 'tratamentos/_form/mensalidade';
                    break;
                case 4:
                    $this->_dados['form'] = 'tratamentos/_form/convenio';
                    break;
                case 5:
                    $this->_dados['form'] = 'tratamentos/_form/combo';
                    break;
            }

            $this->_dados['tratamento'] = $tratamento;

            $this->_dados['limite_fj_periodo'] = array('type' => 'number', 'name' => 'limite_fj_periodo', 'class' => 'form-control', 'id' => 'limite_fj_periodo', 'value' => set_value('limite_fj_periodo', $tratamento->limite_fj_periodo) );
            $this->_dados['periodo_bloqueio'] = array('' => 'Selecione...', 1 => 'Mês Vigente', 2 => 'Dias Corridos');

            $this->_dados['total']              = array( 'name' => 'total', 'id' => 'total', 'readonly'=>'readonly', 'class' => 'form-control money', 'value' => set_value('total',number_format($tratamento->valor,2,',','.')) );

            $this->_dados['data_limite']        = array( 'name' => 'data_limite', 'class' => 'form-control date', 'id' => 'data_limite', 'value' => set_value('data_limite', bd2data($tratamento->data_limite)) );

            $this->_dados['convenio']           = array( 'name' => 'convenio', 'class' => 'form-control', 'id' => 'convenio', 'readonly'=>'readonly', 'value' => set_value('convenio', $tratamento->convenio) );
            $this->_dados['paciente']           = array( 'name' => 'label_paciente', 'class' => 'form-control', 'id' => 'busca_nome', 'disabled' => 'disabled', 'value' => set_value('label_paciente', $paciente->nome) );
            $this->_dados['id_paciente']        = array( 'type' => 'hidden', 'convenio' => $paciente->convenio, 'convenio_id' => $paciente->convenio_id, 'name' => 'paciente', 'class' => 'pacientes', 'id' => 'pacientes', 'required' => 'required', 'value' => set_value('paciente', $paciente->id) );
            $this->_dados['num_guia']           = array( 'name' => 'num_guia', 'class' => 'form-control', 'id' => 'num_guia', 'value' => set_value('num_guia', $tratamento->num_guia) );
            $this->_dados['cid']           = array( 'name' => 'cid', 'class' => 'form-control', 'id' => 'cid', 'value' => set_value('cid', $tratamento->cid) );
            // $this->_dados['sessoes']            = array( 'name' => 'sessoes', 'class' => 'form-control', 'id' => 'sessoes', 'maxlength' => '2', 'value' => set_value('sessoes', $tratamento->sessoes_totais) );
            $this->_dados['data_inicio']        = array( 'name' => 'data_inicio', 'class' => 'form-control date', 'id' => 'data_inicio_tratamento', 'value' => set_value('data_inicio', bd2data($tratamento->data_inicio)) );
            $this->_dados['data_vencimento']    = array( 'name' => 'data_vencimento', 'class' => 'form-control date', 'id' => 'data_vencimento', 'value' => set_value('data_vencimento', bd2data($tratamento->data_vencimento)) );

            $this->_dados['sessoes_fisioterapia']= array('name' => 'sessoes_fisioterapia', 'class' => 'form-control', 'id' => 'sessoes_fisioterapia', 'maxlength' => '3', 'value' => set_value('sessoes_fisioterapia', $tratamento->sessoes_totais - $tratamento->sessoes_retorno));
            $this->_dados['sessoes_retorno'] = array('name' => 'sessoes_retorno', 'class' => 'form-control', 'id' => 'sessoes_retorno', 'maxlength' => '2', 'value' => set_value('sessoes_retorno', $tratamento->sessoes_retorno));
            $this->_dados['ta_retorno'] = $this->Tratamentos_model->get_row(array('id' => 17), 'tipo_agendamento');

            $this->_dados['nome_pacote']        = array( 'name' => 'nome_pacote', 'id' => 'nome_pacote', 'class' => 'form-control', 'value' => set_value('nome_pacote', $tratamento->nome_pacote) );
            $this->_dados['tratamento']         = $tratamento;
            
            $this->_dados['valor']              = array( 'name' => 'valor', 'id' => 'valor', 'class' => 'form-control money', 'value' => set_value('valor', number_format($tratamento->valor,2,',','.')) );
            $this->_dados['subtotal']           = array( 'name' => 'subtotal', 'id' => 'subtotal', 'readonly'=>'readonly', 'class' => 'form-control money', 'value' => set_value('subtotal') );
            $this->_dados['desconto']           = array( 'name' => 'desconto', 'id' => 'desconto', 'class' => 'form-control money', 'value' => set_value('desconto', number_format($tratamento->desconto,2,',','.')) );
            $this->_dados['acrescimo']           = array( 'name' => 'acrescimo', 'id' => 'acrescimo', 'class' => 'form-control money', 'value' => set_value('acrescimo', number_format($tratamento->acrescimo,2,',','.')) );
            

            $desconto_porcento = $tratamento->desconto_porcento;
            
            if($tratamento->desconto > 0 && !$desconto_porcento){
                $desconto_porcento = ($tratamento->desconto * 100) / ($tratamento->valor+$tratamento->desconto);
                $desconto_porcento = number_format($desconto_porcento,2,',','.');
            }

            $this->_dados['desconto_real'] = array('name' => 'desconto', 'id' => 'desconto', 'class' => 'form-control money', 'value' => set_value('desconto', number_format($tratamento->desconto,2,',','.')));
            $this->_dados['desconto_porcento'] = array('name' => 'desconto_porcento', 'id' => 'desconto_porcento', 'class' => 'form-control money', 'value' => set_value('desconto_porcento', $desconto_porcento));

            //Permissão para desabilitar a opção de dar descontos no tratamento
            if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'desconto_tratamento', 'Desativar Opção de Descontos e Valor Únitário no Tratamento')){
                $this->_dados['desconto_real']['readonly'] = 'readonly';
                $this->_dados['desconto_porcento']['readonly'] = 'readonly';
                $this->_dados['valor']['readonly'] = 'readonly';
            }

            $this->_dados['data_autorizacao']   = array( 'name' => 'data_autorizacao', 'id' => 'data_autorizacao', 'class' => 'form-control data', 'value' => set_value('data_autorizacao', bd2data($tratamento->data_autorizacao)) );
            $this->_dados['senha']              = array( 'name' => 'senha', 'id' => 'senha', 'class' => 'form-control', 'value' => set_value('senha', $tratamento->senha) );
            $this->_dados['vencimento_autorizacao']   = array( 'name' => 'vencimento_autorizacao', 'id' => 'vencimento_autorizacao', 'class' => 'form-control data', 'value' => set_value('vencimento_autorizacao', isset($tratamento->vencimento_autorizacao) ? $tratamento->vencimento_autorizacao : null) );
    
            $this->_dados['cartao_padrao'] = array('type' => 'hidden', 'name' => 'cartao_padrao', 'id' => 'cartao-padrao', 'value' => $tratamento->cartao_credito);

            //Opção de várias formas de pagamentos
            $this->_dados['entradas'] = array('' => 'Não', 'Sim' => 'Sim');
            $this->_dados['valor_entrada'] = array('name' => 'valor_entrada[]', 'class' => 'form-control money valor_entrada');
            $this->_dados['venc_entrada'] = array('name' => 'venc_entrada[]', 'class' => 'form-control date venc_entrada', 'placeholder' => 'dd/mm/aaaa');
            $this->_dados['parcelas_entrada'] = array('type' => 'number', 'name' => 'parcelas_entrada[]', 'class' => 'form-control parcelas_entrada', 'value' => '1');
            $this->_dados['adicionar_entrada']  = array('type' => 'button', 'class' => 'btn btn-success adicionar_entrada', 'content' => '+ Adicionar mais uma forma de pagamento');
            $this->_dados['remover_entrada']  = array('type' => 'button', 'class' => 'btn btn-danger remover_entrada', 'content' => 'X', 'style' => 'position:relative; top:20px; float:right; width:60px;');
            $this->_dados['cartao_padrao_entrada'] = array('type' => 'hidden', 'name' => 'cartao_padrao_entrada[]', 'class' => 'cartao-padrao-entrada');

            //Configuração para verificar se o tipo de tratamento convenio terá opção de lançar no fluxo de caixa
            $this->_dados['convenio_financeiro_config'] = $this->Tratamentos_model->get_row(array('id' => 42), 'configuracao');

            //Campo valor credito paciente
            $this->_dados['credito_utilizado'] = array('name' => 'credito_utilizado', 'class' => 'form-control money', 'id' => 'credito-utilizado', 'value' => set_value('credito_utilizado'));

            $this->_dados['submit']             = array( 'name' => 'submit', 'class' => 'btn btn-primary', 'id' => 'submit', 'value' => 'Salvar' );
            $this->_dados['id'] = $id;

            $this->load->view($this->_layout, $this->_dados);
        }
    }

    /**
     * Função para dar alta no tratamento e finalizar as evoluções em aberto
     */
    public function alta_tratamento($id_tratamento){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Alta no Tratamento');

        $tratamento = $this->Tratamentos_model->get_by_id_table('tratamento', array('id' => $id_tratamento));
        $descricao  = $this->input->post("descricao");

        //Dados de historico (alta no tratamento)
        $dados = array(
            "descricao" => $descricao,
            "sessoes_restantes" => $tratamento->sessoes_restantes,
            "sessoes_totais" => $tratamento->sessoes_totais,
            "usuario_id" => $this->session->userdata('user_id'),
            "tratamento_id" => $id_tratamento
        );

        $result = $this->Tratamentos_model->insert_table('historico_alta_tratamento', $dados);

        //Quantidade restante que foi utilizado até o momento da alta
        $qtd_restante = $tratamento->sessoes_totais - $tratamento->sessoes_restantes;

        //Coloca o número de sessoes restantes no sessoes totais
        $result = $this->Tratamentos_model->update_table('tratamento', array("sessoes_totais" => $qtd_restante, "sessoes_restantes" => 0), array("id" => $id_tratamento));

        //Remove sessões em aberto
        $max_evo = $this->Tratamentos_model->get_max_evo_tratamento($id_tratamento);
        $max_evo = ($max_evo->max_evo ? $max_evo->max_evo : 0);

        $this->Tratamentos_model->delete_guia_evolucao($id_tratamento, $max_evo);
        $this->Tratamentos_model->delete_procedimento_evolucao($id_tratamento, $max_evo);
        $this->db->where(array('tratamento_id'=>$id_tratamento, 'sessao >'=>$max_evo, 'status'=>''))->delete('evolucao');

        //Resultado do insert
        if($result){
            $alert = array("message" => "Alta no tratamento inserido com sucesso!", "return" => "alert-success");

        }else{
            $alert = array("message" => "Houve um erro ao inserir a alta no tratamento!", "return" => "alert-danger");
        }

        $this->session->set_flashdata("alert", $alert);
        redirect("tratamentos/visualizar/".$id_tratamento);
    }

    /**
     * Função para dar pausa no tratamento
     */
    public function pausa_tratamento($id_tratamento){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Pausa no Tratamento');

        $tratamento = $this->Tratamentos_model->get_by_id_table('tratamento', array('id' => $id_tratamento));
        $descricao  = $this->input->post("descricao");

        //Dados de historico (alta no tratamento)
        $dados = array(
            "descricao" => $descricao,
            "status" => 1,
            "usuario_id" => $this->session->userdata('user_id'),
            "tratamento_id" => $id_tratamento
        );

        $result = $this->Tratamentos_model->insert_table('historico_pausa_tratamento', $dados);
        $this->Tratamentos_model->update_table('tratamento', array("pausa" => 1), array("id" => $id_tratamento));

        //Resultado do insert
        if($result){
            $alert = array("message" => "Pausa no tratamento efetuada com sucesso!", "return" => "alert-success");

        }else{
            $alert = array("message" => "Houve um erro ao pausar o tratamento!", "return" => "alert-danger");
        }

        $this->session->set_flashdata("alert", $alert);
        redirect("tratamentos/visualizar/".$id_tratamento);
    }

    /**
     * Função para reativar o tratamento
     */
    public function reativacao_tratamento($id_tratamento){
        $tratamento = $this->Tratamentos_model->get_by_id_table('tratamento', array('id' => $id_tratamento));
        $descricao  = $this->input->post("descricao");

        //Dados de historico (alta no tratamento)
        $dados = array(
            "status" => 0,
            "descricao" => 'Tratamento reativado',
            "usuario_id" => $this->session->userdata('user_id'),
            "tratamento_id" => $id_tratamento
        );

        $result = $this->Tratamentos_model->insert_table('historico_pausa_tratamento', $dados);
        $this->Tratamentos_model->update_table('tratamento', array("pausa" => 0), array("id" => $id_tratamento));

        //Resultado do insert
        if($result){
            $alert = array("message" => "Reativação no tratamento efetuada com sucesso!", "return" => "alert-success");

        }else{
            $alert = array("message" => "Houve um erro ao reativar o tratamento!", "return" => "alert-danger");
        }

        $this->session->set_flashdata("alert", $alert);
        redirect("tratamentos/visualizar/".$id_tratamento);
    }

    public function cancelamento_tratamento($id_tratamento){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Cancelamento do Tratamento');

        $tratamento = $this->Tratamentos_model->get_by_id_table('tratamento', array('id' => $id_tratamento));
        $descricao  = $this->input->post("descricao");
        $valor_credito = ($this->input->post('valor_credito') ? str_replace(array('.',','), array('','.'), $this->input->post('valor_credito')) : null);

        //Dados de historico (cancelamento)
        $dados = array(
            "descricao" => $descricao,
            "valor_credito" => $valor_credito,
            "sessoes_restantes" => $tratamento->sessoes_restantes,
            "sessoes_totais" => $tratamento->sessoes_totais,
            "usuario_id" => $this->session->userdata('user_id'),
            "tratamento_id" => $id_tratamento
        );

        $result = $this->Tratamentos_model->insert_table('historico_cancelamento_tratamento', $dados);

        //Inserindo no historico de credito do paciente
        if($result && $valor_credito){
            //Consultar o último histórico de crédito para pegar o saldo atual
            $historico_credito = $this->Tratamentos_model->get_row(array('paciente_id' => $tratamento->paciente_id), 'paciente_historico_credito', 'id', 'desc');

            $dados_paciente_credito = array(
                'usuario_id' => $this->session->userdata('user_id'),
                'data_hora' => date('Y-m-d H:i:s'),
                'paciente_id' => $tratamento->paciente_id,
                'historico_cancelamento_tratamento_id' => $result, 
                'valor' => $valor_credito,
                'tipo' => 0, 
                'saldo' => ($historico_credito ? $historico_credito->saldo + $valor_credito : $valor_credito)
            );

            $result += $this->Tratamentos_model->insert_table('paciente_historico_credito', $dados_paciente_credito);
        }

        //Quantidade restante que foi utilizado até o momento do cancelamento
        $qtd_restante = $tratamento->sessoes_totais - $tratamento->sessoes_restantes;

        //Coloca o número de sessoes restantes no sessoes totais
        $result += $this->Tratamentos_model->update_table('tratamento', array("sessoes_totais" => $qtd_restante, "sessoes_restantes" => 0), array("id" => $id_tratamento));

        //Remove sessões em aberto
        $max_evo = $this->Tratamentos_model->get_max_evo_tratamento($id_tratamento);
        $max_evo = ($max_evo->max_evo ? $max_evo->max_evo : 0);

        $this->Tratamentos_model->delete_guia_evolucao($id_tratamento, $max_evo);
        $this->Tratamentos_model->delete_procedimento_evolucao($id_tratamento, $max_evo);
        $this->db->where(array('tratamento_id'=>$id_tratamento, 'sessao >'=>$max_evo, 'status'=>''))->delete('evolucao');

        //Resultado do insert
        if($result){
            $alert = array("message" => "Cancelamento efetuado com sucesso!", "return" => "alert-success");

        }else{
            $alert = array("message" => "Houve um erro ao efetuar o cancelamento!", "return" => "alert-danger");
        }

        $this->session->set_flashdata("alert", $alert);
        redirect("tratamentos/visualizar/".$id_tratamento);
    }

    public function excluir( $id = 0 ){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Excluir');
        
        $check = $this->Tratamentos_model->check_row( $id );

        // Verifica se possui registros relacionado para validação
        if( $check > 0 ) {
            $alert = array( 'message' => 'Não é possivel excluir registro. A exclusão desse registro pode ocasionar perda em outras informações!', 'return' => 'alert-danger' );
        } else {
        
            $result += $this->Tratamentos_model->update_table('agenda', array( 'tratamento_id' => null ), array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete_combo( array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete( 'tratamento_has_procedimento', array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete( 'avaliacao', array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete( 'mensalidade', array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete('faturamento', array('tratamento_id' => $id));
            // $result += $this->Tratamentos_model->delete_lancamento( array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete_guias( array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete_evolucoes( array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete( 'tratamento_edicao', array( 'tratamento_id' => $id ) );
            $result += $this->Tratamentos_model->delete( 'tratamento', array( 'id' => $id ) );

            if( $result ){

                $alert = array( 'message' => 'Registro foi removido com sucesso!', 'return' => 'alert-success' );
            }else{
                $alert = array( 'message' => 'Houve um erro ao remover registro! Entre em contato com suporte ou tente mais tarde.', 'return' => 'alert-danger' );
            }
        }

        $this->session->set_flashdata( 'alert', $alert );
        redirect( base_url() . 'tratamentos/' );

    }

    /**
     * Salvar Antes e Depois
     */
    public function salvar_antes_depois($tratamento_id, $id_agenda = null, $id_evolucao = null  ){
        $this->auth_library->check_logged('tratamentos', 'tratamentos', 'visualizar_antes_depois', 'Antes e Depois');

        if($tratamento_id){

            $return = 0;
            if(isset($_FILES['foto_antes_modificacao']['name'])){

                $id_imagem = $this->input->post('id');
                $foto_antes_modificacao = $this->upload_antes_depois('foto_antes_modificacao', $tratamento_id);

                $dados = array(
                    'antes' => $foto_antes_modificacao,
                    'data_antes' => date('Y-m-d')
                );

                $return += $this->Tratamentos_model->update_table('tratamento_antes_depois', $dados, array("id" => $id_imagem));

            }elseif(isset($_FILES['foto_depois_modificacao']['name'])){

                $id_imagem = $this->input->post('id');
                $foto_depois_modificacao = $this->upload_antes_depois('foto_depois_modificacao', $tratamento_id);

                $dados = array(
                    'depois' => $foto_depois_modificacao,
                    'data_depois' => date('Y-m-d')
                );

                $return += $this->Tratamentos_model->update_table('tratamento_antes_depois', $dados, array("id" => $id_imagem));

            }else{

                if($_FILES['foto_antes']['name'] != ""){
                    $foto_antes = $this->upload_antes_depois('foto_antes', $tratamento_id);
                }else{
                    $foto_antes = "";
                }

                if($_FILES['foto_depois']['name'] != ""){
                    $foto_depois = $this->upload_antes_depois('foto_depois', $tratamento_id);
                }else{
                    $foto_depois = "";
                }

                $dados = array(
                    'antes' => $foto_antes,
                    'depois' => $foto_depois,
                    'tratamento_id' => $tratamento_id,
                    'usuario_id' => $this->session->userdata('user_id')
                );

                # Data que foi enviada as fotos
                if($foto_antes){ $dados["data_antes"] = date('Y-m-d'); }
                if($foto_depois){ $dados["data_depois"] = date('Y-m-d'); }

                if($foto_antes != ""){
                    $return += $this->Tratamentos_model->insert_table('tratamento_antes_depois', $dados);
                }

            }

            if($return > 0){
                $alert = array('message' => '<i class="glyphicon glyphicon glyphicon-thumbs-up"></i> Sucesso ao enviar a imagem.', 'return' => 'alert-success');
                $this->session->set_flashdata('alert',$alert);
            }else{
                $alert = array('message' => '<i class="glyphicon glyphicon-thumbs-down"></i> Houve um erro ao enviar a imagem.', 'return' => 'alert-danger');
                $this->session->set_flashdata('alert',$alert);
            }

            if (!$id_agenda) {
                redirect(base_url("tratamentos/visualizar/$tratamento_id"));
            } else {
                redirect(base_url("area_profissional/visualizar/$tratamento_id/$id_agenda/$id_evolucao"));
            }

        }else{
            exit("Não foi encontrado o tratamento a ser adicionado o antes e depois.");
        }
    }

    function dados_financeiro(){
        $especialidade_id = $this->input->post('especialidade_id');

        $especialidade = $this->Tratamentos_model->get_by_id_table('especialidade', array('id' => $especialidade_id));

        echo json_encode(array(
            'tipo_plano_conta'  => $especialidade->tipo_plano_conta_id,
            'plano_conta'       => $especialidade->plano_conta_id,
            ));
    }

    /**
     * Upload de imagem Antes e depois
     */
    private function upload_antes_depois($input_name, $tratamento_id){
        $config['upload_path'] = 'uploads/antes_depois';
        $config['allowed_types'] = 'gif|png|jpg|jpeg|jfif|bmp|psd';
        $config['file_name'] = md5(rand(0,getrandmax()));

        $this->load->library('upload', $config);

        $this->upload->do_upload($input_name);

        $this->load->library('image_lib');
        $data = $this->upload->data();

        $config['source_image'] = $data['full_path'];
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        
        $this->image_lib->initialize($config);

        if(!$this->image_lib->resize()){
            $erro = $this->image_lib->display_errors();
            $erro = utf8_encode($erro);
            @unlink($data['full_path']);
            $array = array('message' => 'Erro ao redimencionar imagem, verifique tamanho e formato!', 'return' => 'alert-danger');
            $this->session->set_flashdata('alert', $array);

            redirect(base_url("tratamentos/visualizar/$tratamento_id"));

            return false;
        }else{
            $this->image_lib->clear();
        }

        $info_foto = $this->upload->data();
        $formato = $info_foto['file_ext'];

        # Faz a verificação em qual campos é para alterar caso for imagem de celular.
        if(isset($_FILES['foto_antes'])){
            $exif = exif_read_data($_FILES['foto_antes']['tmp_name']);
            if(!empty($exif['Orientation'])) {
                if($exif['Orientation'] == 6){
                    $this->girar($data['file_name']);
                }
            }
        }else if(isset($_FILES['foto_depois'])){
            $exif = exif_read_data($_FILES['foto_depois']['tmp_name']);
            if(!empty($exif['Orientation'])) {
                if($exif['Orientation'] == 6){
                    $this->girar($data['file_name']);
                }
            }
        }else if(isset($_FILES['foto_depois_modificacao'])){
            $exif = exif_read_data($_FILES['foto_depois_modificacao']['tmp_name']);
            if(!empty($exif['Orientation'])) {
                if($exif['Orientation'] == 6){
                    $this->girar($data['file_name']);
                }
            }
        }else if(isset($_FILES['foto_antes_modificacao'])){
            $exif = exif_read_data($_FILES['foto_antes_modificacao']['tmp_name']);
            if(!empty($exif['Orientation'])) {
                if($exif['Orientation'] == 6){
                    $this->girar($data['file_name']);
                }
            }
        }

        return $data['file_name'];
    }

    /**
     * Função para girar imagens do celular
     */
    private function girar($img){
        $filename = 'uploads/antes_depois/'.$img;
        $rotang = 270;
        $source = imagecreatefromjpeg($filename) or die('Error opening file '.$filename);
        imagealphablending($source, true);
        imagesavealpha($source, true);

        $rotation = imagerotate($source, $rotang, imageColorAllocateAlpha($source, 0, 0, 0, 700));
        imagealphablending($rotation, false);
        imagesavealpha($rotation, true);

        header('Content-type: image/png');
        imagejpeg($rotation, 'uploads/antes_depois/'.$img);
        imagedestroy($source);
        imagedestroy($rotation);
    }

    /**
     * Salvar Peso e Medidas
     */
    public function salvar_peso_medidas($tratamento_id){
        $this->auth_library->check_logged('tratamentos', 'tratamentos', 'visualizar_peso_medidas', 'Peso e Medidas');

        if($tratamento_id){

            $peso = $this->input->post('peso');
            $cintura01 = $this->input->post('cintura01');
            $cintura02 = $this->input->post('cintura02');
            $abdomen01 = $this->input->post('abdomen01');
            $abdomen02 = $this->input->post('abdomen02');
            $quadril = $this->input->post('quadril');
            $coxa = $this->input->post('coxa');
            $imc = $this->input->post('imc');

            # Verifica todos os registros
            foreach($peso as $key => $item){
                $dados = array(
                    "peso" => $peso[$key],
                    "cintura01" => $cintura01[$key],
                    "cintura02" => $cintura02[$key],
                    "abdomen01" => $abdomen01[$key],
                    "abdomen02" => $abdomen02[$key],
                    "quadril" => $quadril[$key],
                    "coxa" => $coxa[$key],
                    "imc" => $imc[$key],
                    "tratamento_id" => $tratamento_id,
                    "usuario_id" => $this->session->userdata('user_id')
                );

                $this->Tratamentos_model->insert_table('tratamento_peso_medidas', $dados);
            }

            $alert = array('message' => '<b><i class="glyphicon glyphicon-thumbs-up"></i></b> Peso e Medidas inserido com sucesso!', 'return' => 'alert-success');
            $this->session->set_flashdata('alert', $alert);

            redirect(base_url("tratamentos/visualizar/$tratamento_id"));

        }else{
            exit("Não foi encontrado o tratamento a ser adicionado o pesos e medidas.");
        }
    }

    /**
     * Visualizar detalhes de um tratamento em especifico
     */
    public function visualizar( $id = 0 ){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Visualizar Tratamento');

        $this->load->helper('texto_helper');
        
        //Reodernar sessoes de acordo com a data
        $this->verificar_evolucao($id);

        $tratamento = $this->Tratamentos_model->get_tratamento( array( 't.id' => $id ) );
        $evolucao   = $this->Tratamentos_model->get_evolucao( array( 't.id' => $id ) );
        $where      = array();
        $sessao     = 0;
        $num_sessao = 0;

        if($tratamento){

        $where = array(
            'paciente_id'       => $tratamento->paciente_id,
            'profissional_id'   => $tratamento->profissional_id
        );

        //Verificando se o profissional tem permissão para acessar o tratamento
        if($this->session->userdata('profissional_id') && $this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_tratamentos', 'O profissional poderá visualizar somente seus tratamentos relacionados') && $tratamento->profissional_id != $this->session->userdata('profissional_id')){
            redirect('tratamentos');
        }

        if($evolucao){
            $evo = $this->Tratamentos_model->get_evolucao( array( 't.id' => $id, 'e.status !=' => 'FJ', 'e.status !=' => 'NC' ) );

            if(isset($evo[count($evo)-1]->sessao)){
                $sessao     = $tratamento->sessoes_totais - $evo[count($evo)-1]->sessao;
                $num_sessao = $evo[count($evo)-1]->sessao+1;
            }else{
                $sessao     = $tratamento->sessoes_restantes;
                $num_sessao = $tratamento->sessoes_totais - $tratamento->sessoes_restantes + 1;
            }

        }else{
            $sessao = $tratamento->sessoes_restantes;
            $num_sessao = $tratamento->sessoes_totais - $tratamento->sessoes_restantes+1;
        }

        if($sessao < 0){
            $sessao = 0;
        }

        if(isset($tratamento->categoria_tratamento_id)){
            switch ($tratamento->categoria_tratamento_id) {
                case 1:
                    $this->_dados['view'] = 'tratamentos/_view/avista';
                    break;
                case 2:
                    $this->_dados['view'] = 'tratamentos/_view/pacote';
                    break;
                case 3:
                    $this->_dados['view'] = 'tratamentos/_view/mensalidade';
                    break;
                case 4:
                    $this->_dados['view'] = 'tratamentos/_view/convenio';
                    break;
                case 5:
                    $this->_dados['view'] = 'tratamentos/_view/combo';
                    break;
            }
        }

            # Antes e Depois
            $tratamento->antes_depois = $this->Tratamentos_model->get_all_table('tratamento_antes_depois', array("tratamento_id" => $id, "deleted" => 0));

            # Peso e Medidas
            $tratamento->medidas = $this->Tratamentos_model->get_all_table('tratamento_peso_medidas', array("tratamento_id" => $id));
            foreach($tratamento->medidas as $key => $item){
                $item->usuario = $this->Tratamentos_model->get_by_id_table('usuario', array("id" => $item->usuario_id))->first_name;
            }

        }

        $this->_dados['agendamentos']   = $this->Tratamentos_model->get_agendamentos($where, $sessao );
        $this->_dados['id'] = $id;
        $this->_dados['pagina']         = 'tratamentos/visualizar';
        $this->_dados['titulo']         = 'Visualizar Tratamento';
        $this->_dados['tratamento']     = $tratamento;

        # Historico de alta no tratamento
        $alta_tratamento = $this->Tratamentos_model->get_by_id_table('historico_alta_tratamento', array("tratamento_id" => $id));
        if(isset($alta_tratamento->usuario_id)){
            # Profissional
            $usuario = $this->Tratamentos_model->get_by_id_table('usuario', array("id" => $alta_tratamento->usuario_id));
            $nm_usuario = $usuario->first_name ." ". $usuario->last_name;
            $alta_tratamento->usuario = $nm_usuario;
        }
        $this->_dados['historico_alta_tratamento'] = $alta_tratamento ? $alta_tratamento : null;

        # Historico de pausa no tratamento
        $pausa_tratamento = $this->Tratamentos_model->get_historico_tratamento_pausado(array("tratamento_id" => $id, "status" => 1));
        $this->_dados['historico_pausa_tratamento'] = $pausa_tratamento ? $pausa_tratamento : null;

        # Historico de reativacao no tratamento
        $reativacao_tratamento = $this->Tratamentos_model->get_historico_tratamento_pausado(array("tratamento_id" => $id, "status" => 0));
        $this->_dados['historico_reativacao_tratamento'] = $reativacao_tratamento ? $reativacao_tratamento : null;

        # Historico de cancelamento no tratamento
        $cancelamento_tratamento = $this->Tratamentos_model->get_by_id_table('historico_cancelamento_tratamento', array("tratamento_id" => $id));
        if(isset($cancelamento_tratamento->usuario_id)){
            # Profissional
            $usuario = $this->Tratamentos_model->get_by_id_table('usuario', array("id" => $cancelamento_tratamento->usuario_id));
            $nm_usuario = $usuario->first_name ." ". $usuario->last_name;
            $cancelamento_tratamento->usuario = $nm_usuario;
        }
        $this->_dados['historico_cancelamento_tratamento'] = $cancelamento_tratamento ? $cancelamento_tratamento : null;

        // $procedimentos_evolucoes = array();

        // $this->_dados['procedimentos_evo'][0] = '';


        //Atualização na data das evoluções
        foreach($evolucao as $key => $dados_evolucao){
            $dados_evolucao->procedimento_descricao = '';
            $descricao_proc_evol = $this->Tratamentos_model->get_procedimento_evolucao($dados_evolucao->id);

            if($descricao_proc_evol){
                foreach($descricao_proc_evol as $key2 => $proc){
                    $dados_evolucao->procedimento_descricao = $dados_evolucao->procedimento_descricao.$proc->codigo.' - '.$proc->procedimento;
                    if(isset($descricao_proc_evol[$key2+1])){
                        $dados_evolucao->procedimento_descricao .= ', ';
                    }
                }
            }

            // if($dados_evolucao->agenda_id){
            //     $agenda = $this->db->where('id', $dados_evolucao->agenda_id)->get('agenda')->row();

            //     if($agenda->data_inicio != $dados_evolucao->data){
            //         $this->Tratamentos_model->update_table('evolucao', array('data' => $agenda->data_inicio), array('id' => $dados_evolucao->id));
            //         $evolucao[$key]->data = $agenda->data_inicio;
            //     }
            // }

            // $pro_evo = $this->Tratamentos_model->get_all_table( 'procedimento_evolucao', array('evolucao_id'=>$dados_evolucao->id));
            // if (!$pro_evo) {
            //     $proc = $this->Tratamentos_model->get_all_table( 'procedimento', array('convenio_id'=>$tratamento->convenio_id, 'status'=>1), 0, 0, 'status', 'desc');

            //     foreach ($proc as $key2 => $pr) {

            //         $procedimentos_evolucoes[$dados_evolucao->id]['procedimentos'][$pr->id] = $pr->codigo.' - '.$pr->procedimento. ($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? ' (R$ '.number_format($pr->valor,2,',','.').')' : '');
            //     }
            // } else {

            //     foreach($pro_evo as $p){

            //         $procedimentos_evolucoes[$p->evolucao_id]['proc_evolucao'][$p->procedimento_id] = $p->procedimento_id;



            //         if($tratamento){
            //             $proc = $this->Tratamentos_model->get_all_table( 'procedimento', array('convenio_id'=>$tratamento->convenio_id, 'status'=>1), 0, 0, 'status', 'desc');

            //             foreach ($proc as $key2 => $pr) {

            //                 $procedimentos_evolucoes[$p->evolucao_id]['procedimentos'][$pr->id] = $pr->codigo.' - '.$pr->procedimento. ($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? ' (R$ '.number_format($pr->valor,2,',','.').')' : '');

            //                 if (!isset($procedimentos_evolucoes[$p->evolucao_id]['procedimentos'][$p->procedimento_id])) {
            //                     $proc_deleted = $this->Tratamentos_model->get_row(array('id'=>$p->procedimento_id), 'procedimento');

            //                     $procedimento_situcao = null;

            //                     if($proc_deleted->status == 0){
            //                         $procedimento_situcao = ' (Desativado)';
            //                     }

            //                     if($proc_deleted->deleted == 1){
            //                         $procedimento_situcao = ' (Removido)';
            //                     }

            //                     $procedimentos_evolucoes[$p->evolucao_id]['procedimentos'][$p->procedimento_id] = $proc_deleted->codigo.' - '.$proc_deleted->procedimento. ($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? ' (R$ '.number_format($proc_deleted->valor,2,',','.').')' : '').$procedimento_situcao;
            //                 }
            //             }
            //         }
                // }
            // }
        }

        // $this->_dados['procedimentos_evolucoes'] = $procedimentos_evolucoes;
        $this->_dados['evolucao']       = $evolucao;
        $this->_dados['historico_evolucao'] = $this->Tratamentos_model->get_historico_evolucao( array( 't.id' => $id ) );
        $this->_dados['procedimentos']  = $this->Tratamentos_model->get_procedimentos_combo(array('tratamento_id'=>$id));
        $this->_dados['movimentacao']   = $this->Tratamentos_model->get_movimentacao_procedimentos_combo(array('mc.tratamento_id'=>$id));
        $this->_dados['avaliacao']      = $this->Tratamentos_model->get_avaliacao_form(array('tratamento_id'=>$id));
        $this->_dados['num_sessao']     = $num_sessao;
        $this->_dados['mensalidade']  = $this->Tratamentos_model->get_all_table('mensalidade', array('tratamento_id' => $id));
        if($tratamento){
            $this->_dados['relatorio_procedimentos'] = $this->Tratamentos_model->getRelatorioProcedimentos($id, $tratamento->categoria_tratamento_id);
            $historico_atendimentos = $this->Tratamentos_model->getHistoricoAtendimentos($tratamento->paciente_id, $tratamento->especialidade_id);
            $historico_avaliacao = $this->Tratamentos_model->get_historico_avaliacao($tratamento->paciente_id);

            $historico_atendimentos_paciente = $this->Tratamentos_model->getHistoricoAtendimentosPaciente($tratamento->paciente_id);
            $especialidade_visualizar = array();
            $usuario = $this->Tratamentos_model->get_by_id_table('usuario', array('id' => $this->session->userdata('user_id')));
            if(isset($usuario->profissional_id)){
                $especialidade_visualizar = $this->Tratamentos_model->get_all_table('especialidade_visualizar_has_profissional', array('profissional_id' => $usuario->profissional_id));
            }

            # Verificação de permissão da especialidade para o profissional
            $info = "";
            foreach($historico_atendimentos_paciente as $key => $historico){
                if ($usuario->profissional_id != '') {
                    foreach($especialidade_visualizar as $key => $especialidade){
                        if($historico->id_especialidade == $especialidade->especialidade_id){
                            $historico->visualiza = 1;
                        }
                    }
                } else {
                    $historico->visualiza = 1;
                }
            }

            foreach($historico_avaliacao as $key => $aval){
                if ($usuario->profissional_id != '') {
                    foreach($especialidade_visualizar as $key => $especialidade){
                        if($aval->id_especialidade == $especialidade->especialidade_id){
                            $aval->visualiza = 1;
                        }
                    }
                } else {
                    $aval->visualiza = 1;
                }
            }

            foreach($historico_atendimentos as $key => $atendimentos){
                if ($usuario->profissional_id != '') {
                    foreach($especialidade_visualizar as $key => $especialidade){
                        if($atendimentos->id_especialidade == $especialidade->especialidade_id){
                            $atendimentos->visualiza = 1;
                        }
                    }
                } else {
                    $atendimentos->visualiza = 1;
                }
            }

            $visualizar_avaliacao = 0;
            if (isset($this->_dados['avaliacao']['titulo'][0])) {
                if ($usuario->profissional_id != '') {
                    foreach($especialidade_visualizar as $key => $especialidade){
                        if($this->_dados['avaliacao']['titulo'][0]->especialidade_id == $especialidade->especialidade_id){
                            $visualizar_avaliacao++;
                        }
                    }
                } else {
                    $visualizar_avaliacao++;
                }
            }

            $this->_dados['visualizar_avaliacao'] = $visualizar_avaliacao;
            $this->_dados['historico_atendimentos_paciente'] = $historico_atendimentos_paciente;
            $this->_dados['historico_avaliacao'] = $historico_avaliacao;
            $this->_dados['historico_atendimentos'] = $historico_atendimentos;
        }

        /*
         * Detallhes do financeiro
         */
        $this->load->library('parcela');
        
        $registro = $this->Tratamentos_model->get_lancamento($id);
        $this->_dados['registro'] = $registro;

        if($registro){
            $this->_dados['pagamento_caixa']  = $this->Tratamentos_model->get_list_pagamento_caixa($registro->lancamento_id);
            $this->_dados['historico_pagamento']  = $this->Tratamentos_model->get_historico_caixa($registro->lancamento_id);
            $this->_dados['total_pagar']  = $this->Tratamentos_model->get_total_pagar($registro->lancamento_id);
            $this->_dados['transacoes_agilpay']  = $this->Tratamentos_model->get_transacoes_agilpay($id);
            $this->_dados['config_agilpay_vendedor_id'] = $this->Tratamentos_model->get_row(array('id' => 35), 'configuracao');
        }

        //Historico de edição do tratamento
        $this->_dados['historico_edicao']  = $this->Tratamentos_model->get_historico_edicao_tratamento($id);
        $this->_dados['historico_unificar_paciente']  = $this->Tratamentos_model->get_historico_unificar_paciente($id);
        $this->_dados['historico_voltar_condicao']  = $this->Tratamentos_model->get_historico_voltar_condicao($id);

        //Verificação da Notificação de Evoluções em Aberto
        if($evolucao){
            $this->load->library('Evolucao_aberto');

            foreach ($evolucao as $evol){
                if($evol->agenda_id){
                    $this->evolucao_aberto->verificacao($evol->agenda_id);
                }
            }
        }

        $this->load->view( $this->_layout, $this->_dados );
    }

    public function remover_historico_img(){
        $id_historico   = $this->input->post('id');
        $img_remover    = $this->input->post('img_remover');

        $historico = $this->Tratamentos_model->get_by_id_table('tratamento_antes_depois', array('id' => $id_historico));

        $dados = array(
                        'antes' => $historico->antes,
                        'data_antes' => $historico->data_antes,
                        'depois' => $historico->depois,
                        'data_depois' => $historico->data_depois,
                        'tratamento_id' => $historico->tratamento_id,
                        'usuario_id' => $historico->usuario_id,
                        'modificacao' => $historico->modificacao,
                        'deleted' => 1
                    );
        $this->Tratamentos_model->insert_table('tratamento_antes_depois', $dados);

        if ($img_remover == 'antes') {
            $dados['antes'] = '';
            $dados['data_antes'] = null;
        } else {
            $dados['depois'] = '';
            $dados['data_depois'] = null;
        }

        if ($dados['data_antes'] != '' || $dados['data_depois'] != '') {
            $dados['deleted'] = 0;
        }

        $dados['usuario_id'] = $this->session->userdata('user_id');

        if ($this->Tratamentos_model->update_table('tratamento_antes_depois', $dados, array('id' => $id_historico))) {
            $retorno = array('message' => 'Imagem removida com sucesso!');
            echo json_encode($retorno);
        } else {
            $retorno = array('message' => 'Erro ao remover imagem!');
            echo json_encode($retorno);
        }

    }

    private function verificar_evolucao($id_tratamento){
        $evolucoes = $this->db->order_by('data', 'asc')->where('tratamento_id', $id_tratamento)->where('sessao >', 0)->where('data !=', '0000-00-00')->get('evolucao')->result();
        $sessao = 1;

        foreach ($evolucoes as $ev) {
            $this->Tratamentos_model->update_table('evolucao', array('sessao' => $sessao), array('id' => $ev->id));

            $sessao++;
        }

        $evolucoes_atual = $this->db->order_by('data', 'asc')->where('tratamento_id', $id_tratamento)->where('sessao >', 0)->where('data', '0000-00-00')->get('evolucao')->result();

        foreach ($evolucoes_atual as $key => $eva) {
            $evolucao_get = $this->db->where('tratamento_id', $id_tratamento)->where('sessao', $eva->sessao)->where('data', '0000-00-00')->get('evolucao')->row();

            if($evolucao_get){
                $this->Tratamentos_model->update_table('evolucao', array('sessao' => $sessao), array('id' => $evolucao_get->id));
            }
            $sessao++;
        }
    }

    /**
     * BUSCAR EVOLUCAO QUE DEVE SER TRANSFERIDA PARA OUTRO TRATAMENTO
     */
    public function buscar_evolucao(){
        $tratamento     = $this->input->post('tratamento');
        $id_evolucao    = $this->input->post('id_evolucao');

        $info_tratamento = $this->Tratamentos_model->get_by_id_table('tratamento', array('id' => $tratamento));
        $info['evolucao_atual'] = $this->Tratamentos_model->get_evolucao( array( 't.id' => $tratamento, 'e.id' => $id_evolucao ) );
        $info['tratamentos'] = $this->Tratamentos_model->get_all( array('t.id !=' => $tratamento, 't.sessoes_restantes >' => 0, 't.paciente_id' => $info_tratamento->paciente_id), '', 0, 0, 't.id', 'desc' );

        echo json_encode($info);
    }

    /**
     * TRANSFERIR EVOLUCAO PARA OUTRO TRATAMENTO
     */
    public function transferir_evolucao(){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Transferir Evolução de Tratamentos');

        $tratamento_atual       = $this->input->post('tratamento_atual');
        $evolucao_escolhido     = $this->input->post('evolucao_escolhido');
        $tratamento_escolhido   = $this->input->post('tratamento_escolhido');

        # controladora de mensagem
        $msg = 0;

        # busca dados de evolução atual
        $evolucao_atual = $this->Tratamentos_model->get_by_id_table('evolucao', array('tratamento_id'=>$tratamento_atual, 'id'=>$evolucao_escolhido));

        # pega a evolução atual e troca o tratamento_id pelo escolhido
        $msg .= $this->Tratamentos_model->update_table('evolucao', array('tratamento_id'=>$tratamento_escolhido), array('tratamento_id'=>$tratamento_atual, 'id'=>$evolucao_escolhido));

        # mudar na agenda o tratamento_id para o tratamento escolhido
        $msg .= $this->Tratamentos_model->update_table('agenda', array('tratamento_id'=>$tratamento_escolhido), array('id'=>$evolucao_atual->agenda_id));

        if($evolucao_atual->status != "FJ" and $evolucao_atual->status != "DP" and $evolucao_atual->status != "NC"){
            # insere uma nova linha no tratamento que a evolução foi transferida
            $dados_evolucao_atual = array(
                'sessao'            => $evolucao_atual->sessao,
                'profissional_id'   => $evolucao_atual->profissional_id,
                'tratamento_id'     => $evolucao_atual->tratamento_id
            );

            $insert_evolucao = $this->Tratamentos_model->insert_table( 'evolucao', $dados_evolucao_atual );
            $msg .= $insert_evolucao;

            # ultimo id da evolucao inserida do tratamento atual
            $ult_id_evolucao = $insert_evolucao;

            # busca os procedimentos que o tratamento atual possui
            $procedimentos = $this->Tratamentos_model->get_all_table('tratamento_has_procedimento', array('tratamento_id' => $tratamento_atual));

            foreach($procedimentos as $procedimento){
                # insere os procedimentos do tratamento para a ultima evolução inserida
                $msg .= $this->Tratamentos_model->insert_table( 'procedimento_evolucao', array('evolucao_id' => $ult_id_evolucao, 'procedimento_id' => $procedimento->procedimento_id) );
            }
        }

        # tratamento atual da evolução que foi transferida
        $tratamento = $this->Tratamentos_model->get_by_id_table('tratamento', array('id'=>$tratamento_atual));

        if($evolucao_atual->status != "FJ" and $evolucao_atual->status != "DP" and $evolucao_atual->status != "NC"){
            # recebe uma sessão restante no tratamento que a evolução foi transferida
            $msg .= $this->Tratamentos_model->update_table('tratamento', array('sessoes_restantes'=>($tratamento->sessoes_restantes + 1)), array('id'=>$tratamento_atual));
        }

        if($evolucao_atual->status != "FJ" and $evolucao_atual->status != "DP" and $evolucao_atual->status != "NC"){
            # busca evolução que vai ser removida
            $evolucao_excluida = $this->Tratamentos_model->get_by_id_table('evolucao', array('tratamento_id'=>$tratamento_escolhido, 'status'=>''));

            # remove guia da evolucao do tratamento que recebeu a nova evolução transferida
            $msg .= $this->Tratamentos_model->delete( 'guia', array('evolucao_id'=>$evolucao_excluida->id) );
            
            # remove procedimento da evolucao do tratamento que recebeu a nova evolução transferida
            $msg .= $this->Tratamentos_model->delete( 'procedimento_evolucao', array('evolucao_id'=>$evolucao_excluida->id) );

            # remove uma evolução do tratamento que recebeu a nova evolução transferida
            $msg .= $this->Tratamentos_model->delete( 'evolucao', array('id'=>$evolucao_excluida->id) );

            # busca dado do tratamento que receberá a evolução
            $tratamento_recebe = $this->Tratamentos_model->get_by_id_table('tratamento', array('id'=>$tratamento_escolhido));

            # faz o tratamento diminuir uma das sessões restantes na evolução que foi recebida(tratamento)
            $msg .= $this->Tratamentos_model->update_table('tratamento', array('sessoes_restantes'=>($tratamento_recebe->sessoes_restantes - 1)), array('id'=>$tratamento_escolhido));
        }

        # verifica se o convenio do tratamento antigo e o novo são diferentes (remove procedimento da evolução)
        if($tratamento->convenio_id != $tratamento_recebe->convenio_id){
            $msg .= $this->Tratamentos_model->delete( 'procedimento_evolucao', array('evolucao_id'=>$evolucao_escolhido) );
        }

        # cria histórico de transferencia da evolução atual
        $dados_historico_evolucao = array(
            'id_evolucao'    => $evolucao_atual->id,
            'data'           => $evolucao_atual->data,
            'sessao'         => $evolucao_atual->sessao,
            'descricao'      => $evolucao_atual->descricao,
            'observacao'     => $evolucao_atual->observacao,
            'status'         => $evolucao_atual->status,
            'profissional_id'=> $evolucao_atual->profissional_id,
            'tratamento_id'  => $evolucao_atual->tratamento_id,
            'data_edicao'    => $evolucao_atual->data_edicao,
            'agenda_id'      => $evolucao_atual->agenda_id,
            'equipamento_id' => $evolucao_atual->equipamento_id,
            'transferido'    => $tratamento_escolhido
        );
        $msg .= $this->Tratamentos_model->insert_table( 'historico_evolucao', $dados_historico_evolucao );

        if($msg > 0){
            $alert = array( 'message' => '<b><i class="glyphicon glyphicon-thumbs-up"></i></b> Evolução transferida com sucesso!', 'return' => 'alert-success' );
        }else{
            $alert = array( 'message' => '<b><i class="glyphicon glyphicon-thumbs-down"></i></b> Erro ao transferir evolução.', 'return' => 'alert-danger' );
        }

        $this->session->set_flashdata( 'alert', $alert );
        redirect( base_url() . 'tratamentos/visualizar/' . $tratamento_atual );
    }

    public function edicao_evolucao( $id = 0 ){
        $evolucao = $this->Tratamentos_model->get_by_id_table( 'evolucao', array( 'id' => $id ) );

        if( $evolucao->data_edicao == NULL || $evolucao->data_edicao != date('Y-m-d')){
            $data['data_edicao'] = date('Y-m-d');
            $message = 'Habilitada a edição da avaliação por periodo de 1 dia';            
        }else{
            $data['data_edicao'] = NULL;
            $message = 'Desabilitada a edição da avaliação';

        }

        if($this->Tratamentos_model->update_table( 'evolucao', $data, array( 'id' => $id ) )){
            $alert = array( 'message' => $message, 'return' => 'alert-success' );
        }else{
            $alert = array( 'message' => 'Houve um erro ao habilitar edição', 'return' => 'alert-danger' );
        }

        $this->session->set_flashdata( 'alert', $alert );
        redirect( base_url() . 'tratamentos/visualizar/' . $evolucao->tratamento_id );
    }

    public function editar_avaliacao( $id = 0, $id_tratamento = 0 ){
        $avaliacao = $this->Tratamentos_model->get_by_id_table( 'avaliacao', array( 'id' => $id ) );

        if($avaliacao){
            if( $avaliacao->data_edicao == NULL || $avaliacao->data_edicao != date('Y-m-d') ){
                $data['data_edicao'] = date('Y-m-d');
                $message = 'Habilitada a edição da avaliação por periodo de 1 dia';
            }else{
                $data['data_edicao'] = NULL;
                $message = 'Desabilitada a edição da avaliação';
            }

            if($this->Tratamentos_model->update_table( 'avaliacao', $data, array( 'tratamento_id' => $id_tratamento ) )){
                $alert = array( 'message' => $message, 'return' => 'alert-success' );
            }else{
                $alert = array( 'message' => 'Houve um erro ao habilitar edição', 'return' => 'alert-danger' );
            }
        }else{
            $data['tratamento_id']  = $id_tratamento;
            $data['data']           = date('Y-m-d');
            $data['data_edicao']    = date('Y-m-d');

            if($this->Tratamentos_model->insert_table( 'avaliacao', $data )){
                $alert = array( 'message' => 'Habilitada a edição da avaliação por periodo de 1 dia', 'return' => 'alert-success' );
            }else{
                $alert = array( 'message' => 'Houve um erro ao habilitar edição', 'return' => 'alert-danger' );
            }
        }


        $this->session->set_flashdata( 'alert', $alert );
        redirect( base_url() . 'tratamentos/visualizar/' . $id_tratamento );
    }

    public function visualizar_impressao( $id = 0, $tipo = 0){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , 'visualizar', 'Visualizar Tratamento');

        $this->load->helper('texto_helper');
        
        $tratamento     = $this->Tratamentos_model->get_tratamento( array( 't.id' => $id ) );
        $evolucao       = $this->Tratamentos_model->get_evolucao( array( 't.id' => $id ) );

        $this->_dados['titulo']         = 'Visualizar Tratamento';

        $this->_dados['tratamento']     = $tratamento;
        $this->_dados['evolucao']       = $evolucao;

        
        if(isset($tratamento->categoria_tratamento_id)){
            switch ($tratamento->categoria_tratamento_id) {
                case 1:
                    $this->_dados['view'] = 'tratamentos/_view/avista';
                    break;
                case 2:
                    $this->_dados['view'] = 'tratamentos/_view/pacote';
                    break;
                case 3:
                    $this->_dados['view'] = 'tratamentos/_view/mensalidade';
                    break;
                case 4:
                    $this->_dados['view'] = 'tratamentos/_view/convenio';
                    break;
                case 5:
                    $this->_dados['view'] = 'tratamentos/_view/combo';
                    break;
            }
        }

        # Historico de alta no tratamento
        $alta_tratamento = $this->Tratamentos_model->get_by_id_table('historico_alta_tratamento', array("tratamento_id" => $id));
        if(isset($alta_tratamento->usuario_id)){
            # Profissional
            $usuario = $this->Tratamentos_model->get_by_id_table('usuario', array("id" => $alta_tratamento->usuario_id));
            $nm_usuario = $usuario->first_name ." ". $usuario->last_name;
            $alta_tratamento->usuario = $nm_usuario;
        }
        $this->_dados['historico_alta_tratamento'] = $alta_tratamento ? $alta_tratamento : null;

        $this->_dados['avaliacao']      = $this->Tratamentos_model->get_avaliacao_form( array( 'tratamento_id' => $id ) );
        $this->_dados['mensalidade']  = $this->Tratamentos_model->get_all_table('mensalidade', array('tratamento_id' => $id));
        $this->_dados['procedimentos']      = $this->Tratamentos_model->get_procedimentos_combo(array('tratamento_id'=>$id));

        $_config = $this->Tratamentos_model->get_all_table('configuracao',array('modulo'=>'dados'));

        $cidade = $this->Tratamentos_model->get_by_id_table('cidade',array('id'=>$_config[5]->valor));
        $estado = $this->Tratamentos_model->get_by_id_table('estado',array('id'=>$_config[4]->valor));

        $this->_dados['config'] = $_config;

        $this->_dados['cidade'] = $cidade;
        $this->_dados['estado'] = $estado;
        $this->_dados['tipo'] = $tipo; 

        ob_start();
        $this->load->view( 'tratamentos/visualizar_impressao', $this->_dados );

        $this->load->library('Pdf');
        $this->pdf->gerar_pdf();
    }

    public function busca_profissional(){
        $especialidade_id   = $this->input->post( 'especialidade_id' );

        $profissionais = $this->Tratamentos_model->get_profissionais( array( 'e.id' => $especialidade_id, 'p.status' => 1 ));

        echo json_encode( $profissionais );
    }


    public function controle_presenca($id = 0){
        $this->_dados['_config_layout'] = $this->Tratamentos_model->get_all_table('configuracao',array('modulo'=>'layout'));
        $this->_dados['_config_dados'] = $this->Tratamentos_model->get_all_table('configuracao',array('modulo'=>'dados'));
        $this->_dados['tratamento'] = $this->Tratamentos_model->get_tratamento(array('t.id'=>$id));

        ob_start();
        $this->load->view( 'tratamentos/controle_presenca', $this->_dados );
        $this->load->library('Pdf');
        $this->pdf->gerar_pdf(true);
    }

    public function voltar_condicao($evolucao_id, $agenda_id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , $this->router->method, 'Voltar Condição');

        $evolucao   = $this->Tratamentos_model->get_by_id_table('evolucao', array('id'=>$evolucao_id));
        $tratamento = $this->Tratamentos_model->get_by_id_table('tratamento',array('id'=>$evolucao->tratamento_id));


        /*Salva histórico volta condição INÍCIO*/
        $info_evolucao = $this->Tratamentos_model->get_info_evolucao($agenda_id);
        $data_acao = new Datetime();
        $data_acao = $data_acao->format('Y-m-d H:i:s');
        $usuario_acao = $this->session->userdata('user_id');

        foreach ($info_evolucao as $key => $ie) {
            
            $dados_historico = array('data_acao'        => $data_acao,
                                     'usuario_id'       => $usuario_acao,
                                     'data'             => $ie->data_inicio,
                                     'status'           => $ie->status,
                                     'sessao'           => $ie->sessao,
                                     'descricao'        => $ie->descricao,
                                     'observacao'       => $ie->observacao,
                                     'profissional_id'  => $ie->profissional_id,
                                     'hora_inicio'      => $ie->hora_inicio,
                                     'hora_fim'         => $ie->hora_fim,
                                     'tratamento_id'    => $ie->tratamento_id,
                                     'finalizado_biometria' => $ie->finalizado_biometria,);
            $this->Tratamentos_model->insert_table('historico_voltar_condicao', $dados_historico);
        }
        /*Salva histórico volta condição FIM*/

            if($evolucao->sessao == 0){
                $this->Tratamentos_model->delete('procedimento_evolucao', array('evolucao_id'=>$evolucao_id));
                $this->Tratamentos_model->delete('equipamento_evolucao', array('evolucao_id' => $evolucao_id));

                $agenda = $this->Tratamentos_model->get_agendamento_condicao($evolucao_id);
                    
                if ($agenda->tipo_agendamento_id == 2 || $agenda->tipo_agendamento_id == 3) {
                    $this->Tratamentos_model->update_table('agenda', array('status_consulta' => null, 'status' => 0), array('id'=>$agenda->id));
                }

                //Verificação caso de reposição de sessões
                $this->Tratamentos_model->update_table('agenda', array('evolucao_id_reposicao' => null), array('evolucao_id_reposicao' => $evolucao_id));

                if($this->Tratamentos_model->delete('evolucao', array('id'=>$evolucao_id))){
                    $alert = array('message'=>'Foi restaurada a condição!','return'=>'alert-success');
                }else{
                    $alert = array('message'=>'Houve um erro ao restaurar condição!','return'=>'alert-danger');
                }
            }else{

                //Verifica se o tratamento é do tipo Combo
                if ($tratamento->categoria_tratamento_id == 5) {

                    $movimentacao = $this->Tratamentos_model->get_all_table('movimentacao_combo', array('tratamento_id' => $tratamento->id, 'evolucao_id' => $evolucao->id));

                    foreach ($movimentacao as $key => $mov) {

                        //Seleciona o combo referente a evolução
                        $combo = $this->Tratamentos_model->get_by_id_table('combo', array('id' => $mov->combo_id));

                        // Soma as evoluções restantes do combo com as utilizadas para retornar ao número correto de evoluções
                        $atualiza_sessoes = $mov->sessoes_utilizadas + $combo->sessoes_restantes;

                        $dados = array(
                                        'sessoes_restantes' => $atualiza_sessoes,
                                        );

                        $this->Tratamentos_model->update_table('combo', $dados, array('id' => $mov->combo_id));

                        $this->Tratamentos_model->delete('movimentacao_combo', array('id' => $mov->id));
                    }

                    
                    $limpa_dados = array(
                                    'data'              => '0000-00-00',
                                    'descricao'         => null,
                                    'observacao'        => null,
                                    'status'            => '',
                                    'profissional_id'   => $tratamento->profissional_id,
                                    'data_edicao'       => null,
                                    'agenda_id'         => null,
                                    'equipamento_id'    => null
                                    );

                    if($this->Tratamentos_model->update_table('evolucao', $limpa_dados, array('tratamento_id'=>$evolucao->tratamento_id, 'agenda_id'=>$agenda_id))){
                        
                        $sessoes = $this->Tratamentos_model->getSessoes($evolucao->tratamento_id);

                        $this->Tratamentos_model->update(array('sessoes_restantes'=>$sessoes->restantes), $tratamento->id);

                        $alert = array('message'=>'Foi restaurada a condição!','return'=>'alert-success');
                    } else {
                        $alert = array('message'=>'Houve um erro ao restaurar condição!','return'=>'alert-danger');
                    }

                } else {

                    $dados = array(
                        'data'              => '0000-00-00',
                        'descricao'         => null,
                        'observacao'        => null,
                        'status'            => '',
                        'profissional_id'   => $tratamento->profissional_id,
                        'data_edicao'       => null,
                        'agenda_id'         => null,
                        'equipamento_id'    => null
                        );

                    $agenda = $this->Tratamentos_model->get_agendamento_condicao($evolucao_id);

                    if ($agenda->tipo_agendamento_id == 2 || $agenda->tipo_agendamento_id == 3) {
                        $this->Tratamentos_model->update_table('agenda', array('status_consulta' => null, 'status' => 0), array('id'=>$agenda->id));
                    }

                    if($this->Tratamentos_model->update_table('evolucao', $dados, array('id'=>$evolucao_id))){

                        $sessoes = $this->Tratamentos_model->getSessoes($evolucao->tratamento_id);

                        $this->Tratamentos_model->update(array('sessoes_restantes'=>$sessoes->restantes), $tratamento->id);

                        $alert = array('message'=>'Foi restaurada a condição!','return'=>'alert-success');
                    }else{
                        $alert = array('message'=>'Houve um erro ao restaurar condição!','return'=>'alert-danger');
                    }
                }
        }

        //Verificação de evolução em aberto
        $this->load->library('Evolucao_aberto');
        $this->evolucao_aberto->verificacao($agenda_id);

        $this->session->set_flashdata('alert',$alert);
        redirect(base_url() . 'tratamentos/visualizar/' . $tratamento->id);
    }

    public function anexos($tratamento_id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , 'visualizar', 'Visualizar Tratamento');

        $this->_dados['pagina'] = 'tratamentos/anexos/anexos';
        $this->_dados['titulo'] = 'Anexos';
        $this->_dados['anexos'] = $this->Tratamentos_model->get_all_table('anexo',array('tratamento_id'=>$tratamento_id), 0, 0, 'modificacao', 'desc');
        $this->_dados['tratamento_id'] = $tratamento_id;

        $this->load->view($this->_layout, $this->_dados);
    }

    public function anexar($tratamento_id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , 'visualizar', 'Visualizar Tratamento');

        // $tratamento_id  = $this->input->post('tratamento_id');
        $descricao = $this->input->post('descricao');

        $filedata = array();

        if(!$_FILES){
            $alert = array('message'=>'O tamanho do arquivo excede o limite máximo!', 'return'=>'alert-danger');
            $this->session->set_flashdata('alert',$alert);
            redirect(base_url() . 'tratamentos/anexos/' . $tratamento_id);
        }

        foreach ($_FILES['anexos']['name'] as $key => $value) {
            $filename = array();
            $config = array();

            $filename = array(
                'name'      => $_FILES['anexos']['name'][$key],
                'type'      => $_FILES['anexos']['type'][$key],
                'tmp_name'  => $_FILES['anexos']['tmp_name'][$key],
                'error'     => $_FILES['anexos']['error'][$key],
                'size'      => $_FILES['anexos']['size'][$key]
                );

            $_FILES['filename'] = $filename;

            $config['upload_path']      = 'uploads/anexos';
            $config['allowed_types']    = '*';
            $config['max_size']         = '151200';
            $config['encrypt_name']     = true;

            $this->load->library('upload', $config);

            if ( $this->upload->do_upload('filename')) {       
                $data = $this->upload->data();   

                $filedata[] = array(
                    'nome'          => $data['file_name'],
                    'nome_original' => $_FILES['anexos']['name'][$key],
                    'tipo'          => $_FILES['anexos']['type'][$key],
                    'tamanho'       => $_FILES['anexos']['size'][$key],
                    'tratamento_id' => $tratamento_id,
                    'descricao'     => $descricao
                    );
            }else{
                $alert = array('message'=>$this->upload->display_errors(), 'return'=>'alert-danger');
                $this->session->set_flashdata('alert',$alert);
                redirect(base_url() . 'tratamentos/anexos/' . $tratamento_id);
            }
        }

        if($this->Tratamentos_model->insert_table_batch('anexo', $filedata)){
            $alert = array('message'=>'Arquivos anexados com sucesso!', 'return'=>'alert-success');
        }else{
            $alert = array('message'=>'Houve um erro ao anexar arquivos!', 'return'=>'alert-danger');
        }

        $this->session->set_flashdata('alert',$alert);

        redirect(base_url() . 'tratamentos/anexos/' . $tratamento_id);
    }

    public function remover_anexo($id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class , 'visualizar', 'Visualizar Tratamento');
        
        $anexo = $this->Tratamentos_model->get_by_id_table('anexo', array('id'=>$id));

        unlink('uploads/anexos/' . $anexo->nome);

        if($this->Tratamentos_model->delete('anexo', array('id'=>$id))){
            $alert = array('message'=>'Arquivo removido com sucesso!', 'return'=>'alert-success');
        }else{
            $alert = array('message'=>'Houve um erro ao remover arquivo!', 'return'=>'alert-danger');
        }

        $this->session->set_flashdata('alert',$alert);
        redirect(base_url() . 'tratamentos/anexos/' . $anexo->tratamento_id);
    }

    /**
     * JSON com lista de procedimentos 
     */
    public function getProcedimentos(){
        $convenio_id = $this->input->post('convenio_id');

        if(!$convenio_id){
            $convenio_id = 1;
        }

        $procedimentos = $this->Tratamentos_model->get_all_table('procedimento', array('convenio_id' => $convenio_id, 'deleted' => 0), 0, 0, 'ordem', 'asc');

        echo json_encode($procedimentos);
    }

    /**
     * Retorna procedimento evolucao na listagem de evoluções para edição do mesmo
     */
    public function get_procedimentos_evolucao(){
        $evolucao_id = $this->input->post('evolucao_id');
        $evolucao = $this->Tratamentos_model->get_by_id_table('evolucao', array('id' => $evolucao_id));
        $tratamento = $this->Tratamentos_model->get_by_id_table('tratamento', array('id' => $evolucao->tratamento_id));
        $procedimentos_evolucoes = array();
        $pro_evo = $this->Tratamentos_model->get_all_table('procedimento_evolucao', array('evolucao_id' => $evolucao->id));

        if(!$pro_evo){
            $proc = $this->Tratamentos_model->get_all_table('procedimento', array('convenio_id' => $tratamento->convenio_id, 'status' => 1), 0, 0, 'ordem', 'asc');

            $procedimentos_evolucoes['proc_evolucao'] = array();

            foreach($proc as $key2 => $pr){
                $procedimentos_evolucoes['procedimentos'][$pr->id] = $pr->codigo.' - '.$pr->procedimento. ($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? ' (R$ '.number_format($pr->valor, 2, ',', '.').')' : '');
            }

        }else{
            foreach($pro_evo as $p){
                $procedimentos_evolucoes['proc_evolucao'][$p->procedimento_id] = $p->procedimento_id;

                if($tratamento){
                    $proc = $this->Tratamentos_model->get_all_table('procedimento', array('convenio_id' => $tratamento->convenio_id, 'status'=>1), 0, 0, 'ordem', 'asc');

                    foreach ($proc as $key2 => $pr) {
                        $procedimentos_evolucoes['procedimentos'][$pr->id] = $pr->codigo.' - '.$pr->procedimento. ($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? ' (R$ '.number_format($pr->valor,2,',','.').')' : '');

                        if(!isset($procedimentos_evolucoes['procedimentos'][$p->procedimento_id])) {
                            $proc_deleted = $this->Tratamentos_model->get_row(array('id'=>$p->procedimento_id), 'procedimento');
                            $procedimento_situcao = null;

                            if($proc_deleted->status == 0){
                                $procedimento_situcao = ' (Desativado)';
                            }

                            if($proc_deleted->deleted == 1){
                                $procedimento_situcao = ' (Removido)';
                            }

                            $procedimentos_evolucoes['procedimentos'][$p->procedimento_id] = $proc_deleted->codigo.' - '.$proc_deleted->procedimento. ($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? ' (R$ '.number_format($proc_deleted->valor,2,',','.').')' : '').$procedimento_situcao;
                        }
                    }
                }
            }
        }

        echo json_encode($procedimentos_evolucoes);
    }

    /**
     * Retorna descrição do procedimento evolucao na listagem de evoluções
     */
    public function get_procedimentos_descricao(){
        $evolucao_id = $this->input->post('evolucao_id');
        $procedimento_descricao = '';
        $descricao_proc_evol = $this->Tratamentos_model->get_procedimento_evolucao($evolucao_id);

        if($descricao_proc_evol){
            foreach($descricao_proc_evol as $key2 => $proc){
                $procedimento_descricao = $procedimento_descricao.$proc->codigo.' - '.$proc->procedimento;

                if(isset($descricao_proc_evol[$key2+1])){
                    $procedimento_descricao .= ', ';
                }
            }
        }

        echo json_encode($procedimento_descricao);
    }

    /**
     * Retorna o valor de um procedimento especifico
     */
    public function getRowProcedimento(){
        $id = $this->input->post('id');
        $valor = 0;

        if($id){   
            foreach ($id as $key => $value) {
                $procedimento = $this->Tratamentos_model->get_by_id_table('procedimento', array('id'=>$value));
                $valor += $procedimento->valor;
            }
        }

        $return = array(
            'valor_format' => number_format($valor,2,',','.'),
            'valor' => $valor, 
        );

        echo json_encode($return);
    }

    /**
     * Retorna a porcentagem de um profissional especifico
     */
    public function get_row_profissional(){
        $id = $this->input->post('id');

        if($id){   
            foreach($id as $key => $value){
                $profissional = $this->Tratamentos_model->get_by_id_table('profissional', array('id'=>$value));
            }
        }

        $return = array(
            'nome' => $profissional->nome,
        );

        echo json_encode($return);
    }

    /**
     * Campos do Formulário
     */
    public function viewForm($form, $id_tratamento = 0){
        $this->_dados['medicos']['']                = 'Selecione...';
        $this->_dados['tipos_pagamento']['']        = 'Selecione...';
        $this->_dados['categorias_conta']['']       = 'Selecione...';
        $this->_dados['contas']['']                 = 'Selecione...';
        $this->_dados['subcategorias']['']          = 'Selecione...';
        $this->_dados['carater'][''] = 'Selecione...';
        $this->_dados['acidente'][''] = 'Selecione...';
        $this->_dados['tipo_atendimento'][''] = 'Selecione...';
        $this->_dados['set_tipo_pagamento'] = 10;
        
        $profissionais = $this->Tratamentos_model->get_all_table('profissional', array('status' => 1), 0, 0, 'nome', 'asc');

        foreach ($profissionais as $p){
            $this->_dados['profissionais'][$p->id] = $p->nome;
        }

        $tratamento = $this->Tratamentos_model->get_by_id_table('tratamento',array('id'=>$id_tratamento));

        $tipos_pagamento           = $this->Tratamentos_model->get_all_table( 'tipo_pagamento', array(), 0, 0, 'nome', 'asc');
        foreach ($tipos_pagamento as $c) $this->_dados['tipos_pagamento'][$c->id] = $c->nome;

        $medicos                = $this->Tratamentos_model->get_all_table( 'medico', array(), 0, 0, 'nome', 'asc' );
        foreach ($medicos as $m) $this->_dados['medicos'][$m->id] = $m->nome;

        $carater = $this->Tratamentos_model->get_all_table( 'tiss_carater', array(), 0, 0, 'nome', 'asc' );
        foreach ($carater as $car) $this->_dados['carater'][$car->id] = $car->nome.' ('.$car->codigo.')';

        $tipo_atendimento = $this->Tratamentos_model->get_all_table( 'tiss_tipo_atendimento', array(), 0, 0, 'id', 'asc' );
        foreach ($tipo_atendimento as $ta) $this->_dados['tipo_atendimento'][$ta->id] = $ta->codigo.' - '.$ta->nome;

        $acidente = $this->Tratamentos_model->get_all_table( 'tiss_acidente', array(), 0, 0, 'id', 'asc' );
        foreach ($acidente as $ac) $this->_dados['acidente'][$ac->id] = $ac->codigo.' - '.$ac->nome;

        $this->_dados['indicacao_clinica'] = array('name' => 'indicacao_clinica', 'class' => 'form-control', 'id' => 'indicacao_clinica', 'value' => set_value('indicacao_clinica'));

        $conta           = $this->Tratamentos_model->get_all_table( 'conta', array('status'=>1) );
        foreach ($conta as $c) $this->_dados['contas'][$c->id] = $c->nome;

        $categoria           = $this->Tratamentos_model->get_all_table( 'tipo_plano_conta', array('id !=' => 3, 'deleted' => 0) );
        foreach ($categoria as $c) $this->_dados['categorias_conta'][$c->id] = $c->nome;

        $this->_dados['planos'] = array(
            '' => 'Selecione...',
            1 => '1 Mês',
            2 => '2 Meses',
            3 => '3 Meses',
            4 => '4 Meses',
            5 => '5 Meses',
            6 => '6 Meses',
            7 => '7 Meses',
            8 => '8 Meses',
            9 => '9 Meses',
            10 => '10 Meses',
            11 => '11 Meses',
            12 => '12 Meses'
        );

        $this->_dados['dias_semana'] = array(
            1 => 'Segunda-Feira',
            2 => 'Terça-Feira',
            3 => 'Quarta-Feira',
            4 => 'Quinta-Feira',
            5 => 'Sexta-Feira',
            6 => 'Sábado',
        );

        $this->_dados['data_inicio']        = array( 'name' => 'data_inicio', 'class' => 'form-control date', 'id' => 'data_inicio_tratamento', 'value' => set_value('data_inicio', isset($tratamento->data_inicio) ? $tratamento->data_inicio : null) );

        $this->_dados['sessoes_fisioterapia']= array( 'name' => 'sessoes_fisioterapia', 'class' => 'form-control', 'id' => 'sessoes_fisioterapia', 'maxlength' => '3', 'value' => set_value('sessoes_fisioterapia', isset($tratamento->sessoes_totais) ? $tratamento->sessoes_totais : null) );
        $this->_dados['sessoes_retorno'] = array('name' => 'sessoes_retorno', 'class' => 'form-control', 'id' => 'sessoes_retorno', 'maxlength' => '2', 'value' => set_value('sessoes_retorno'));
        $this->_dados['ta_retorno'] = $this->Tratamentos_model->get_row(array('id' => 17), 'tipo_agendamento');

        $this->_dados['data_vencimento']    = array( 'name' => 'data_vencimento', 'class' => 'form-control date', 'id' => 'data_vencimento', 'value' => set_value('data_vencimento') );
        $this->_dados['data_limite']        = array( 'name' => 'data_limite', 'class' => 'form-control date', 'id' => 'data_limite', 'value' => set_value('data_limite') );
        $this->_dados['num_guia']           = array( 'name' => 'num_guia', 'class' => 'form-control', 'id' => 'num_guia', 'value' => set_value('num_guia', isset($tratamento->num_guia) ? $tratamento->num_guia : null));
        $this->_dados['cid']           = array( 'name' => 'cid', 'class' => 'form-control', 'id' => 'cid', 'value' => set_value('cid', isset($tratamento->cid) ? $tratamento->cid : null) );
        // $this->_dados['sessoes']            = array( 'name' => 'sessoes', 'id' => 'sessoes', 'class' => 'form-control', 'value' => set_value('sessoes') );
        $this->_dados['valor']              = array( 'name' => 'valor', 'id' => 'valor', 'class' => 'form-control money', 'value' => set_value('valor', isset($tratamento->valor) ? $tratamento->valor : null) );
        $this->_dados['subtotal']           = array( 'name' => 'subtotal', 'id' => 'subtotal', 'readonly'=>'readonly', 'class' => 'form-control money', 'value' => set_value('subtotal') );
        $this->_dados['total']              = array( 'name' => 'total', 'id' => 'total', 'readonly'=>'readonly', 'class' => 'form-control money', 'value' => set_value('total') );
        $this->_dados['desconto_real'] = array('name' => 'desconto', 'id' => 'desconto', 'class' => 'form-control money', 'value' => set_value('desconto'));
        $this->_dados['desconto_porcento'] = array('name' => 'desconto_porcento', 'id' => 'desconto_porcento', 'class' => 'form-control money', 'value' => set_value('desconto_porcento'));

        //Permissão para desabilitar a opção de dar descontos no tratamento
        if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'desconto_tratamento', 'Desativar Opção de Descontos e Valor Únitário no Tratamento')){
            $this->_dados['desconto_real']['readonly'] = 'readonly';
            $this->_dados['desconto_porcento']['readonly'] = 'readonly';
            $this->_dados['valor']['readonly'] = 'readonly';
        }

        $this->_dados['acrescimo']           = array( 'name' => 'acrescimo', 'id' => 'acrescimo', 'class' => 'form-control money', 'value' => set_value('acrescimo') );

        $this->_dados['data_autorizacao']   = array( 'name' => 'data_autorizacao', 'id' => 'data_autorizacao', 'class' => 'form-control data', 'value' => set_value('data_autorizacao', isset($tratamento->data_autorizacao) ? $tratamento->data_autorizacao : null) );
        $this->_dados['senha']              = array( 'name' => 'senha', 'id' => 'senha', 'class' => 'form-control', 'value' => set_value('senha', isset($tratamento->senha) ? $tratamento->senha : null) );
        $this->_dados['vencimento_autorizacao']   = array( 'name' => 'vencimento_autorizacao', 'id' => 'vencimento_autorizacao', 'class' => 'form-control data', 'value' => set_value('vencimento_autorizacao', isset($tratamento->vencimento_autorizacao) ? $tratamento->vencimento_autorizacao : null) );

        //Cartão
        $this->_dados['cartao_padrao'] = array('type' => 'hidden', 'name' => 'cartao_padrao', 'id' => 'cartao-padrao');

        //Opção de várias formas de pagamentos
        $this->_dados['entradas'] = array('' => 'Não', 'Sim' => 'Sim');
        $this->_dados['valor_entrada'] = array('name' => 'valor_entrada[]', 'class' => 'form-control money valor_entrada');
        $this->_dados['venc_entrada'] = array('name' => 'venc_entrada[]', 'class' => 'form-control date venc_entrada', 'placeholder' => 'dd/mm/aaaa');
        $this->_dados['parcelas_entrada'] = array('type' => 'number', 'name' => 'parcelas_entrada[]', 'class' => 'form-control parcelas_entrada', 'value' => '1');
        $this->_dados['adicionar_entrada']  = array('type' => 'button', 'class' => 'btn btn-success adicionar_entrada', 'content' => '+ Adicionar mais uma forma de pagamento');
        $this->_dados['remover_entrada']  = array('type' => 'button', 'class' => 'btn btn-danger remover_entrada', 'content' => 'X', 'style' => 'position:relative; top:20px; float:right; width:60px;');
        $this->_dados['cartao_padrao_entrada'] = array('type' => 'hidden', 'name' => 'cartao_padrao_entrada[]', 'class' => 'cartao-padrao-entrada');

        //Bloqueio FJ Pacote
        $this->_dados['limite_fj_periodo'] = array('type' => 'number', 'name' => 'limite_fj_periodo', 'class' => 'form-control', 'id' => 'limite_fj_periodo', 'value' => set_value('limite_fj_periodo') );
        $this->_dados['periodo_bloqueio'] = array('' => 'Selecione...', 1 => 'Mês Vigente', 2 => 'Dias Corridos');

        //Configuração para verificar se o tipo de tratamento convenio terá opção de lançar no fluxo de caixa
        $this->_dados['convenio_financeiro_config'] = $this->Tratamentos_model->get_row(array('id' => 42), 'configuracao');

        //Campo valor credito paciente
        $this->_dados['credito_utilizado'] = array('name' => 'credito_utilizado', 'class' => 'form-control money', 'id' => 'credito-utilizado', 'value' => set_value('credito_utilizado'));

        $this->form_novo_medico();

        //Campos Pagamento Online
        $this->form_agilpay();

        $this->load->view('tratamentos/_form/'.$form, $this->_dados);
    }

    public function formasPagamentoJson(){
        $id_categoria   = (int) $this->input->post('id_categoria');
        $sessao         = (int) $this->input->post('sessao');

        $valor          = (float) str_replace(array('.',','), array('','.'), $this->input->post('valor'));
        $desconto       = (float) str_replace(array('.',','), array('','.'), $this->input->post('desconto'));
        $acrescimo      = (float) str_replace(array('.',','), array('','.'), $this->input->post('acrescimo'));
        $plano          = (int) $this->input->post('plano');
        $data_vencimento = $this->input->post('data_vencimento');
        $controle       = $this->input->post('controle');
        $total_entrada = (float) $this->input->post('total_entrada');

        if($controle == 'true'){
            $controleDados       = array(
                'dias' => $this->input->post('dias'),
                'data_inicio' => $this->input->post('data_inicio'),
                'plano' => $this->input->post('plano'),
                );
        }else{
            $controleDados = array();
        }

        if($id_categoria == 2 || $id_categoria == 1 || $id_categoria == 4){
            $valor = $valor * $sessao;
        }

        if($data_vencimento){
            $d = explode('/', $data_vencimento);
            $data_vencimento = $d[2] . '-' . $d[1] . '-' . $d[0];
        }else{
            $data_vencimento = date('Y-m-d');
        }

        //Caso Mensalidade
        if($id_categoria == 3){
            $valor = $valor - ($total_entrada / $plano);

        }else{
            $valor = $valor - $total_entrada;
        }

        if($total_entrada > 0 && ($valor - $desconto) <= 0){
            echo json_encode(false);

        }else if(($valor - $desconto) < 0){
            echo json_encode(array('negativo' => true));

        }else{
            $forma_pagamento = $this->formasPagamento($id_categoria, $valor, $desconto, $acrescimo, $plano, $data_vencimento, $controleDados);

            echo json_encode($forma_pagamento);
        }
    }

    /**
     * Visualizar detalhes de uma avaliação de um tratamento em especifico
     */
    public function avaliacao_visualizar( $id = 0 ){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class, $this->router->method, 'Histórico de Avaliações do Paciente');

        $tratamento = $this->Tratamentos_model->get_tratamento(array('t.id' => $id));

        if($tratamento){
            if(isset($tratamento->categoria_tratamento_id)){
                switch ($tratamento->categoria_tratamento_id) {
                    case 1:
                        $this->_dados['view'] = 'tratamentos/_view/avista';
                        break;
                    case 2:
                        $this->_dados['view'] = 'tratamentos/_view/pacote';
                        break;
                    case 3:
                        $this->_dados['view'] = 'tratamentos/_view/mensalidade';
                        break;
                    case 4:
                        $this->_dados['view'] = 'tratamentos/_view/convenio';
                        break;
                    case 5:
                        $this->_dados['view'] = 'tratamentos/_view/combo';
                        break;
                }
            }
        }

        $this->_dados['visualizar_avaliacao'] = true;
        $this->_dados['pagina'] = 'tratamentos/avaliacao_visualizar';
        $this->_dados['titulo'] = 'Avaliação';
        $this->_dados['tratamento'] = $tratamento;
        $this->_dados['avaliacao'] = $this->Tratamentos_model->get_avaliacao_form(array('tratamento_id'=>$id));

        $this->load->view($this->_layout, $this->_dados);
    }

    /**
     * Controle de sessoes :: Verificando tipo
     */
    private function controleSessoes($dias = null, $data_inicio = null, $plano = null){
        $config_tipo = $this->db->where('id', 19)->get('configuracao')->row();

        if($config_tipo->valor == 0){
            $controle_sessoes = $this->controleSessoesMensal($dias, $data_inicio, $plano);
        
        }else{
            $controle_sessoes = $this->controleSessoesDiasCorridos($dias, $data_inicio, $plano);
        }

        return $controle_sessoes;
    }

    /**
     * Controle de sessoes :: Mensal (Padrão)
     */
    private function controleSessoesMensal($dias = null, $data_inicio = null, $plano = null){
        if($dias && $data_inicio && $plano){
            $plano = $plano - 1;

            $data_inicio = data2famericano($data_inicio);
            $datetime_inicio = new DateTime( $data_inicio );
            $data_limite = new DateTime( $data_inicio );

            if($plano != 0){
                $data_limite->add(new DateInterval('P'.$plano.'M'));
            }

            $interval = $datetime_inicio->diff( new DateTime( $data_limite->format('Y-m-t') ) );
            $intervalo = (int) $interval->format("%a");
            $sessoes = 0;
            $meses = array();
            $di = 0;

            for ($i=0; $i <= $intervalo; $i++) { 
                $data_atual = new DateTime( $data_inicio );
                $data_atual->add(new DateInterval('P'.$i.'D'));

                foreach ($dias as $d => $dia) {
                    if($dia == $data_atual->format('w') && !$this->Tratamentos_model->get_row_feriados($data_atual->format('Y-m-d')) ){
                        $mes = (int) $data_atual->format('m');

                        if(!isset($meses[$mes])){
                            $meses[$mes] = 1;
                        
                        }else{
                            $meses[$mes]++;
                        }

                        $sessoes++;
                    }
                }

                $di++;
            }

            $controle_sessoes = array('sessoes'=>$sessoes,'meses'=>$meses);
            $this->session->set_userdata('controle_sessoes',$controle_sessoes);

        }else{
            $controle_sessoes = array();
        }

        return $controle_sessoes;
    }

    /**
     * Controle de sessoes :: Dias Corridos
     */
    private function controleSessoesDiasCorridos($dias = null, $data_inicio = null, $plano = null){
        if($dias && $data_inicio && $plano){
            $data_inicio = data2famericano($data_inicio);
            $datetime_inicio = new DateTime( $data_inicio );
            $data_limite = new DateTime( $data_inicio );

            if($plano != 0){
                $data_limite->add(new DateInterval('P'.$plano.'M'));
                $data_limite->sub(new DateInterval('P1D'));
            }

            $interval = $datetime_inicio->diff( new DateTime( $data_limite->format('Y-m-d') ) );
            $intervalo = (int) $interval->format("%a");
            $sessoes = 0;
            $meses = array();
            $di = 0;

            for ($i=0; $i <= $intervalo; $i++) { 
                $data_atual = new DateTime( $data_inicio );
                $data_atual->add(new DateInterval('P'.$i.'D'));
                
                foreach ($dias as $d => $dia) {
                    if($dia == $data_atual->format('w') && !$this->Tratamentos_model->get_row_feriados($data_atual->format('Y-m-d')) ){
                        $data_atual_aux = new DateTime( $datetime_inicio->format('Y-m') . '-01' );
                        $data_atual_aux->add(new DateInterval('P'.$i.'D'));
                        $mes = (int) $data_atual_aux->format('m');

                        if(!isset($meses[$mes])){
                            $meses[$mes] = 1;
                        
                        }else{
                            $meses[$mes]++;
                        }

                        $sessoes++;
                    }
                }

                $di++;
            }

            $controle_sessoes = array('sessoes'=>$sessoes,'meses'=>$meses);
            $this->session->set_userdata('controle_sessoes',$controle_sessoes);

        }else{
            $controle_sessoes = array();
        }

        return $controle_sessoes;
    }

    /**
     * Retorna o controle de sessoes de acordo com os parametros informados
     */
    public function controleSessoesJson(){
        $dias = $this->input->post('dias');
        $data_inicio = $this->input->post('data_inicio');
        $plano = (int) $this->input->post('plano');

        echo json_encode($this->controleSessoes($dias, $data_inicio, $plano));
    }

    private function formasPagamento($id_categoria, $valor, $desconto, $acrescimo, $plano = 0, $data_vencimento = null, $controle = null){
        if(!$data_vencimento)
            $data_vencimento = date('Y-m-d');

        $id_categoria   = (int) $id_categoria;
        $valor          = (float) $valor;
        $desconto       = (float) $desconto;
        $acrescimo       = (float) $acrescimo;

        $valor = ($valor - $desconto) + $acrescimo;

        switch ($id_categoria) {
            case 1:
                $forma_pagamento[0] = array(
                    'forma' => 1
                    );

                $datetime    = new DateTime( $data_vencimento );
                $forma_pagamento[0]['valor'][] = number_format($valor, 2, ',','.');
                $forma_pagamento[0]['vencimento'][] = $datetime->format('d/m/Y');

                break;
            case 2:
                $parcelas = 12;

                $i=0;
                for($prazo=1; $prazo<=$parcelas; $prazo++){
                    $forma_pagamento[$i] = array(
                        'forma' => $prazo
                        );

                    $datetime    = new DateTime( $data_vencimento );
                    for($v=0;$v<$prazo;$v++){
                        $forma_pagamento[$i]['valor'][] = number_format($valor/$prazo, 2, ',','.');
                        $forma_pagamento[$i]['vencimento'][] = $datetime->format('d/m/Y');

                        $datetime->add(new DateInterval('P1M'));
                    }
                    $i++;
                }

                break;
            case 3:
                // $vezes = (int) $plano;
                // $datetime    = new DateTime( $data_vencimento );

                // $forma_pagamento = array();

                // if($controle){
                //     $controle_sessoes = $this->controleSessoes($controle['dias'], $controle['data_inicio'], $controle['plano']);

                //     $key = 1;
                //     $forma_pagamento[0] = array(
                //         'forma' => $vezes,
                //         );

                //     // foreach($controle_sessoes['meses'] as $sessoes){
                //     for ($i=0; $i < $vezes; $i++) { 
                //         $forma_pagamento[0]['valor'][] = number_format(($valor), 2, ',','.');
                //         $forma_pagamento[0]['vencimento'][] = $datetime->format('d/m/Y');

                //         $datetime->add(new DateInterval('P1M'));

                //         $key++;
                //     }
                // }else{
                //     $forma_pagamento[0] = array(
                //         'forma' => $vezes
                //         );

                //     $datetime    = new DateTime( $data_vencimento );
                //     for($v=0;$v<$vezes;$v++){
                //         $forma_pagamento[0]['valor'][] = number_format($valor, 2, ',','.');
                //         $forma_pagamento[0]['vencimento'][] = $datetime->format('d/m/Y');

                //         $datetime->add(new DateInterval('P1M'));
                //     }
                // }

                $parcelas = 12;
                $vezes = (int) $plano;
                $valor = $valor * $vezes;

                $i=0;
                for($prazo=1; $prazo<=$parcelas; $prazo++){
                    $forma_pagamento[$i] = array(
                        'forma' => $prazo
                        );

                    $datetime    = new DateTime( $data_vencimento );
                    for($v=0;$v<$prazo;$v++){
                        $forma_pagamento[$i]['valor'][] = number_format($valor/$prazo, 2, ',','.');
                        $forma_pagamento[$i]['vencimento'][] = $datetime->format('d/m/Y');

                        $datetime->add(new DateInterval('P1M'));
                    }
                    $i++;
                }

                break;

            case 4:
                $parcelas = 12;
                $i=0;

                for($prazo = 1; $prazo <= $parcelas; $prazo++){
                    $forma_pagamento[$i] = array(
                        'forma' => $prazo
                    );

                    $datetime = new DateTime($data_vencimento);

                    for($v = 0; $v < $prazo; $v++){
                        $forma_pagamento[$i]['valor'][] = number_format($valor/$prazo, 2, ',','.');
                        $forma_pagamento[$i]['vencimento'][] = $datetime->format('d/m/Y');

                        $datetime->add(new DateInterval('P1M'));
                    }

                    $i++;
                }

                break;

            case 5:
                $parcelas = 12;

                $i=0;
                for($prazo=1; $prazo<=$parcelas; $prazo++){
                    $forma_pagamento[$i] = array(
                        'forma' => $prazo
                        );

                    $datetime    = new DateTime( $data_vencimento );
                    for($v=0;$v<$prazo;$v++){
                        $forma_pagamento[$i]['valor'][] = number_format($valor/$prazo, 2, ',','.');
                        $forma_pagamento[$i]['vencimento'][] = $datetime->format('d/m/Y');

                        $datetime->add(new DateInterval('P1M'));
                    }
                    $i++;
                }

                break;
            default:
                $parcelas = 0;
                break;
        }

        return $forma_pagamento;
    }

    public function salvar_procedimento(){
        $id_evolucao    = $this->input->post('id_evolucao');
        $procedimentos  = $this->input->post('procedimentos');

        if($this->Tratamentos_model->updateProcedimentos($procedimentos, $id_evolucao)){
            return true;
        }else{
            return false;
        }

    }

    /**
     * Impressão do Termo
     */
    public function imprimir_termo($especialidade_id){
        $this->_dados['termo'] = $this->Tratamentos_model->get_by_id_table('termo', array('especialidade_id' => $especialidade_id));

        if(!$this->_dados['termo']){
            $termo['id'] = 0;
            $termo['titulo'] = '';
            $termo['conteudo'] = 'Nenhum termo cadastrado para esta especialidade.';
            $termo['especialidade_id'] = $especialidade_id;

            $this->_dados['termo'] = (object) $termo;
        }

        ob_start();
        $this->load->view( 'tratamentos/imprimir_termo', $this->_dados );
        $this->load->library('Pdf');
        $this->pdf->gerar_pdf();
    }

    public function imprimir_contrato($tratamento_id){
        $tratamento = $this->Tratamentos_model->get_tratamento(array('t.id'=>$tratamento_id));

        $tratamento->procedimentos = $this->Tratamentos_model->get_procedimentos_combo(array('c.tratamento_id'=>$tratamento_id));

        $this->_dados['contrato'] = $this->Tratamentos_model->get_by_id_table('contrato', array('especialidade_id' => $tratamento->especialidade_id));

        if(!$this->_dados['contrato']){
            $contrato['id'] = 0;
            $contrato['titulo'] = '';
            $contrato['conteudo'] = 'Não possui contrato cadastrado para essa especialidade!';
            $contrato['especialidade_id'] = $especialidade_id;

            $this->_dados['contrato'] = (object) $contrato;
        }else{
            $this->_dados['contrato']->conteudo = $this->attrVariavel($this->_dados['contrato']->conteudo, $tratamento);
        }

        $this->_dados['tratamento'] = $tratamento;

        $this->_dados['_config'] = $this->Tratamentos_model->get_all_table('configuracao',array('modulo'=>'dados'));

        $this->_dados['cidade'] = $this->Tratamentos_model->get_by_id_table('cidade',array('id'=>$this->_dados['_config'][5]->valor));
        $this->_dados['estado'] = $this->Tratamentos_model->get_by_id_table('estado',array('id'=>$this->_dados['_config'][4]->valor));

        ob_start();
        $this->load->view( 'tratamentos/imprimir_contrato', $this->_dados );
        $this->load->library('Pdf');
        $this->pdf->gerar_pdf();
    }
    /**
     * Impressão da Guia SP/SADT
     */
    public function imprimir_guia($tratamento_id){
        $this->load->model('tiss/Lote_model');

        $tratamento = $this->Tratamentos_model->get_tratamento(array('t.id'=>$tratamento_id));
        $tratamento->conselho = (strlen($tratamento->conselho) == 1 ? '0'.$tratamento->conselho : $tratamento->conselho);
        $list_evolucoes = $this->Lote_model->get_evolucoes($tratamento_id);

        $list_atendimento1 = array();
        $list_atendimento2 = array();
        $list_atendimento3 = array();
        $list_atendimento4 = array();

        $valor_total_atendimento1 = 0;
        $valor_total_atendimento2 = 0;
        $quantidades = [];
        $quantidade = 1;

        //Varrendo para efetuar a contagem de atendimentos por procedimento
        foreach($list_evolucoes as $key => $evo){
            $list_procedimentos = $this->Lote_model->get_procedimentos($evo->id);

            foreach($list_procedimentos as $key => $procedimento_item){
                if(isset($quantidades[$procedimento_item->procedimento_id])){
                    $quantidades[$procedimento_item->procedimento_id] += 1;

                }else{
                    $quantidades[$procedimento_item->procedimento_id] = 1;
                }
            }
        }

        foreach($list_evolucoes as $key => $evolucao_item){
            $data_evolucao = ($evolucao_item->data);
            $list_procedimentos = $this->Lote_model->get_procedimentos($evolucao_item->id);

            foreach($list_procedimentos as $key => $procedimento_item){
                $codigo = $procedimento_item->codigo;
                $valor_unitario_str = 0;

                if((($procedimento_item->avaliacao == 1 && $evolucao_item->sessao == 1) OR ($evolucao_item->sessao == '')) || (($procedimento_item->avaliacao == 0 && $evolucao_item->sessao >= 1) OR $evolucao_item->sessao == '')){
                    $data_exp = explode('-', $data_evolucao);

                    $atendimento = new stdClass();
                    $atendimento->dia = $data_exp[2];
                    $atendimento->mes = $data_exp[1];
                    $atendimento->ano = $data_exp[0];
                    $atendimento->tabela = $procedimento_item->tiss_tabela;
                    $atendimento->codigo = $procedimento_item->codigo;
                    $atendimento->descricao = $procedimento_item->descricao_procedimento;
                    $atendimento->quantidade = 1;

                    if(isset($quantidades[$procedimento_item->procedimento_id])){
                        $atendimento->quantidade = $quantidades[$procedimento_item->procedimento_id];
                    }

                    $valor_unitario = $procedimento_item->valor;
                    $valor_unitario = number_format($valor_unitario, 2, '.', '.');
                    $valor_unitario = explode('.', $valor_unitario);
                    $atendimento->valor_unitario_real = $valor_unitario[0];
                    $atendimento->valor_unitario_centavos = $valor_unitario[1];

                    $total_branco = 6 - strlen($atendimento->valor_unitario_real);
                    $i_branco = 0;
                    $text_branco = '';

                    while($total_branco > $i_branco){
                        $text_branco .= '0';
                        $i_branco++;
                    }

                    $atendimento->text_branco_unitario = $text_branco;

                    $valor_exp = $procedimento_item->valor;
                    $valor_exp = $valor_exp * $quantidades[$procedimento_item->procedimento_id];
                    $valor_exp = number_format($valor_exp, 2, '.', '.');
                    $valor_exp = explode('.', $valor_exp);
                    $atendimento->valor_real = $valor_exp[0];
                    $atendimento->valor_centavos = $valor_exp[1];
                    $total_branco = 6 - strlen($atendimento->valor_real);
                    $i_branco = 0;
                    $text_branco = '';

                    while($total_branco > $i_branco){
                        $text_branco .= '0';
                        $i_branco++;
                    }

                    $atendimento->text_branco = $text_branco;

                    if(count($list_atendimento1) < 5){
                        $list_atendimento1[] = $atendimento;

                    }else{
                        $list_atendimento2[] = $atendimento;

                        if(count($list_atendimento2) <= 5){
                            $valor_total_atendimento2 += $procedimento_item->valor;
                        }
                    }

                    if(count($list_atendimento3) < 5){
                        $list_atendimento3[$procedimento_item->procedimento_id] = $atendimento;
                        if(!isset($list_atendimento3[$procedimento_item->procedimento_id])){
                            $list_atendimento3[] = $list_atendimento3[$procedimento_item->procedimento_id];
                        }
                    }

                    $valor_total_atendimento1 += $procedimento_item->valor;
                }
            }
        }  

        foreach($list_atendimento3 as $key => $value){
            $list_atendimento4[] = $list_atendimento3[$key]; 
        }

        //Atendimentos 1
        $this->_dados['list_atendimento1'] = $list_atendimento1;
        $this->_dados['list_atendimento4'] = $list_atendimento4;

        //Formatando o valor total 1
        $valor_total1_exp = explode('.', $valor_total_atendimento1);

        $total_branco = 8 - strlen($valor_total1_exp[0]);
        $i_branco = 0;
        $text_branco = '';

        while($total_branco > $i_branco){
            $text_branco .= '0';
            $i_branco++;
        }

        $this->_dados['valor_total1_real'] = $valor_total1_exp[0];
        $this->_dados['valor_total1_centavos'] = isset($valor_total1_exp[1]) ? $valor_total1_exp[1] : '00';
        $this->_dados['valor_total1_text_branco'] = $text_branco;

        //Atendimentos 2
        $this->_dados['list_atendimento2'] = $list_atendimento2;

        //Formatando o valor total 2
        $valor_total2_exp = explode('.', $valor_total_atendimento2);

        $total_branco = 8 - strlen($valor_total2_exp[0]);
        $i_branco = 0;
        $text_branco = '';

        while($total_branco > $i_branco){
            $text_branco .= '0';
            $i_branco++;
        }

        $this->_dados['valor_total2_real'] = $valor_total2_exp[0];
        $this->_dados['valor_total2_centavos'] = isset($valor_total2_exp[1]) ? $valor_total2_exp[1] : '00';
        $this->_dados['valor_total2_text_branco'] = $text_branco;

        //Verificação de CNES e executante
        $_config = $this->Tratamentos_model->get_all_table('configuracao', array('modulo' => 'dados'));
        $controle_clinicas = $this->auth_library->check_permission('clinica', 'clinica', 'index', 'Controle de Clínicas');
        $cnes = null;
        $executante = null;

        if(!$controle_clinicas){
            $cnes = $_config[16]->valor;
            $executante = $_config[1]->valor;

        }else{
            $clinica_dados = $this->Tratamentos_model->get_row(array('id' => $tratamento->clinica_id), 'clinica');

            $cnes = $clinica_dados->cnes;
            $executante = $clinica_dados->razao_social;
        }

        $this->_dados['cnes'] = $cnes;
        $this->_dados['executante'] = $executante;
        $this->_dados['tratamento'] = $tratamento;
        $this->_dados['cidade'] = $this->Tratamentos_model->get_by_id_table('cidade',array('id' => $_config[5]->valor));
        $this->_dados['estado'] = $this->Tratamentos_model->get_by_id_table('estado',array('id' => $_config[4]->valor));
        
        $this->_dados['data_autorizacao'] = data_guia($this->_dados['tratamento']->data_autorizacao);
        $this->_dados['vencimento_autorizacao'] = data_guia($this->_dados['tratamento']->vencimento_autorizacao);
        $this->_dados['validade'] = data_guia($this->_dados['tratamento']->validade);
        $this->_dados['recem_nascido'] = $this->verifica_recem_nato($tratamento->data_nascimento);
        ob_start();
        $this->load->view( 'tratamentos/guia/guia_sp_sadt', $this->_dados );

        $this->load->library('mpdf/mpdf');
        $html  = ob_get_clean();
        $mpdf = new mPDF('utf-8', 'A4-L');
        
        $mpdf->mPDF('utf-8','A4-L', 0, 0, 0, 0, 0, 0);

        $mpdf->useOddEven = 1;
        
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    /**
     * Verifica se o paciente é recem nascido
     */
    private function verifica_recem_nato($data_nascimento) {
        $recem_nato = 'S';
        $data_inicial = $this->gera_segundos($data_nascimento);
        $data_final = $this->gera_segundos(date('Y-m-d'));

        $dias_nascimento = (int) floor(($data_final - $data_inicial) / (60 * 60 * 24));

        if($dias_nascimento > 28){
            $recem_nato = 'N';
        }
        
        return $recem_nato;
    }

    /**
     * Retorna o Time em seconds de uma data no formato aaaa/mm/dd
     */
    private function gera_segundos($data) {
        $partes = explode('-', $data);

        return mktime(0, 0, 0, $partes[1], $partes[2], $partes[0]);
    }

    private function attrVariavel($conteudo, $tratamento){
        $this->load->helper('texto_helper');
        $valor_total = ($tratamento->valor + $tratamento->acrescimo) - $tratamento->desconto;
        if ($tratamento->plano_mensal) {
            $valor_total = $tratamento->plano_mensal * $valor_total;
        }
        $search = array(
            '{{NOME_PACIENTE}}',
            '{{CPF_PACIENTE}}',
            '{{RG_PACIENTE}}',
            '{{ENDERECO_PACIENTE}}',
            '{{DATA_ATUAL}}',
            '{{NOME_PROFISSIONAL}}',
            '{{DATA_NASCIMENTO}}',
            '{{VALOR_TOTAL}}',
            '{{DATA_INICIO}}',
            '{{DATA_LIMITE}}',
            '{{DATA_VENCIMENTO}}',
            '{{SESSOES}}',
            '{{VALOR_MENSALIDADE}}',
            );
        $replace = array(
            padroniza_nome($tratamento->paciente),
            padroniza_nome($tratamento->cpf),
            padroniza_nome($tratamento->identidade),
            padroniza_nome($tratamento->endereco),
            date('d/m/Y'),
            padroniza_nome($tratamento->profissional),
            bd2data($tratamento->data_nascimento),
            'R$ '.number_format($valor_total,2,',','.'),
            bd2data($tratamento->data_inicio),
            bd2data($tratamento->data_limite),
            bd2data($tratamento->data_vencimento),
            $tratamento->sessoes_totais,
            'R$ '.number_format($tratamento->valor,2,',','.'),
            );
        $conteudo = str_replace($search, $replace, $conteudo);

        return $conteudo;
    }

    /**
     * Controle de clinicas
     */
    private function controle_clinicas($set_clinica = null){
        //Gerenciamento de clinicas
        $controle_clinicas = $this->auth_library->check_permission('clinica', 'clinica', 'index', 'Controle de Clínicas');
        $id_clinica = 1; //1 = Clínica padrão

        //Se for diferente de administrador e possuir controle de clinicas
        if($this->session->userdata('group_id') != 1 && $controle_clinicas){
            $usuario = $this->ion_auth->user()->row();
            $clinicas = $this->Tratamentos_model->get_clinica_usuario($usuario->id, true);

            //Trazer selecionada a clinica em que o usuário está relacionado
            $clinicadb = $this->Tratamentos_model->get_clinica_usuario($usuario->id);
            $id_clinica = null;

            if($clinicadb){
                $id_clinica = $clinicadb->id;
            }
        
        }else{
            $clinicas = $this->Tratamentos_model->get_all_table('clinica', '');
        }

        $clinica = array('' => 'Selecione...');

        foreach ($clinicas as $dados_clinica) {
            $clinica[$dados_clinica->id] = $dados_clinica->nome; 
        }

        $this->_dados['controle_clinicas'] = $controle_clinicas;
        $this->_dados['clinica'] = $clinica;
        $this->_dados['set_clinica'] = ($set_clinica ? $set_clinica : $id_clinica);
    }

    /**
     * Marcacao das regioes do corpo
     */
    public function marcacao($id){
        $this->auth_library->check_logged($this->router->fetch_module(), $this->router->class, $this->router->method, 'Regiões do Corpo');

        $this->_dados['marcacao_list'] = $this->Tratamentos_model->get_all_table('tratamento_marcacao', array('tratamento_id' => $id), 0, 0, 'data', 'asc');
        $this->_dados['id_tratamento'] = $id;

        $this->load->view('tratamentos/marcacao/index', $this->_dados);
    }

    /**
     * Registro de marcações
     */
    public function marcacao_novo($id_tratamento){
        $this->auth_library->check_logged('tratamentos', 'tratamentos', 'marcacao', 'Regiões do Corpo');

        $this->_dados['id_tratamento'] = $id_tratamento;

        $this->load->view('tratamentos/marcacao/novo', $this->_dados);
    }

    /**
     * Salvar a imagem das marcações
     */
    public function marcacao_salvar($id_tratamento){
        $this->auth_library->check_logged('tratamentos', 'tratamentos', 'marcacao', 'Regiões do Corpo');

        $retorno_json = array();

        $dados = array(
            'tratamento_id' => $id_tratamento,
            'observacao' => $this->input->post('observacao'),
            'imagem' => $this->input->post('selecao_corpo'),
            'posicao' => serialize($this->input->post('selecao')), 
            'data' => date('Y-m-d'),
        );

        $insert_marcacao = $this->Tratamentos_model->insert_table('tratamento_marcacao', $dados);

        if($insert_marcacao){
            $retorno_json = array(
                'message' => 'Salvo com sucesso.',
                'id' => $insert_marcacao, 
                'data' => date('d/m/Y'),
                'type' => true,
            );

        }else{
            $retorno_json = array(
                'message' => 'Houve um erro ao salvar.',
                'type' => false
            );
        }

        echo json_encode($retorno_json);
    }

    /**
     * Visualizar das marcações de imagens
     */
    public function marcacao_visualizar($id){
        $this->auth_library->check_logged('tratamentos', 'tratamentos', 'marcacao', 'Regiões do Corpo');

        $this->_dados['marcacoes'] = $this->Tratamentos_model->get_row(array('id' => $id), 'tratamento_marcacao');

        $this->load->view('tratamentos/marcacao/visualizar', $this->_dados);
    }

    /**
     * Opção de remover as imagens das marcações das regiões do corpo
     */
    public function marcacao_remover(){
        $this->auth_library->check_logged('tratamentos', 'tratamentos', 'marcacao', 'Regiões do Corpo');
        
        $retorno_json = false;

        if($this->Tratamentos_model->delete('tratamento_marcacao', array('id' => $this->input->post('id')))){
            $retorno_json = true;
        }

        echo json_encode($retorno_json);
    }

    /**
     * Campos para cadastro rápido de médicos
     */
    private function form_novo_medico(){
        //Campos para registro rapido do médico
        //UF Conselho
        $uf_conselho = $this->db->order_by('nome', 'asc')->get('estado')->result();
        $campo_uf_conselho = array('' => 'Selecione...');

        foreach ($uf_conselho as $dados_uf){
            $campo_uf_conselho[$dados_uf->id] = $dados_uf->sigla;
        }

        $this->_dados['uf_conselho'] = $campo_uf_conselho;
        $this->_dados['set_uf_conselho'] = null;

        //Conselho
        $conselho = $this->db->order_by('id', 'asc')->get('tiss_medico_conselho')->result();
        $campo_conselho = array('' => 'Selecione...');

        foreach ($conselho as $dados_co){
            $campo_conselho[$dados_co->id] = $dados_co->nome;
        }

        $this->_dados['conselho'] = $campo_conselho;
        $this->_dados['set_conselho'] = null;

        //CBOS
        $cbos = $this->Tratamentos_model->get_all_table('cbos', array(), 0, 0, 'nome', 'asc');
        $campo_cbos = array('' => 'Selecione...');

        foreach ($cbos as $key => $c){
            $campo_cbos[$c->id] = $c->codigo . ' - ' . $c->nome;
        }

        $this->_dados['cbos'] = $campo_cbos;
        $this->_dados['set_cbos'] = null;
        $this->_dados['crm'] = array('name' => 'crm', 'class' => 'form-control', 'id' => 'crm-medico', 'value' => set_value('crm'));
    }

    /**
     * Verificar data de fechamento do caixa de uma conta especifica
     */
    private function verificar_data_fechamento_caixa($data_pagamento, $id_conta){
        $fechamento_caixa = $this->Caixa_model->get_data_fechamento_caixa($data_pagamento, $id_conta);

        if($fechamento_caixa){
            return false;
        
        }else{
            return true;
        }
    }
}
