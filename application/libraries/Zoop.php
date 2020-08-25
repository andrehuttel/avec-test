<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * LIBRARY Zoop
 *
 * Biblioteca de gerenciamento das APIs da fornecedora Zoop
 * @author DALLO, gabriel <gabriel_dalo@hotmail.com>
 * @copyright VEG Tecnologia
 * @version 1.0
 */
class Zoop{
    private $url = 'https://api.zoop.ws/v1';
    private $key = 'enBrX3Byb2RfQmM1S0JwWk5GRG1sMkk0bm5tTXQ1dzE0Og=='; //zpk_prod_Bc5KBpZNFDml2I4nnmMt5w14
    private $marketplace_id = '367ea05fd1044e4c97a652c04b62fb1e';

    /**
     * API paga emitir um pagamento de cliente
     */
    public function emitir_pagamento($dados){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/marketplaces/'.$this->marketplace_id.'/transactions');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-Type: application/json', 'accept: application/json', 'authorization: Basic '.$this->key));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * API que retorna o pagamento registrado
     */
    public function get_pagamento($id_transacao){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/marketplaces/'.$this->marketplace_id.'/transactions/'.$id_transacao);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json', 'authorization: Basic '.$this->key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * API que retorna os vendedores regitrados na Zoop
     */
    public function get_vendedores(){
        //Consultar vendedores registrados na plug
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/marketplaces/'.$this->marketplace_id.'/sellers');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json', 'authorization: Basic '.$this->key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * API criação de compradores
     */
    public function criar_comprador($dados){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/marketplaces/'.$this->marketplace_id.'/buyers');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-Type: application/json', 'accept: application/json', 'authorization: Basic '.$this->key));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * API alteração de comprador
     */
    public function alterar_comprador($dados, $id){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/marketplaces/'.$this->marketplace_id.'/buyers/'.$id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-Type: application/json', 'accept: application/json', 'authorization: Basic '.$this->key));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * API criação de token de cartão de crédito
     */
    public function criar_token($dados){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/marketplaces/'.$this->marketplace_id.'/transactions');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-Type: application/json', 'accept: application/json', 'authorization: Basic '.$this->key));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }
}

?>