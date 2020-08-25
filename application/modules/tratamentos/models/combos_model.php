<?php

/**
 * Combos_model responsavel pela interação entre o controller e o banco de dados
 * @author BERNS SIMÃO, Alexandre Augusto <alexandre.b.simao@gmail.com>
 * @author DALLO, Gabriel <gabriel_dalo@hotmail.com>
 * 
 */
class Combos_model extends CI_Model {
    private $_table = "pacote_combo";
    private $_key = "id";

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_all($where = array(), $like = array(), $offset = 0, $per_page = 0, $order_by1 = 'nome', $order_by2 = 'asc'){
        $this->db->select('*')
                 ->from($this->_table);

        if($where){
            $this->db->where($where);
        }

        if($per_page != 0){
            $this->db->limit($per_page, $offset);
        }

        if($order_by1 != null && $order_by2 != null){
            $this->db->order_by($order_by1, $order_by2);
        }

        if($like){
            foreach ($like as $col => $val) {
                if($val)
                    $this->db->like($col, $val);
            }
        }

        return $this->db->get()->result();
    } 

    /**
     * Retorar registro especifico
     */
    function get_row($where = array(), $table = null){
        $this->db->select('*')
                 ->from($table);

        if($where){
            $this->db->where($where);
        }
        
        return $this->db->get()->row();
    }

    /**
     * Remoção de registros
     */
    function excluir($id){
        $this->db->where('pacote_combo_id',$id)->delete('pacote_combo_procedimento');
        $this->db->where('id',$id)->delete('pacote_combo');

        return $this->db->affected_rows();
    }

    /**
     * Retorno de vários registro com opção de ordenação, condições e paginação
     */
    function get_all_table($table, $where = array(), $offset = 0, $per_page = 0, $order_by1 = null, $order_by2 = null){
        $this->db->select('*')
                 ->from($table);

        if($where){
            $this->db->where($where);
        }

        if($per_page != 0){
            $this->db->limit($per_page, $offset);
        }

        if($order_by1 != null && $order_by2 != null){
            $this->db->order_by($order_by1, $order_by2);
        }

        return $this->db->get()->result();
    }

    /**
     * Retorno de numero de registros
     */
    function get_num_rows($table = 'pacote_combo', $where = array(), $like = array()){
        if($where){
            $this->db->where($where);
        }

        if($like){
            foreach ($like as $col => $val) {
                if($val)
                    $this->db->like($col, $val);
            }
        }

        return $this->db->get($table)->num_rows();
    }

    /**
     * Retorna um combo especifico com os procedimentos relacionados junto
     */
    function get_combo($id){
        $combo = $this->db->where('id',$id)->get('pacote_combo')->row();

        if($combo){
            $combo->procedimentos = $this->db->select('pcp.*, pro.valor as valor_procedimento')
                                             ->from('pacote_combo_procedimento pcp')
                                             ->join('procedimento pro', 'pcp.procedimento_id = pro.id')
                                             ->where('pacote_combo_id',$id)
                                             ->get()
                                             ->result();
        }

        return $combo;
    }

    /**
     * Inserção de combos 
     */
    function insert_pacote_combo($pacote, $procedimentos_combo){
        $this->db->insert('pacote_combo', $pacote);

        $pacote_combo_id = $this->db->insert_id();

        foreach ($procedimentos_combo as $k => $p) {
            $procedimentos_combo[$k]['pacote_combo_id'] = $pacote_combo_id;
        }

        $this->db->insert_batch('pacote_combo_procedimento', $procedimentos_combo);

        return $this->db->affected_rows();
    }

    /**
     * Edição de combos
     */
    function update_pacote_combo($pacote, $procedimentos_combo, $procedimentos, $id){
        $affected = 0;

        $this->db->where_not_in('procedimento_id',$procedimentos)->where('pacote_combo_id', $id)->delete('pacote_combo_procedimento');
        $affected += $this->db->affected_rows();

        $this->db->where('id',$id)->update('pacote_combo', $pacote);
        $affected += $this->db->affected_rows();

        foreach ($procedimentos_combo as $k => $p) {
            $dados_procedimento = array(
                'pacote_combo_id' => $id,
                'procedimento_id' => $p['procedimento_id'],
                'sessao'          => $p['sessao']
            );

            $pacote_combo_procedimento = $this->db->where(array('procedimento_id'=>$p['procedimento_id'],'pacote_combo_id'=>$id))->get('pacote_combo_procedimento')->row();

            if($pacote_combo_procedimento->id){
                $this->db->where('id',$pacote_combo_procedimento->id)->update('pacote_combo_procedimento', $dados_procedimento);
                $affected += $this->db->affected_rows();

            }else{
                $this->db->insert('pacote_combo_procedimento', $dados_procedimento);
                $affected += $this->db->affected_rows();
            }
        }

        return $affected;
    }

    /**
     * Retorna lista dos procedimentos
     */
    function get_list_procedimento(){
        $this->db->select("pro.*, co.nome as convenio")
                 ->from('procedimento pro')
                 ->join('convenio co','co.id = pro.convenio_id')
                 ->order_by('(-co.id)', 'desc')
                 ->order_by('pro.procedimento', 'asc')
                 ->where('pro.status', 1);

        return $this->db->get()->result();
    }

    function __destruct(){
        $this->db->close();
    }
}