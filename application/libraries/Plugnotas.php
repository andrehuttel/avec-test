<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * LIBRARY Plugnotas
 *
 * Biblioteca de gerenciamento das APIs da PlugNotas
 * @author DALLO, gabriel <gabriel_dalo@hotmail.com>
 * @copyright VEG Tecnologia
 * @version 1.0
 */
class Plugnotas{
    private $url = 'https://api.plugnotas.com.br';
    private $key = '5ef084ce-2123-4505-9cd3-af42316e2997';

    /**
     * API que retorna as cidades que a PlugNotas atende
     */
    public function get_cidades_atendidas(){
        //Consultar cidades que a plug notas atende
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/nfse/cidades');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-api-key: '.$this->key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * API que retorna a empresa registrada na PlugNotas, verificação pelo CNPJ da mesma
     */
    public function get_empresa($cnpj){
        //Consultar se a empresa está registrada
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/empresa/'.$cnpj);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-api-key: '.$this->key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * API para emitir a NFSE
     */
    public function emitir_nfse($dados){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/nfse');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-api-key: '.$this->key));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array($dados)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * API para consultar o status da NFSE
     */
    public function consultar_nfse($id_nota){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/nfse/consultar/'.$id_nota);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'x-api-key: '.$this->key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = json_decode(curl_exec($ch)); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * Download do PDF da NFSe
     */
    public function get_nfse_pdf($id_nota){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/nfse/pdf/'.$id_nota);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/pdf', 'Content-Disposition: attachment', 'x-api-key: '.$this->key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = curl_exec($ch); 

        curl_close($ch);

        return $ch_result;
    }

    /**
     * Download do XML da NFSe
     */
    public function get_nfse_xml($id_nota){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url.'/nfse/xml/'.$id_nota);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Content-Disposition: attachment', 'x-api-key: '.$this->key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(ENVIRONMENT !== 'production'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        $ch_result = curl_exec($ch); 

        curl_close($ch);

        return $ch_result;
    }
}

?>