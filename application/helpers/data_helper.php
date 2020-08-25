<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Helper data_helper
 *
 * Helper para tratamento de datas
 * @copyright VEG Tecnologia
 */

/**
 * Exibir data e hora de um DATETIME obtido de um banco de dados
 */
if(!function_exists('bd2datahora')){
    function bd2datahora($datahora){
        if($datahora == "0000-00-00 00:00:00"){
            return "";

        }else{
            $datahora = explode(" ", $datahora);
            $data = $datahora[0];
            $hora00 = $datahora[1];
            $dataformat = explode("-", $data);
            $data = $dataformat[2]."/".$dataformat[1]."/".$dataformat[0];

            return $data." ".$hora00;
        }
    }
}

/**
 * Exibir apenas a data de um DATETIME obtido de um banco de dados
 */
if(!function_exists('bd2data')){
    function bd2data($datahora){
        if($datahora == "0000-00-00" || $datahora == NULL){
            return "";

        }else{
            $datahora = explode(" ", $datahora);
            $data = $datahora[0];
            $dataformat = explode("-", $data);
            $data = $dataformat[2]."/".$dataformat[1]."/".$dataformat[0];

            return $data;
        }
    }
}

/**
 * Exibir apenas a hora de um DATETIME obtido de um banco de dados
 */
if(!function_exists('bd2hora')){
    function bd2hora($datahora){
        if($datahora == "0000-00-00 00:00:00"){
            return "";

        }else{
            $datahora = explode(" ", $datahora);
            $hora00 = $datahora[1];
            $hora = explode(":", $hora00);

            return $hora[0].":".$hora[1];
        }
    }
}

/**
 * Converter uma data em formato de data americano
 * Se não estiver completo a expressão DD/MM/AAAA, ela é completada
 * com os valores atuais
 */
if(!function_exists('data2famericano')){
    function data2famericano($data){
        $dataformat = explode("/", $data);
        $dia = isset($dataformat[0])? $dataformat[0] : date('d');
        $mes = isset($dataformat[1])? $dataformat[1] : date('m');
        $ano = isset($dataformat[2])? $dataformat[2] : date('Y');
        $data = $ano."-".$mes."-".$dia;

        return $data;
    }
}

?>