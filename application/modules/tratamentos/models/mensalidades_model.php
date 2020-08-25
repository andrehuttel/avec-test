<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mensalidades_model responsavel pela interação entre o controller e o banco de dados
 * @author DALLO, Gabriel <gabriel_dalo@hotmail.com>
 * @copyright VEG Tecnologia
 * @package Tratamentos
 * @version 2.0
 */
class Mensalidades_model extends CI_Model{
    private $_table = "tratamento_mensalidade";
    private $_key = "id";

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Retorna listagem de mensalidades
     */
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
     * Inserção de registros
     */
    function incluir($dados = array(), $table = null){
        if(!$table){
            $table = $this->_table;
        }

        $this->db->insert($table, $dados);

        return $this->db->insert_id();
    }

    /**
     * Atualização de registros
     */
    function atualizar($id, $dados = array(), $table = null){
        if(!$table){
            $table = $this->_table;
        }

        $this->db->where('id', $id)
                 ->update($table, $dados);

        return $this->db->affected_rows();
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
    function remove($id, $table, $campo = 'id'){
        $this->db->where($campo, $id);
        $this->db->delete($table);  
        
        return $this->db->affected_rows(); 
    }

    /**
     * Retorno de numero de registros
     */
    function get_num_rows($table = 'tratamento_mensalidade', $where = array(), $like = array()){
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

    function check_row( $id = 0 ){
        $rows = $this->db->where('tratamento_mensalidade_id', $id)->get('tratamento')->num_rows();

        return $rows;
    }

    function __destruct(){
        $this->db->close();
    }
}