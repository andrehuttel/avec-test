<?php

/**
 * Tratamentos_model responsavel pela interação entre o controller e o banco de dados
 * @author BERNS SIMÃO, Alexandre Augusto <alexandre.b.simao@gmail.com>
 * 
 */
class Tratamentos_model extends CI_Model {

    private $_table = "tratamento";
    private $_key = "id";

    // -----------------------------------------------------------------------

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // -----------------------------------------------------------------------

    function get_all( $where = array(), $like = array(), $offset = 0, $per_page = 0, $order_by1 = NULL, $order_by2 = NULL ){
        $permissao_tratamento = false;

        if($this->session->userdata('profissional_id') && $this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_tratamentos', 'O profissional poderá visualizar somente seus tratamentos relacionados')){
            $permissao_tratamento = true;
        }

        $this->db->select( 't.*, pc.nome, e.nome as especialidade, pf.nome as profissional, c.nome as convenio, (sessoes_totais - sessoes_restantes) as sessao_aberto, t.num_guia, n.nome as necessidade' )
                 ->from( $this->_table . ' t' );

        if( $where )
            $this->db->where( $where );

        if( $per_page != 0 )
            $this->db->limit( $per_page, $offset );

        if( $order_by1 != NULL && $order_by2 != NULL )
            $this->db->order_by( $order_by1, $order_by2 );

        if (isset($like['t.paciente_id']) && $like['t.paciente_id']) {
            unset($like['pc.nome']);

            $id_paciente = $like['t.paciente_id'];

            if($id_paciente){
                $this->db->where('t.paciente_id', $id_paciente);
                unset($like['t.paciente_id']);
            }
        } else {
            if(isset($like['pc.nome']) && $like['pc.nome']){
                $nomes = explode(' ', $like['pc.nome']);

                $busca = '';
                foreach ($nomes as $key => $nome) {
                    $nome = trim($nome);
                    $nome = str_replace('"', '', $nome);
                    
                    if($nome != '')
                        $busca .= 'pc.nome LIKE "%'.$this->db->escape_str($nome).'%" AND ';
                }

                if($busca){
                    $busca = '('.substr($busca, 0,-5).')';
                    $this->db->where($busca);
                    unset($like['pc.nome']);
                }
            }
        }

        if(isset($like['t.especialidade_id']) && $like['t.especialidade_id']){
            $this->db->where('t.especialidade_id', $like['t.especialidade_id']);
            unset($like['t.especialidade_id']);
        }

        if(isset($like['t.convenio_id'])){
            $convenio = $like['t.convenio_id'];
            
            if($convenio){
                $this->db->where('t.convenio_id', $convenio);
                unset($like['t.convenio_id']);
            }   
        }

        if( $like ){
            foreach ($like as $col => $val) {
                if( $val )
                    $this->db->like( $col, $val );
            }
        }

        $this->db->join( 'paciente pc', 'pc.id = t.paciente_id' );
        $this->db->join( 'especialidade e', 'e.id = t.especialidade_id' );
        $this->db->join( 'profissional pf', 'pf.id = t.profissional_id' );
        $this->db->join( 'plano pl', 'pl.id = t.plano_id', 'left' );
        $this->db->join( 'convenio c', 'c.id = t.convenio_id', 'left' );
        $this->db->join( 'necessidade n', 'n.id = t.necessidade_id', 'left' );

        if($permissao_tratamento){
            $this->db->where('t.profissional_id', $this->session->userdata('profissional_id'));
        }

        return $this->db->get()->result();
    } 

    // -----------------------------------------------------------------------

    function get_row($where = array(), $table = null, $order_by1 = null, $order_by2 = null){
        if(!$table){
            $table = $this->_table;
        }

        $this->db->select( '*' )
                 ->from($table);

        if($where){
            $this->db->where( $where );
        }

        if($order_by1 && $order_by2){
            $this->db->order_by($order_by1, $order_by2);
        }
        
        return $this->db->get()->row();
    }

    // -----------------------------------------------------------------------

    function insert($dados){
        $this->db->insert($this->_table, $dados);
        return $this->db->insert_id();
    }

    // -----------------------------------------------------------------------

    function insert_batch($dados){
        $this->db->insert_batch($this->_table, $dados);
        return $this->db->affected_rows();
    }

    // -----------------------------------------------------------------------
    
    function insert_table_batch($table, $dados){
        $this->db->insert_batch($table, $dados);
        return $this->db->insert_id();
    }

    // -----------------------------------------------------------------------
    
    function update($dados, $id){
        $this->db->where('id',$id);
        $this->db->update($this->_table, $dados);
        return $this->db->affected_rows();
    }

    // -----------------------------------------------------------------------

    function delete( $table, $condicao ){
        $this->db->where( $condicao );
        $this->db->delete( $table );
        return $this->db->affected_rows();
    }

    function get_all_table( $table, $where = array(), $offset = 0, $per_page = 0, $order_by1 = NULL, $order_by2 = NULL ){
        $this->db->select( '*' )
                 ->from( $table );

        if( $where )
            $this->db->where( $where );

        if( $per_page != 0 )
            $this->db->limit( $per_page, $offset );

        if ($order_by1 == 'procedimento') {
            $this->db->order_by( 'deleted', 'ASC' );
        }
        if( $order_by1 != NULL && $order_by2 != NULL )
            $this->db->order_by( $order_by1, $order_by2 );

        return $this->db->get()->result();
    }

    function get_by_id_table( $table, $where = array() ){
        $this->db->select( '*' )
                 ->from( $table );

        if( $where )
            $this->db->where( $where );
        
        return $this->db->get()->row();
    }

    function insert_table( $table, $dados ){
        $this->db->insert( $table, $dados );
        return $this->db->insert_id();
    }

    function update_table( $table, $dados, $where ){
        $this->db->where( $where );
        $this->db->update( $table, $dados );
        return $this->db->affected_rows();
    }

    function get_profissionais( $where = array(), $like = array() ){
        $this->db->select( 'p.*' );
        $this->db->where( $where );
        $this->db->where( 'p.status', 1 );
        $this->db->group_by( 'tp.profissional_id' );

        foreach( $like as $col => $val ){
            $this->db->like( $col, $val );
        }

        $this->db->join( 'especialidade_has_profissional tp', 'tp.profissional_id = p.id' );
        $this->db->join( 'especialidade e', 'e.id = tp.especialidade_id' );
        return $this->db->get( 'profissional p' )->result();
    }

    // -----------------------------------------------------------------------

    function get_historico_evolucao( $where = array() ){
        $this->db->select( 'e.*, pr.nome as profissional, pr.tipo_registro, pr.crefito, t.num_guia, eq.nome as equipamento' );
        $this->db->from( 'historico_evolucao e' );
        $this->db->join( 'tratamento t', 't.id = e.tratamento_id' );
        $this->db->join( 'equipamento eq', 'eq.id = e.equipamento_id', 'left' );
        $this->db->join( 'profissional pr', 'pr.id = e.profissional_id' );
        $this->db->where( $where );
        $this->db->order_by( 'IF(e.data,e.data,CURDATE()), e.sessao', 'asc', false );

        return $this->db->get()->result();
    }

    // -----------------------------------------------------------------------

    function get_evolucao( $where = array() ){
        $this->db->select( 'e.*, pr.nome as profissional, pr.tipo_registro, pr.crefito, t.num_guia, eq.nome as equipamento, GROUP_CONCAT(e.sessao) as sessao, ag.hora_inicio, ag.hora_fim, ag.clinica_id, ag.profissional_id as id_profissional_agenda, ag.data_inicio, ag.id as id_agenda, ag.observacao as observacao_agenda, t.categoria_tratamento_id, DATE_FORMAT(ev_r.data, "%d/%m/%Y") AS data_reposicao, ag.tipo_agendamento_id,
            IF(te.data, te.data, e.data) as data_tiss', false );
        $this->db->from( 'evolucao e' );
        $this->db->join( 'tratamento t', 't.id = e.tratamento_id' );
        $this->db->join( 'equipamento eq', 'eq.id = e.equipamento_id', 'left' );
        $this->db->join( 'profissional pr', 'pr.id = e.profissional_id' );
        $this->db->join( 'agenda ag', 'ag.id = e.agenda_id', 'left' );
        $this->db->join('evolucao ev_r', 'ag.evolucao_id_reposicao = ev_r.id', 'left');
        $this->db->join('tiss_evolucao te', 'te.evolucao_id = e.id', 'left');
        $this->db->where( $where );
        $this->db->order_by( 'IF(e.data,e.data,CURDATE()), e.sessao', 'asc', false );
        $this->db->group_by( 'IF(e.agenda_id,e.agenda_id,CONCAT(e.id,NOW()) )', false );

        return $this->db->get()->result();
    }

    /**
     * Retorna os procedimentos relacionados na evolução
     */
    function get_procedimento_evolucao($evolucao_id){
        $this->db->select('pr.codigo, pr.procedimento')
            ->from('procedimento_evolucao pe')
            ->join('procedimento pr', 'pr.id = pe.procedimento_id', 'left')
            ->where('pe.evolucao_id', $evolucao_id);

        return $this->db->get()->result();
    }

    // -----------------------------------------------------------------------

    function get_avaliacao( $where = array() ){
        $this->db->select( 'a.*, pc.*, e.nome as especialidade' )
                 ->from( 'tratamento t' );

        if( $where )
            $this->db->where( $where );

        $this->db->join( 'paciente pc', 'pc.id = p.paciente_id' );
        $this->db->join( 'especialidade e', 't.id = p.especialidades_id' );
        $this->db->join( 'avaliacao a', 'a.tratamento_id = p.id' );

        return $this->db->get()->row();
    }

    // -----------------------------------------------------------------------

    function get_tratamento( $where = array() ){
        $this->db->select( 't.*, pc.*, a.*, e.nome as especialidade, pl.descricao as plano, c.nome as convenio, m.nome as medico, pr.nome as profissional, 
                            pc.nome as paciente, pr.crefito, pr.tipo_registro, pr.cpf as cpf_profissional, pr.estado_id as profissional_estado_id, t.id as tratamento_id, a.id as id_avaliacao, (sessoes_totais - sessoes_restantes) as sessao_ativa, n.nome as necessidade, GROUP_CONCAT(DISTINCT CONCAT(pd.codigo," - ",pd.procedimento)) as procedimento, GROUP_CONCAT(DISTINCT(pd.codigo)) as codigo_procedimento, GROUP_CONCAT(DISTINCT(pd.procedimento)) as descricao_procedimento, GROUP_CONCAT((SELECT codigo from tiss_tabela tt where tt.id = pd.tiss_tabela_id )) as codigo_tiss_procedimento, ct.nome as tipo, t.observacao as observacao_tratamento, pc.id as paciente_id, (SELECT nome from tiss_acidente ta where ta.id = t.tiss_acidente_id ) as tiss_acidente,
							cd.nome as cidade, br.nome as bairro, t.data as data_cadastro, usu.username as usuario,
							pl.id as id_plano, t.convenio_id, t.plano_id, t.plano as plano_mensal, usut.username as usuario_tratamento, c.tiss_versao_id, tm.nome as tratamento_mensalidade, tm.recuperar_mes, tm.dias_recuperar, c.numero_ans, c.codigo_operadora, m.crm, es.sigla as estado_medico, m.cbo, cbo.codigo as codigo_cbo, 
                            tmc.codigo_operadora as codigo_prestador_operadora, tmc.nome_empresa as nome_empresa_tiss, m.tiss_medico_conselho_id as conselho, tat.codigo as tipo_atendimento, tac.codigo as acidente, c.imagem as convenio_logo, t.clinica_id, tmo.nome as conselho_str, pr_e.sigla as uf_conselho, t.cid', false)
                 ->from( 'tratamento t' );

        if( $where )
            $this->db->where( $where );

        $this->db->join( 'paciente pc', 'pc.id = t.paciente_id' );
        $this->db->join( 'especialidade e', 'e.id = t.especialidade_id' );
        $this->db->join( 'plano pl', 'pl.id = t.plano_id', 'left' );
        $this->db->join( 'convenio c', 'c.id = t.convenio_id', 'left' );
        $this->db->join( 'medico m', 'm.id = t.medico_id', 'left' );
        $this->db->join( 'profissional pr', 'pr.id = t.profissional_id' );
        $this->db->join( 'avaliacao a', 'a.tratamento_id = t.id', 'left' );
        $this->db->join( 'necessidade n', 'n.id = t.necessidade_id', 'left' );
        $this->db->join( 'tratamento_has_procedimento tp', 'tp.tratamento_id = t.id', 'left' );
        $this->db->join( 'procedimento pd', 'pd.id = tp.procedimento_id', 'left' );
        $this->db->join( 'categoria_tratamento ct', 'ct.id = t.categoria_tratamento_id', 'left' );
        $this->db->join( 'tratamento_mensalidade tm', 'tm.id = t.tratamento_mensalidade_id', 'left' );
        $this->db->join( 'cbos cbo', 'cbo.id = m.cbos_id', 'left' );
        $this->db->join( 'estado es', 'es.id = m.estado_id', 'left' );

        $this->db->join( 'bairro br', 'br.id = pc.bairro_id', 'left' );
        $this->db->join( 'cidade cd', 'cd.id = br.cidade_id', 'left' );
        $this->db->join( 'usuario usu', 'usu.id = pc.usuario_id', 'left' );
        $this->db->join( 'usuario usut', 'usut.id = t.usuario_id', 'left' );
        $this->db->join('tiss_medico_convenio tmc', 'tmc.medico_id = m.id and tmc.convenio_id = c.id', 'left');
        $this->db->join('tiss_tipo_atendimento tat', 'tat.id = t.tiss_tipo_atendimento_id', 'left');
        $this->db->join('tiss_acidente tac', 'tac.id = t.tiss_acidente_id', 'left');
        $this->db->join('tiss_medico_conselho tmo', 'tmo.id = m.tiss_medico_conselho_id', 'left');
        $this->db->join('estado pr_e', 'pr_e.id = pr.estado_id', 'left');
        $this->db->group_by('t.id');

        return $this->db->get()->row();
    }

    function get_num_rows( $table = 'tratamento', $where = array(), $like = array() ){
        $permissao_tratamento = false;

        if($this->session->userdata('profissional_id') && $this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_tratamentos', 'O profissional poderá visualizar somente seus tratamentos relacionados')){
            $permissao_tratamento = true;
        }

        if( $where )
            $this->db->where( $where );

        if(isset($like['pc.nome']) && $like['pc.nome']){
            $nomes = explode(' ', $like['pc.nome']);

            $busca = '';
            foreach ($nomes as $key => $nome) {
                $nome = trim($nome);
                $nome = str_replace('"', '', $nome);
                
                if($nome != '')
                    $busca .= 'pc.nome LIKE "%'.$this->db->escape_str($nome).'%" AND ';
            }

            if($busca){
                $busca = '('.substr($busca, 0,-5).')';
                $this->db->where($busca);
                unset($like['pc.nome']);
            }
        }

        if(isset($like['t.convenio_id'])){
            $convenio = $like['t.convenio_id'];
            
            if($convenio){
                $this->db->where('t.convenio_id', $convenio);
                unset($like['t.convenio_id']);
            }   
        }

        if( $like ){
            foreach ( $like as $col => $val ) {
                if( $val )
                    $this->db->like( $col, $val );
            }

            $this->db->join( 'paciente pc', 'pc.id = t.paciente_id' );
        }

        if($permissao_tratamento){
            $this->db->where('t.profissional_id', $this->session->userdata('profissional_id'));
        }

        return $this->db->get( $table . ' t' )->num_rows();
    }

    function get_max_sessao($id_tratamento){
        return $this->db->query('select max(sessao) as sessao from evolucao where tratamento_id = ?', array($id_tratamento))->row();
    }


    function check_row( $id = 0 ){
        $rows  = $this->db->where('tratamento_id', $id)->where('tipo_agendamento_id = 2 OR tipo_agendamento_id = 3')->get('agenda')->num_rows();
        $rows  = $this->db->where('tratamento_id', $id)->get('avaliacao')->num_rows();
        $rows += $this->db->where('tratamento_id', $id)->get('lancamento_caixa')->num_rows();
        $rows += $this->db->where('tratamento_id', $id)->where('agenda_id is not null')->get('evolucao')->num_rows();
        $rows += $this->db->where('tratamento_id', $id)->get('anexo')->num_rows();

        // $this->db->select( 'count(t.id) as nums' )
        //          ->from( 'tratamento t' )
        //          ->join( 'avaliacao a', 'a.tratamento_id = t.id', 'left' )
        //          ->join( 'evolucao e', 'e.tratamento_id = t.id', 'left' )
        //          ->where( 't.id', $id )
        //          ->where( '(a.id IS NOT NULL OR e.id IS NOT NULL)' )
        //          ->group_by( 't.id' );

        // return $this->db->get()->row();

        return $rows;
    }

    function get_agendamentos($where, $limit){
        $this->db->select('*')
                 ->from('agenda')
                 ->where('data_inicio > curdate()')
                 ->where($where)
                 ->order_by('data_inicio', 'asc')
                 ->limit($limit);

        return $this->db->get()
                        ->result();

    }

    function get_avaliacao_form($where = array()){
        $this->db->select('especialidade_id')
                 ->from('tratamento')
                 ->where('id', $where['tratamento_id']);
        $tratamento = $this->db->get()->row();

        $this->db->select('titulo_avaliacao_id, nome as titulo, a.registro_avaliacao as registro, a.data as data_avaliacao, t.especialidade_id')
                 ->from('avaliacao a')
                 ->join('formulario_avaliacao f', 'f.id = a.formulario_avaliacao_id')
                 ->join('titulo_avaliacao t', 't.id = f.titulo_avaliacao_id')
                 ->where($where)
                 ->group_by('registro_avaliacao')
                 ->order_by('registro_avaliacao', 'desc');
        $aval = $this->db->get()->result();

        $formulario = array();
        foreach ($aval as $key => $av) {
            if(isset($av->titulo_avaliacao_id)){
                $this->db->select('*')
                     ->from('formulario_avaliacao f')
                     // ->join('avaliacao a', 'a.formulario_avaliacao_id = f.id')
                     ->where('f.titulo_avaliacao_id', $av->titulo_avaliacao_id)
                     // ->where('a.tratamento_id',$where['tratamento_id']) 
                     ->where('f.parent is null')
                     ->order_by('f.ordem', 'asc');
                $formulario[$av->registro] = $this->db->get()->result();

                foreach ($formulario[$av->registro] as $key => $form) {
                    $resposta = $this->db->select('a.id as id_resposta, a.resposta, a.registro_avaliacao')
                                ->from('avaliacao a')
                                ->where('a.formulario_avaliacao_id',$form->id)
                                ->where('a.registro_avaliacao', $av->registro)
                                ->where('a.tratamento_id',$where['tratamento_id'])->get()->row();

                    $form->id_resposta = (isset($resposta->id_resposta)) ? $resposta->id_resposta : '';
                    $form->resposta = (isset($resposta->resposta)) ? $resposta->resposta : '';
                    $form->registro_avaliacao = (isset($resposta->registro_avaliacao)) ? $resposta->registro_avaliacao : '';

                    $this->db->select('*')
                         ->from('formulario_avaliacao f')
                         ->join('avaliacao a', 'a.formulario_avaliacao_id = f.id')
                         ->where(array('parent'=>$form->id, 'tratamento_id' => $where['tratamento_id']))
                         ->where('parent is not null')
                         ->order_by('f.ordem, f.id', 'asc');
                    $form->parent = $this->db->get()->result();
                }
            }
        }


        return array(
            'formulario' => $formulario,
            'titulo' => $aval,
            'titulo_avaliacao' => isset($aval) ? $aval : array(),
        );
    }

    /**
     * Retorna os detalhes de um lancamento especifico
     */
    function get_lancamento($tratamento_id){
        $this->db->select('*, lc.tipo_pagamento as id_tipo_pagamento, lc.id as id_lancamento, f.nome as fornecedor, pc.nome as plano_conta, tpc.id as id_tipo_plc, tpc.nome as tipo_plano_conta, lc.observacoes, con.nome as conta, pa.nome as paciente, fu.nome as funcionario, tp.nome as tipo_pagamento, lc.id as lancamento_id')
                 ->from('lancamento_caixa lc')
                 ->join('fornecedor f', 'f.id = lc.fornecedor_id', 'left')
                 ->join('plano_conta pc', 'pc.id = lc.plano_conta_id')
                 ->join('tipo_plano_conta tpc', 'tpc.id = pc.tipo_plano_conta_id')
                 ->join('conta con', 'lc.conta_id = con.id', 'left')
                 ->join('paciente pa', 'pa.id = lc.paciente_id', 'left')
                 ->join('funcionario fu', 'fu.id = lc.funcionario_id', 'left')
                 ->join('tipo_pagamento tp', 'tp.id = lc.tipo_pagamento', 'left')
                 ->where('lc.tratamento_id', $tratamento_id);

        return $this->db->get()->row();
    }

    /**
     * Retorna todo o historico de pagamento de um lancamento especifico
     */
    function get_historico_caixa($id_lancamento){
        $this->db->select('pc.num_parcela, hpc.data, hpc.hora, pc.valor_parcela, hpc.multa, hpc.juros, hpc.descontos, hpc.valor, hpc.status, pc.data_vencimento, con.nome as conta')
                 ->from('historico_pagamento_caixa hpc')
                 ->join('pagamento_caixa pc', 'pc.id = hpc.pagamento_caixa_id')
                 ->join('lancamento_caixa lc', 'lc.id = pc.lancamento_caixa_id')
                 ->join('conta con', 'lc.conta_id = con.id', 'left')
                 ->where('lc.id', $id_lancamento)
                 ->order_by('hpc.data', 'desc');

        return $this->db->get()->result();
    }

    /**
     * Retorna o total a pagar de um lancamento especifico
     */
    function get_total_pagar($id){
        $this->db->select('sum(valor_pagar) as pagar')
                 ->from('pagamento_caixa')
                 ->where('lancamento_caixa_id', $id);

        return $this->db->get()->row();
    }

    function delete_guias($where){
        $this->db->query("DELETE g 
                          FROM guia g 
                          JOIN evolucao e 
                            ON g.evolucao_id = e.id 
                          WHERE e.tratamento_id = ?", array($where['tratamento_id']));

        return $this->db->affected_rows();
    }

    function delete_lancamento($where){
      $this->db->query("DELETE h 
                          FROM pagamento_caixa p 
                          JOIN lancamento_caixa l 
                            ON p.lancamento_caixa_id = l.id 
                          JOIN historico_pagamento_caixa h 
                            ON h.pagamento_caixa_id = p.id 
                          WHERE l.tratamento_id = ?", array($where['tratamento_id']));

        $this->db->query("DELETE p 
                          FROM pagamento_caixa p 
                          JOIN lancamento_caixa l 
                            ON p.lancamento_caixa_id = l.id 
                          WHERE l.tratamento_id = ?", array($where['tratamento_id']));

        $this->db->where($where)->delete('lancamento_caixa');

        return $this->db->affected_rows();
    }

    function delete_evolucoes($where){
        //procedimento_evolucao
        $this->db->query("DELETE pe
                            FROM evolucao e
                            JOIN procedimento_evolucao pe ON pe.evolucao_id = e.id
                           WHERE e.tratamento_id = ?", array($where['tratamento_id']));

        //equipamento_evolucao_exercicio
        $this->db->query("DELETE eee
                            FROM evolucao e
                            JOIN equipamento_evolucao ee ON ee.evolucao_id = e.id
                            JOIN equipamento_evolucao_exercicio eee ON eee.equipamento_evolucao_id = ee.id
                           WHERE e.tratamento_id = ?", array($where['tratamento_id']));

        //equipamento_evolucao
        $this->db->query("DELETE ee
                            FROM evolucao e
                            JOIN equipamento_evolucao ee ON ee.evolucao_id = e.id
                           WHERE e.tratamento_id = ?", array($where['tratamento_id']));

        //tiss_evolucao
        $this->db->query("DELETE te
                            FROM evolucao e
                            JOIN tiss_evolucao te ON te.evolucao_id = e.id
                           WHERE e.tratamento_id = ?", array($where['tratamento_id']));

        $this->db->where($where)->delete('evolucao');

        return $this->db->affected_rows();
    }

    function delete_combo($where){
        $this->db->where($where)->delete('movimentacao_combo');
        $this->db->where($where)->delete('combo');
    }

    function get_info_evolucao($agenda_id){
        $this->db->select('ev.status, ev.sessao, ev.tratamento_id, ev.descricao, ev.observacao, ev.profissional_id, ag.data_inicio, ag.hora_inicio, ag.hora_fim, ag.finalizado_biometria')
                 ->from('evolucao ev')
                 ->join('agenda ag', 'ag.id = ev.agenda_id')
                 ->where('ev.agenda_id', $agenda_id);

        return $this->db->get()->result();
    }

    /**
     * Retorna os dados da agenda
     */
    function get_dados_agenda($id_agenda){
        $this->db->select('p.id as id_paciente, p.nome as paciente, c.id as id_convenio, c.nome as convenio, a.profissional_id, a.data_inicio, a.clinica_id')
                 ->from('agenda a')
                 ->join('paciente p', 'p.id = a.paciente_id')
                 ->join('convenio c', 'c.id = p.convenio_id', 'left')
                 ->where('a.id', $id_agenda);
        
        return $this->db->get()->row();
    }

    function get_procedimentos_combo($where){
        $this->db->select('c.*, p.procedimento, p.codigo, p.valor as valor_procedimento, c.valor, (c.sessoes_totais - c.sessoes_restantes) as sessoes_utilizadas, c.sessoes_totais, c.sessoes_restantes')
                 ->from('combo c')
                 ->join('procedimento p', 'p.id = c.procedimento_id')
                 ->where($where);

        return $this->db->get()->result();
    }

    /**
     * Retorna as comissões de mais de um profissional por tratamento :: Caso mensalidade
     */
    function get_tratamento_mensalidade_profissional($where){
        $this->db->select('tmp.*, p.nome as profissional')
                 ->from('tratamento_mensalidade_profissional tmp')
                 ->join('profissional p', 'p.id = tmp.profissional_id')
                 ->where($where);

        return $this->db->get()->result();
    }

    function getHistoricoAtendimentos($paciente_id, $especialidade_id){
        $this->db->select('e.data, e.status, e.descricao, e.observacao, p.nome as profissional, t.especialidade_id as id_especialidade')
               ->from('evolucao e')
               ->join('tratamento t','t.id = e.tratamento_id')
               ->join('profissional p','p.id = e.profissional_id')
               ->where('t.paciente_id',$paciente_id)
               ->where('t.especialidade_id',$especialidade_id)
               ->where('e.status != ""')
               ->order_by('e.data','asc');

        return $this->db->get()->result();
    }

    function getHistoricoAtendimentosPaciente($paciente_id){
        $this->db->select('e.data, e.status, e.descricao, e.observacao, p.nome as profissional, esp.id id_especialidade, esp.nome especialidade, n.id as id_necessidade, n.nome as necessidade, t.num_guia')
               ->from('evolucao e')
               ->join('tratamento t','t.id = e.tratamento_id')
               ->join('profissional p','p.id = e.profissional_id')
               ->join('especialidade esp', 'esp.id = t.especialidade_id', 'left')
               ->join('necessidade n', 'n.id = t.necessidade_id', 'left')
               ->where('t.paciente_id',$paciente_id)
               ->where('e.status != ""')
               ->order_by('e.data','asc')
               ->group_by('e.id');

        return $this->db->get()->result();
    }

    function get_movimentacao_procedimentos_combo($where){
        $this->db->select('c.*, p.procedimento, p.codigo, e.id as evolucao_id, mc.sessoes_utilizadas')
                 ->from('evolucao e')
                 ->join('combo c', 'c.tratamento_id = e.tratamento_id', 'left')
                 ->join('movimentacao_combo mc', 'mc.evolucao_id = e.id and mc.combo_id = c.id', 'left')
                 ->join('procedimento p', 'p.id = c.procedimento_id', 'left')
                 ->where($where)
                 ->group_by('mc.id, c.procedimento_id');

        $dados = $this->db->get()->result();

        $movimentacao = array();
        foreach ($dados as $key => $dado) {
            $movimentacao[$dado->evolucao_id][] = $dado;
        }

        return $movimentacao;
    }

    function getSessoes($tratamento_id){
        $this->db->select('count(*) as q_utilizadas')
                 ->from('evolucao')
                 ->where('tratamento_id',$tratamento_id)
                 ->where('sessao > 0')
                 ->where('status != ""');
        $eu = $this->db->get()->row();

        $this->db->select('count(*) as q_restantes')
                 ->from('evolucao')
                 ->where('tratamento_id',$tratamento_id)
                 ->where('sessao > 0')
                 ->where('status = ""');
        $er = $this->db->get()->row();

        return (object) array(
            'utilizadas' => $eu->q_utilizadas,
            'restantes'  => $er->q_restantes
            );
    }

    function updateProcedimentos($procedimentos, $id_evolucao){
        //Buscar os procedimentos que estão inseridos na evolucao até o momento para verificação do historico
        $list_procedimento_evolucao = $this->db->where('evolucao_id', $id_evolucao)->get('procedimento_evolucao')->result();
        $lista_procedimentos = array();
        $proc_hist = array();

        if($list_procedimento_evolucao){
            foreach ($list_procedimento_evolucao as $lista) {
                array_push($lista_procedimentos, $lista->procedimento_id);

                $exist = in_array($lista->procedimento_id, $procedimentos);

                if(!$exist){
                    $proc_hist[] = array(
                        'procedimento_id'   => $lista->procedimento_id,
                        'evolucao_id'       => $id_evolucao,
                        'situacao' => 1, //Removido
                        'data' => date('Y-m-d'),
                        'hora' => date('H:i:s'), 
                        'usuario_id' => $this->session->userdata('user_id'),
                    );
                }
            }

            if($proc_hist){
                $this->db->insert_batch('procedimento_evolucao_historico', $proc_hist);
            }
        }

        $this->db->where('evolucao_id', $id_evolucao)->delete('procedimento_evolucao');

        $proc = array();
        $proc_hist_add = array();

        foreach($procedimentos as $p){
            $proc[] = array(
                'evolucao_id'       => $id_evolucao,
                'procedimento_id'   => $p
                );

            $exist = in_array($p, $lista_procedimentos);

            if(!$exist){
                $proc_hist_add[] = array(
                    'procedimento_id'   => $p,
                    'evolucao_id'       => $id_evolucao,
                    'situacao' => 0, //Inserido
                    'data' => date('Y-m-d'),
                    'hora' => date('H:i:s'), 
                    'usuario_id' => $this->session->userdata('user_id'),
                );
            }
        }

        $this->db->insert_batch('procedimento_evolucao', $proc);

        if($proc_hist_add){
            $this->db->insert_batch('procedimento_evolucao_historico', $proc_hist_add);
        }

        return $this->db->affected_rows();
    }

    function insertEvolucoes($dados, $procedimentos){
        $id_tratamento = isset($dados[0]['tratamento_id']) ? $dados[0]['tratamento_id'] : null;

        if($id_tratamento){
            $this->db->insert_batch('evolucao', $dados);
            $evolucoes = $this->db->where('tratamento_id', $id_tratamento)->get('evolucao')->result();
            $proc_evo = array();

            if($procedimentos){
                foreach ($evolucoes as $evolucao) {
                    foreach($procedimentos as $procedimento){
                        if($procedimento){
                            $verifica_duplic = $this->db->where('evolucao_id', $evolucao->id)->where('procedimento_id', $procedimento)->get('procedimento_evolucao')->row();

                            if(!$verifica_duplic){
                                $proc_evo[] = array(
                                    'evolucao_id'       => $evolucao->id,
                                    'procedimento_id'   => $procedimento
                                );
                            }
                        }
                    }
                }

                if($proc_evo){
                    $this->db->insert_batch('procedimento_evolucao', $proc_evo);
                }
            }

            return $this->db->affected_rows();

        }else{
            return false;
        }
    }

    function verificaProcedimentos($procedimentos, $tratamento_id){
        $evolucoes = $this->db->where('tratamento_id', $tratamento_id)->get('evolucao')->result();

        foreach($evolucoes as $ev){
            $proc_evo = array();

            if($this->db->where('evolucao_id', $ev->id)->get('procedimento_evolucao')->num_rows() <= 0){
                foreach($procedimentos as $procedimento){
                    $proc_evo[] = array(
                        'evolucao_id'       => $ev->id,
                        'procedimento_id'   => $procedimento
                        );
                }
                
                $this->db->insert_batch('procedimento_evolucao', $proc_evo);
            }

        }

        return $this->db->affected_rows();
    }

    function getRelatorioProcedimentos($tratamento_id, $categoria_tratamento_id){

        if($categoria_tratamento_id == 5){
            $this->db->select( 'e.id, e.data, GROUP_CONCAT(e.sessao) as sessao, e.status' );
            $this->db->from( 'evolucao e' );
            $this->db->where('e.tratamento_id', $tratamento_id);
            $this->db->where('(e.status != "" and e.status != "FJ" and e.status != "DP" and e.status != "NC")');
            $this->db->order_by( 'IF(e.data,e.data,CURDATE()), e.sessao', 'asc', false );
            $this->db->group_by( 'IF(e.agenda_id,e.agenda_id,CONCAT(e.id,NOW()) )', false );

            $procedimentos_realizados = $this->db->get()->result();

        }else{
            $this->db->select('e.data, e.sessao, e.status, GROUP_CONCAT(p.procedimento) as procedimento, SUM(p.valor) as valor')
                     ->from('evolucao e')
                     ->join('procedimento_evolucao pe', 'pe.evolucao_id = e.id', 'left')
                     ->join('procedimento p', 'p.id = pe.procedimento_id', 'left')
                     ->where('e.tratamento_id', $tratamento_id)
                     ->where('(e.status != "" and e.status != "FJ" and e.status != "DP" and e.status != "NC")')
                     ->group_by('e.id')
                     ->order_by('e.sessao');
            $procedimentos_realizados = $this->db->get()->result();
        }
        $this->db->select('e.data, e.sessao, e.status, GROUP_CONCAT(p.procedimento) as procedimento, SUM(p.valor) as valor')
                 ->from('evolucao e')
                 ->join('tratamento_has_procedimento tp', 'tp.tratamento_id = e.tratamento_id', 'left')
                 ->join('procedimento p', 'p.id = tp.procedimento_id', 'left')
                 ->where('e.tratamento_id', $tratamento_id)
                 ->where('(e.status != "FJ" and e.status != "DP" and e.status != "NC")')
                 ->group_by('e.id')
                 ->order_by('e.sessao');
        $procedimentos_previsto = $this->db->get()->result();


        return (object) array(
            'realizados' => $procedimentos_realizados,
            'previsto'   => $procedimentos_previsto
            );

    }

    function syncCombo($procedimentos, $id_tratamento){

        $this->db->query('
            DELETE c FROM combo c
            LEFT JOIN movimentacao_combo mc ON mc.combo_id = c.id
            WHERE mc.id is null
            AND c.tratamento_id = ? 
            AND c.procedimento_id NOT IN ('.implode(',', $procedimentos).')', array($id_tratamento));

        $combo = $this->db->query('
            SELECT c.* FROM combo c
            JOIN movimentacao_combo mc ON mc.combo_id = c.id
            WHERE c.tratamento_id = ? 
            AND c.procedimento_id NOT IN ('.implode(',', $procedimentos).')', array($id_tratamento))->result();

        foreach ($combo as $key => $c) {
            $sessoes_totais = ($c->sessoes_totais - $c->sessoes_restantes);
            if($sessoes_totais <= 0){
                $sessoes_totais = 0;
            }
            $this->db->where('id',$c->id)->update('combo',array('sessoes_totais'=>$sessoes_totais, 'sessoes_restantes'=>0));
        }

        return $this->db->affected_rows();
    }

    function getSessoesUtilizadas($where){
        $this->db->select('sum(sessoes_utilizadas) as sessoes_utilizadas')
                 ->from('movimentacao_combo')
                 ->where($where);

        return $this->db->get()->row();
    }

    function get_paciente($where = array()){
        $this->db->select('p.*, c.nome as convenio')
                 ->from('paciente p')
                 ->join('convenio c', 'c.id = p.convenio_id')
                 ->where($where);

        return $this->db->get()->row();
    }

    /**
     * Retorna os historicos de edição do tratamento
     */
    function get_historico_edicao_tratamento($id_tratamento){
        $this->db->select('te.data, te.hora, usu.username as usuario')
                 ->from('tratamento_edicao te')
                 ->join('usuario usu', 'usu.id = te.usuario_id')
                 ->where('te.tratamento_id', $id_tratamento)
                 ->order_by('te.id', 'desc');

        return $this->db->get()->result();
    }

    /**
     * Retorna os pagamentos caixa
     */
    function get_list_pagamento_caixa($id_lancamento){
        $this->db->select('pc.*, 
                IF(tp.nome != "", tp.nome, tplc.nome) as nome_tipo_pagamento, 
                IF(car.id != "", car_pra.imagem, IF(tp.nome != "", "", car_pralc.imagem)) as imagem_cartao, 
                IF(car.id != "", car.nome, IF(tp.nome != "", "", carlc.nome)) as cartao', false)
                 ->from('pagamento_caixa pc')
                 ->join('tipo_pagamento tp', 'tp.id = pc.tipo_pagamento', 'left')
                 ->join('cartao car', 'car.id = pc.cartao_id', 'left')
                 ->join('cartao_padrao car_pra', 'car_pra.id = car.cartao_padrao_id', 'left')
                 ->join('lancamento_caixa lc', 'lc.id = pc.lancamento_caixa_id')
                 ->join('tipo_pagamento tplc', 'tplc.id = lc.tipo_pagamento', 'left')
                 ->join('cartao carlc', 'carlc.id = lc.cartao_id', 'left')
                 ->join('cartao_padrao car_pralc', 'car_pralc.id = carlc.cartao_padrao_id', 'left')
                 ->where('pc.lancamento_caixa_id', $id_lancamento);

        return $this->db->get()->result();
    }

    function get_avaliacao_row($tratamento_id){
        $this->db->where('tratamento_id', $tratamento_id);

        return $this->db->get( 'avaliacao'  )->num_rows();
    }

    function get_historico_unificar_paciente($id_tratamento){
        $this->db->select('hup.*, usu.username as usuario')
                 ->from('historico_unificar_paciente hup')
                 ->join('usuario usu', 'usu.id = hup.usuario_id')
                 ->where('hup.tratamento_id', $id_tratamento)
                 ->order_by('hup.id', 'desc');

        return $this->db->get()->result();
    }

    function updateEspecialidadeAgenda($tratamento_id, $especialidade){
        $this->db->where( "(ev.id IS NULL OR ev.`status` = '') AND a.tratamento_id = ".$tratamento_id );
        $this->db->set('a.especialidade_id', $especialidade);
        $this->db->update( 'agenda a LEFT JOIN evolucao ev ON ev.agenda_id = a.id' );
        return $this->db->affected_rows();
    }

    function get_historico_voltar_condicao($id_tratamento){
        $this->db->select('hvc.*, usu.username as usuario, pr.nome as profissional')
                 ->from('historico_voltar_condicao hvc')
                 ->join('usuario usu', 'usu.id = hvc.usuario_id')
                 ->join('profissional pr', 'pr.id = hvc.profissional_id')
                 ->where('hvc.tratamento_id', $id_tratamento)
                 ->order_by('hvc.id', 'desc');

        return $this->db->get()->result();
    }

    /**
     * Retorna a clinica relacionada ao usuario
     */
    function get_clinica_usuario($id_usuario, $list = false){
        $this->db->select('c.*')
                 ->from('clinica c')
                 ->join('clinica_has_usuario chu', 'chu.clinica_id = c.id', 'left')
                 ->group_by('c.id');


        if($id_usuario){
            $this->db->where('chu.usuario_id', $id_usuario);
        }

        if($list){
            return $this->db->get()->result();

        }else{
            return $this->db->get()->row();
        }
    }
    
    function get_agendamento_condicao($evolucao_id){
        $this->db->select('a.*')
                    ->from('evolucao ev')
                    ->join('agenda a', 'ev.agenda_id = a.id')
                    ->where('ev.id', $evolucao_id);

        return $this->db->get()->row();
    }

    /**
     * Verifica integridade referencial nos pagamentos de caixa
     */
    function verificar_relacao_caixa($id_tratamento){
        $this->db->select('pc.id, num_parcela')
                 ->from('pagamento_caixa pc')
                 ->join('lancamento_caixa lc', 'lc.id = pc.lancamento_caixa_id')
                 ->where('lc.tratamento_id', $id_tratamento)
                 ->group_by('pc.id');

        $pagamentos_caixa = $this->db->get()->result();
        $mensagem = null;

        if($pagamentos_caixa){
            foreach ($pagamentos_caixa as $pc) {
                $cobranca = $this->db->where('pagamento_caixa_id', $pc->id)->get('cobranca')->row();

                if($cobranca){
                    $mensagem = 'Não é possível refazer o lançamento de caixa, pois o mesmo possui cobranças registradas.';
                }
            }
        }

        return $mensagem;
    }

    function get_max_evolucao_tratamento($id_tratamento){
        $query = $this->db->query("SELECT max(sessao) as sessao FROM evolucao WHERE tratamento_id = ? AND status != ''", array($id_tratamento))->row();

        return $query;
    }

    function get_max_sessao_tratamento($id_tratamento){
        $query = $this->db->query("SELECT MAX(sessao) as max_sessao FROM evolucao WHERE tratamento_id = ? AND sessao != 0", array($id_tratamento))->row();

        return $query;
    }

    function get_max_evo_tratamento($id_tratamento){
        $query = $this->db->query("SELECT MAX(sessao) as max_evo FROM evolucao WHERE tratamento_id = ? AND sessao != 0 AND status != ''", array($id_tratamento))->row();

        return $query;
    }

    function delete_guia_evolucao($id_tratamento, $max_evo){
        $query = $this->db->query("DELETE g FROM guia g 
            JOIN evolucao e ON e.id = g.evolucao_id 
            WHERE tratamento_id = ? AND sessao > ? AND status = ''", array($id_tratamento, $max_evo));

        return $query;
    }

    function delete_procedimento_evolucao($id_tratamento, $max_evo = null){
        if($max_evo){
            $query = $this->db->query("DELETE pe FROM procedimento_evolucao pe
                JOIN evolucao e ON e.id = pe.evolucao_id
                WHERE e.sessao > ?
                AND e.tratamento_id = ?
                AND status = ''", array($max_evo, $id_tratamento));

        }else{
            $query = $this->db->query("DELETE pe FROM procedimento_evolucao pe
                JOIN evolucao e ON e.id = pe.evolucao_id
                WHERE e.tratamento_id = ?", array($id_tratamento));
        }

        return $query;
    }

    function delete_faturamento_evolucao($id_tratamento, $max_evo = null){
        if ($max_evo) {
            $query = $this->db->query("DELETE fa FROM faturamento fa
                JOIN evolucao e ON e.id = fa.evolucao_id
                WHERE e.sessao > ?
                AND e.tratamento_id = ?
                AND e.status = ''", array($max_evo, $id_tratamento));
        } else {
            $query = $this->db->query("DELETE fa FROM faturamento fa
                JOIN evolucao e ON e.id = fa.evolucao_id
                WHERE e.tratamento_id = ?", array($id_tratamento));
        }

        return $query;
    }

    /**
     * Removendo os equipamentos evolução
     */
    function delete_equipamento_evolucao($id_tratamento, $max_evo = null){
        if($max_evo && $id_tratamento){
            //equipamento_evolucao_exercicio
            $this->db->query("DELETE eee
                                FROM evolucao e
                                JOIN equipamento_evolucao ee ON ee.evolucao_id = e.id
                                JOIN equipamento_evolucao_exercicio eee ON eee.equipamento_evolucao_id = ee.id
                                WHERE e.sessao > ?
                                AND e.tratamento_id = ? 
                                AND e.status = ''", array($max_evo, $id_tratamento));

            //equipamento_evolucao
            $this->db->query("DELETE ee
                                FROM evolucao e
                                JOIN equipamento_evolucao ee ON ee.evolucao_id = e.id
                                WHERE e.sessao > ?
                                AND e.tratamento_id = ? 
                                AND e.status = ''", array($max_evo, $id_tratamento));
        }

        return $this->db->affected_rows();
    }

    function delete_hpc_tratamento($id_tratamento){
        $query = $this->db->query('
            DELETE hpc.* FROM lancamento_caixa lc
            JOIN pagamento_caixa pc ON pc.lancamento_caixa_id = lc.id
            JOIN historico_pagamento_caixa hpc ON hpc.pagamento_caixa_id = pc.id
            WHERE lc.tratamento_id = ?', array($id_tratamento));

        return $query;
    }

    function delete_pc_tratamento($id_tratamento){
        $query = $this->db->query('
            DELETE pc.* FROM lancamento_caixa lc
            JOIN pagamento_caixa pc ON pc.lancamento_caixa_id = lc.id
            WHERE lc.tratamento_id = ?', array($id_tratamento));

        return $query;
    }

    function delete_lc_tratamento($id_tratamento){
        $query = $this->db->query('
            DELETE lc.* FROM lancamento_caixa lc
            WHERE lc.tratamento_id = ?', array($id_tratamento));

        return $query;
    }

    function get_row_feriados( $start ){
        $data = '0000' . substr($start, 4, 6);

        return $this->db->query("SELECT * FROM feriado WHERE (data = ? OR data = ?) AND (status = 1)", array($start, $data))->row();
    }

    /**
     * Histórico de todas as avalições de um paciente em especifico
     */
    function get_historico_avaliacao($paciente_id){
        $this->db->select('t.data, e.nome as especialidade, t.num_guia, p.nome as profissional, a.tratamento_id, t.especialidade_id as id_especialidade')
               ->from('avaliacao a')
               ->join('tratamento t', 't.id = a.tratamento_id')
               ->join('especialidade e', 'e.id = t.especialidade_id')
               ->join('profissional p','p.id = t.profissional_id')
               ->where('t.paciente_id', $paciente_id)
               ->group_by('a.tratamento_id')
               ->order_by('a.data', 'asc');

        return $this->db->get()->result();
    }

    /**
     * Histórico de pausa no tratamento
     */
    function get_historico_tratamento_pausado( $where = array() ){
        $this->db->select( 'h.*, u.username as usuario_nome' );
        $this->db->from( 'historico_pausa_tratamento h' );
        $this->db->join( 'tratamento t', 't.id = h.tratamento_id' );
        $this->db->join( 'usuario u', 'u.id = h.usuario_id' );
        $this->db->where( $where );
        $this->db->order_by( 'h.modificacao', 'asc' );

        return $this->db->get()->result();
    }

    /**
     * Retorna os pagamentos caixa
     */
    function get_data_fechamento_caixa($data_pagamento, $id_conta){
        $this->db->select('hpc.data')
                 ->from('fechamento_caixa fc')
                 ->join('historico_pagamento_caixa hpc', 'hpc.id = fc.historico_pagamento_caixa_id')
                 ->where('fc.conta_id', $id_conta)
                 ->where('hpc.data >=', $data_pagamento)
                 ->order_by('fc.id', 'desc');

        return $this->db->get()->row();
    }

    /**
     * Retorna as transacoes efetuadas em um tratamento especifico
     */
    function get_transacoes_agilpay($id_tratamento){
        $this->db->select('lct.*, usu.username as usuario', false)
                 ->from('lancamento_caixa_transacao lct')
                 ->join('usuario usu', 'usu.id = lct.usuario_id', 'left')
                 ->where('lct.tratamento_id', $id_tratamento)
                 ->order_by('lct.id', 'desc');

        return $this->db->get()->result();
    }

    /**
     * Retorna os boletos gerados de um tratamento especifico
     */
    function get_boleto_gerado($tratamento_id){
        $this->db->select('bg.*')
                 ->from('boleto_gerado bg')
                 ->join('pagamento_caixa pc', 'bg.pagamento_caixa_id = pc.id')
                 ->join('lancamento_caixa lc', 'pc.lancamento_caixa_id = lc.id')
                 ->where('lc.tratamento_id', $tratamento_id);

        return $this->db->get()->result();
    }

    function __destruct(){
        $this->db->close();
    }
}
