<!DOCTYPE html>
<html>
<head>
	<title><?php echo $this->config->item('nome_empresa'); ?> :: Impressão</title>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/guia.css">
</head>
<body>
	<div class="sulamerica">
		<div class="sa_linha"></div>
		<div class="sa_registro_ans"><?= ($tratamento->numero_ans)? $tratamento->numero_ans : '&nbsp;' ?></div>
		<div class="sa_guia_principal"><?= ($tratamento->num_guia)? $tratamento->num_guia : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_data_autorizacao"><?= (isset($data_autorizacao[2]) && $data_autorizacao[2]>0)? $data_autorizacao[2] : '&nbsp;' ?></div>
		<div class="sa_mes_data_autorizacao"><?= (isset($data_autorizacao[1]) && $data_autorizacao[1]>0)? $data_autorizacao[1] : '&nbsp;'?></div>
		<div class="sa_ano_data_autorizacao"><?= (isset($data_autorizacao[0]) && $data_autorizacao[0]>0)? $data_autorizacao[0] : '&nbsp;'?></div>
		<div class="sa_senha"><?= ($tratamento->senha)? $tratamento->senha : '&nbsp;' ?></div>
		<div class="sa_dia_data_validade_senha"><?= (isset($vencimento_autorizacao[2]) && $vencimento_autorizacao[2]>0)? $vencimento_autorizacao[2] : '&nbsp;' ?></div>
		<div class="sa_mes_data_validade_senha"><?= (isset($vencimento_autorizacao[1]) && $vencimento_autorizacao[1]>0)? $vencimento_autorizacao[1] : '&nbsp;' ?></div>
		<div class="sa_ano_data_validade_senha"><?= (isset($vencimento_autorizacao[0]) && $vencimento_autorizacao[0]>0)? $vencimento_autorizacao[0] : '&nbsp;' ?></div>
		<div class="sa_numero_guia_operadora"></div>

		<div class="sa_linha"></div>
		<div class="sa_numero_carteira"><?= ($tratamento->matricula)? $tratamento->matricula : '&nbsp;' ?></div>
		<div class="sa_dia_validade_carteira"><?= (isset($validade[2]) && $validade[2]>0)? $validade[2] : '&nbsp;' ?></div>
		<div class="sa_mes_validade_carteira"><?= (isset($validade[1]) && $validade[1]>0)? $validade[1] : '&nbsp;' ?></div>
		<div class="sa_ano_validade_carteira"><?= (isset($validade[0]) && $validade[0]>0)? $validade[0] : '&nbsp;' ?></div>
		<div class="sa_nome"><?= ($tratamento->nome)? $tratamento->nome : '&nbsp;' ?></div>
		<div class="sa_cartao_nacional">&nbsp;</div>
		<div class="sa_atendimento_rn"><?= ($recem_nascido)? $recem_nascido : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_codigo_operadora"><?= ($tratamento->codigo_prestador_operadora)? $tratamento->codigo_prestador_operadora : '&nbsp;' ?></div>
		<?php if($tratamento->nome_empresa_tiss): ?>
			<div class="sa_nome_contratado"><?= ($tratamento->nome_empresa_tiss)? $tratamento->nome_empresa_tiss : '&nbsp;' ?></div>
		<?php else: ?>
			<div class="sa_nome_contratado"><?= ($tratamento->medico)? $tratamento->medico : '&nbsp;' ?></div>
		<?php endif; ?>
		

		<div class="sa_linha"></div>
		<div class="sa_profissional_solicitante"><?= ($tratamento->medico)? $tratamento->medico : '&nbsp;' ?></div>
		<div class="sa_conselho_profissional"><?= ($tratamento->conselho_str)? $tratamento->conselho_str : '&nbsp;' ?></div>
		<div class="sa_numero_conselho"><?= ($tratamento->crm)? $tratamento->crm : '&nbsp;' ?></div>
		<div class="sa_uf"><?= ($tratamento->estado_medico)? $tratamento->estado_medico : '&nbsp;' ?></div>
		<div class="sa_cbo"><?= ($tratamento->codigo_cbo)? $tratamento->codigo_cbo : '&nbsp;' ?></div>
		
		<div class="sa_linha"></div>
		<div class="sa_carater_atendimento"><?= ($tratamento->tiss_carater_id)? $tratamento->tiss_carater_id : '&nbsp;' ?></div>
		<div class="sa_dia_solicitacao">&nbsp;</div>
		<div class="sa_mes_solicitacao">&nbsp;</div>
		<div class="sa_ano_solicitacao">&nbsp;</div>
		<div class="sa_indicacao_clinica"><?= ($tratamento->tiss_indicacao_clinica)? $tratamento->tiss_indicacao_clinica : '&nbsp;' ?></div>

		<div class="procedimentos">
			<div class="sa_linha"></div>
			<div class="sa_tabela"><?= isset($list_atendimento4[0]->tabela) ? $list_atendimento4[0]->tabela : '&nbsp;' ?></div>
			<div class="sa_codigo_procedimento"><?= isset($list_atendimento4[0]->codigo) ? $list_atendimento4[0]->codigo : '&nbsp;' ?> &nbsp;</div>
			<div class="sa_descricao"><?= isset($list_atendimento4[0]->descricao) ? $list_atendimento4[0]->descricao : '&nbsp;' ?></div>
			<div class="sa_qtde_solicitado"><?= isset($list_atendimento4[0]->quantidade) ? $list_atendimento4[0]->quantidade : '&nbsp;' ?></div>
			<div class="sa_qtde_auto"><?= isset($list_atendimento4[0]->quantidade) ? $list_atendimento4[0]->quantidade : '&nbsp;' ?></div>
			<div class="sa_tabela"><?= isset($list_atendimento4[1]->tabela) ? $list_atendimento4[1]->tabela : '&nbsp;' ?></div>
			<div class="sa_codigo_procedimento"><?= isset($list_atendimento4[1]->codigo) ? $list_atendimento4[1]->codigo : '&nbsp;' ?> &nbsp;</div>
			<div class="sa_descricao"><?= isset($list_atendimento4[1]->descricao) ? $list_atendimento4[1]->descricao : '&nbsp;' ?></div>
			<div class="sa_qtde_solicitado"><?= isset($list_atendimento4[1]->quantidade) ? $list_atendimento4[1]->quantidade : '&nbsp;' ?></div>
			<div class="sa_qtde_auto"><?= isset($list_atendimento4[1]->quantidade) ? $list_atendimento4[1]->quantidade : '&nbsp;' ?></div>
			<div class="sa_tabela"><?= isset($list_atendimento4[2]->tabela) ? $list_atendimento4[2]->tabela : '&nbsp;' ?></div>
			<div class="sa_codigo_procedimento"><?= isset($list_atendimento4[2]->codigo) ? $list_atendimento4[2]->codigo : '&nbsp;' ?> &nbsp;</div>
			<div class="sa_descricao"><?= isset($list_atendimento4[2]->descricao) ? $list_atendimento4[2]->descricao : '&nbsp;' ?></div>
			<div class="sa_qtde_solicitado"><?= isset($list_atendimento4[2]->quantidade) ? $list_atendimento4[2]->quantidade : '&nbsp;' ?></div>
			<div class="sa_qtde_auto"><?= isset($list_atendimento4[2]->quantidade) ? $list_atendimento4[2]->quantidade : '&nbsp;' ?></div>
			<div class="sa_tabela"><?= isset($list_atendimento4[3]->tabela) ? $list_atendimento4[3]->tabela : '&nbsp;' ?></div>
			<div class="sa_codigo_procedimento"><?= isset($list_atendimento4[3]->codigo) ? $list_atendimento4[3]->codigo : '&nbsp;' ?> &nbsp;</div>
			<div class="sa_descricao"><?= isset($list_atendimento4[3]->descricao) ? $list_atendimento4[3]->descricao : '&nbsp;' ?></div>
			<div class="sa_qtde_solicitado"><?= isset($list_atendimento4[3]->quantidade) ? $list_atendimento4[3]->quantidade : '&nbsp;' ?></div>
			<div class="sa_qtde_auto"><?= isset($list_atendimento4[3]->quantidade) ? $list_atendimento4[3]->quantidade : '&nbsp;' ?></div>
			<div class="sa_tabela"><?= isset($list_atendimento4[4]->tabela) ? $list_atendimento4[4]->tabela : '&nbsp;' ?></div>
			<div class="sa_codigo_procedimento"><?= isset($list_atendimento4[4]->codigo) ? $list_atendimento4[4]->codigo : '&nbsp;' ?> &nbsp;</div>
			<div class="sa_descricao"><?= isset($list_atendimento4[4]->descricao) ? $list_atendimento4[4]->descricao : '&nbsp;' ?></div>
			<div class="sa_qtde_solicitado"><?= isset($list_atendimento4[4]->quantidade) ? $list_atendimento4[4]->quantidade : '&nbsp;' ?></div>
			<div class="sa_qtde_auto"><?= isset($list_atendimento4[4]->quantidade) ? $list_atendimento4[4]->quantidade : '&nbsp;' ?></div>

		</div>
		
		<div class="sa_linha"></div>
		<div class="sa_codigo_operadora_executante"><?= ($tratamento->codigo_operadora)? $tratamento->codigo_operadora : '&nbsp;' ?></div>
		<div class="sa_nome_contratado_executante"><?= ($executante) ? $executante : '&nbsp;' ?></div>
		<div class="sa_codigo_cnes"><?= ($cnes) ? $cnes : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_tipo_tendimento"><?= ($tratamento->tipo_atendimento)? $tratamento->tipo_atendimento : '&nbsp;' ?></div>
		<div class="sa_indicacao_acidente"><?= ($tratamento->acidente)? $tratamento->acidente : '&nbsp;' ?></div>
		<div class="sa_tipo_consulta">&nbsp;</div>
		<div class="sa_motivo_encerramento">&nbsp;</div>

		<!--Atendimento 1-->

		<!--36-->
		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento"><?= isset($list_atendimento4[0]->dia) ? $list_atendimento4[0]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento"><?= isset($list_atendimento4[0]->mes) ? $list_atendimento4[0]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento"><?= isset($list_atendimento4[0]->ano) ? $list_atendimento4[0]->ano : '&nbsp;' ?></div>

		<!--39-->
		<div class="sa_tabela_atendimento"><?= isset($list_atendimento4[0]->tabela) ? $list_atendimento4[0]->tabela : '&nbsp;' ?></div>

		<!--40-->
		<div class="sa_codigo_procedimento_atendimento"><?= isset($list_atendimento4[0]->codigo) ? $list_atendimento4[0]->codigo : '&nbsp;' ?> &nbsp;</div>

		<!--41-->
		<div class="sa_descricao_procedimento_atendimento"><?= isset($list_atendimento4[0]->descricao) ? mb_substr($list_atendimento4[0]->descricao, 0, 42) : '&nbsp;' ?></div>

		<!--42-->
		<div class="sa_quantidade_atendimento"><?= isset($list_atendimento4[0]->quantidade) ? $list_atendimento4[0]->quantidade : '&nbsp;' ?></div>

		<!--46-->
		<div class="sa_real_unitario_atendimento"><?= isset($list_atendimento4[0]->valor_unitario_real) ? '<span class="text-branco">'.$list_atendimento4[0]->text_branco_unitario.'</span>'.$list_atendimento4[0]->valor_unitario_real : '&nbsp;' ?>
		</div>
		<div class="sa_centavos_unitario_atendimento"><?= isset($list_atendimento4[0]->valor_unitario_centavos) ? $list_atendimento4[0]->valor_unitario_centavos : '&nbsp;' ?></div>

		<!--47-->
		<div class="sa_real_total_atendimento"><?= isset($list_atendimento4[0]->valor_real) ? '<span class="text-branco">'.$list_atendimento4[0]->text_branco.'</span>'.$list_atendimento4[0]->valor_real : '&nbsp;' ?></div>
		<div class="sa_centavos_total_atendimento"><?= isset($list_atendimento4[0]->valor_centavos) ? $list_atendimento4[0]->valor_centavos : '&nbsp;' ?></div>

		<!--Atendimento 2-->
		
		<!--36-->
		<div class="sa_dia_atendimento" style='margin-top: -2px;'><?= isset($list_atendimento4[1]->dia) ? $list_atendimento4[1]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento"><?= isset($list_atendimento4[1]->mes) ? $list_atendimento4[1]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento"><?= isset($list_atendimento4[1]->ano) ? $list_atendimento4[1]->ano : '&nbsp;' ?></div>

		<!--39-->
		<div class="sa_tabela_atendimento"><?= isset($list_atendimento4[1]->tabela) ? $list_atendimento4[1]->tabela : '&nbsp;' ?></div>

		<!--40-->
		<div class="sa_codigo_procedimento_atendimento"><?= isset($list_atendimento4[1]->codigo) ? $list_atendimento4[1]->codigo : '&nbsp;' ?> &nbsp;</div>

		<!--41-->
		<div class="sa_descricao_procedimento_atendimento"><?= isset($list_atendimento4[1]->descricao) ? mb_substr($list_atendimento4[1]->descricao, 0, 42) : '&nbsp;' ?></div>

		<!--42-->
		<div class="sa_quantidade_atendimento"><?= isset($list_atendimento4[1]->quantidade) ? $list_atendimento4[1]->quantidade : '&nbsp;' ?></div>

		<!--46-->
		<div class="sa_real_unitario_atendimento"><?= isset($list_atendimento4[1]->valor_unitario_real) ? '<span class="text-branco">'.$list_atendimento4[1]->text_branco_unitario.'</span>'.$list_atendimento4[1]->valor_unitario_real : '&nbsp;' ?></div>
		<div class="sa_centavos_unitario_atendimento"><?= isset($list_atendimento4[1]->valor_unitario_centavos) ? $list_atendimento4[1]->valor_unitario_centavos : '&nbsp;' ?></div>

		<!--47-->
		<div class="sa_real_total_atendimento"><?= isset($list_atendimento4[1]->valor_real) ? '<span class="text-branco">'.$list_atendimento4[1]->text_branco.'</span>'.$list_atendimento4[1]->valor_real : '&nbsp;' ?></div>
		<div class="sa_centavos_total_atendimento"><?= isset($list_atendimento4[1]->valor_centavos) ? $list_atendimento4[1]->valor_centavos : '&nbsp;' ?></div>

		<!--Atendimento 3-->
		
		<!--36-->
		<div class="sa_dia_atendimento" style='margin-top: -1px;'><?= isset($list_atendimento4[2]->dia) ? $list_atendimento4[2]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento"><?= isset($list_atendimento4[2]->mes) ? $list_atendimento4[2]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento"><?= isset($list_atendimento4[2]->ano) ? $list_atendimento4[2]->ano : '&nbsp;' ?></div>

		<!--39-->
		<div class="sa_tabela_atendimento"><?= isset($list_atendimento4[2]->tabela) ? $list_atendimento4[2]->tabela : '&nbsp;' ?></div>

		<!--40-->
		<div class="sa_codigo_procedimento_atendimento"><?= isset($list_atendimento4[2]->codigo) ? $list_atendimento4[2]->codigo : '&nbsp;' ?> &nbsp;</div>

		<!--41-->
		<div class="sa_descricao_procedimento_atendimento"><?= isset($list_atendimento4[2]->descricao) ? mb_substr($list_atendimento4[2]->descricao, 0, 42) : '&nbsp;' ?></div>

		<!--42-->
		<div class="sa_quantidade_atendimento"><?= isset($list_atendimento4[2]->quantidade) ? $list_atendimento4[2]->quantidade : '&nbsp;' ?></div>

		<!--46-->
		<div class="sa_real_unitario_atendimento"><?= isset($list_atendimento4[2]->valor_unitario_real) ? '<span class="text-branco">'.$list_atendimento4[2]->text_branco_unitario.'</span>'.$list_atendimento4[2]->valor_unitario_real : '&nbsp;' ?></div>
		<div class="sa_centavos_unitario_atendimento"><?= isset($list_atendimento4[2]->valor_unitario_centavos) ? $list_atendimento4[2]->valor_unitario_centavos : '&nbsp;' ?></div>

		<!--47-->
		<div class="sa_real_total_atendimento"><?= isset($list_atendimento4[2]->valor_real) ? '<span class="text-branco">'.$list_atendimento4[2]->text_branco.'</span>'.$list_atendimento4[2]->valor_real : '&nbsp;' ?></div>
		<div class="sa_centavos_total_atendimento"><?= isset($list_atendimento4[2]->valor_centavos) ? $list_atendimento4[2]->valor_centavos : '&nbsp;' ?></div>

		<!--Atendimento 4-->
		
		<!--36-->
		<div class="sa_dia_atendimento" style='margin-top: 0px;'><?= isset($list_atendimento4[3]->dia) ? $list_atendimento4[3]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento"><?= isset($list_atendimento4[3]->mes) ? $list_atendimento4[3]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento"><?= isset($list_atendimento4[3]->ano) ? $list_atendimento4[3]->ano : '&nbsp;' ?></div>

		<!--39-->
		<div class="sa_tabela_atendimento"><?= isset($list_atendimento4[3]->tabela) ? $list_atendimento4[3]->tabela : '&nbsp;' ?></div>

		<!--40-->
		<div class="sa_codigo_procedimento_atendimento"><?= isset($list_atendimento4[3]->codigo) ? $list_atendimento4[3]->codigo : '&nbsp;' ?> &nbsp;</div>

		<!--41-->
		<div class="sa_descricao_procedimento_atendimento"><?= isset($list_atendimento4[3]->descricao) ? mb_substr($list_atendimento4[3]->descricao, 0, 42) : '&nbsp;' ?></div>

		<!--42-->
		<div class="sa_quantidade_atendimento"><?= isset($list_atendimento4[3]->quantidade) ? $list_atendimento4[3]->quantidade : '&nbsp;' ?></div>

		<!--46-->
		<div class="sa_real_unitario_atendimento"><?= isset($list_atendimento4[3]->valor_unitario_real) ? '<span class="text-branco">'.$list_atendimento4[3]->text_branco_unitario.'</span>'.$list_atendimento4[3]->valor_unitario_real : '&nbsp;' ?></div>
		<div class="sa_centavos_unitario_atendimento"><?= isset($list_atendimento4[3]->valor_unitario_centavos) ? $list_atendimento4[3]->valor_unitario_centavos : '&nbsp;' ?></div>

		<!--47-->
		<div class="sa_real_total_atendimento"><?= isset($list_atendimento4[3]->valor_real) ? '<span class="text-branco">'.$list_atendimento4[3]->text_branco.'</span>'.$list_atendimento4[3]->valor_real : '&nbsp;' ?></div>
		<div class="sa_centavos_total_atendimento"><?= isset($list_atendimento4[3]->valor_centavos) ? $list_atendimento4[3]->valor_centavos : '&nbsp;' ?></div>

		<!--Atendimento 5-->
		
		<!--36-->
		<div class="sa_dia_atendimento" style='margin-top: 1px;'><?= isset($list_atendimento4[4]->dia) ? $list_atendimento4[4]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento"><?= isset($list_atendimento4[4]->mes) ? $list_atendimento4[4]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento"><?= isset($list_atendimento4[4]->ano) ? $list_atendimento4[4]->ano : '&nbsp;' ?></div>

		<!--39-->
		<div class="sa_tabela_atendimento"><?= isset($list_atendimento4[4]->tabela) ? $list_atendimento4[4]->tabela : '&nbsp;' ?></div>

		<!--40-->
		<div class="sa_codigo_procedimento_atendimento"><?= isset($list_atendimento4[4]->codigo) ? $list_atendimento4[4]->codigo : '&nbsp;' ?> &nbsp;</div>

		<!--41-->
		<div class="sa_descricao_procedimento_atendimento"><?= isset($list_atendimento4[4]->descricao) ? mb_substr($list_atendimento4[4]->descricao, 0, 42) : '&nbsp;' ?></div>

		<!--42-->
		<div class="sa_quantidade_atendimento"><?= isset($list_atendimento4[4]->quantidade) ? $list_atendimento4[4]->quantidade : '&nbsp;' ?></div>

		<!--46-->
		<div class="sa_real_unitario_atendimento"><?= isset($list_atendimento4[4]->valor_unitario_real) ? '<span class="text-branco">'.$list_atendimento4[4]->text_branco_unitario.'</span>'.$list_atendimento4[4]->valor_unitario_real : '&nbsp;' ?></div>
		<div class="sa_centavos_unitario_atendimento"><?= isset($list_atendimento4[4]->valor_unitario_centavos) ? $list_atendimento4[4]->valor_unitario_centavos : '&nbsp;' ?></div>

		<!--47-->
		<div class="sa_real_total_atendimento"><?= isset($list_atendimento4[4]->valor_real) ? '<span class="text-branco">'.$list_atendimento4[4]->text_branco.'</span>'.$list_atendimento4[4]->valor_real : '&nbsp;' ?></div>
		<div class="sa_centavos_total_atendimento"><?= isset($list_atendimento4[4]->valor_centavos) ? $list_atendimento4[4]->valor_centavos : '&nbsp;' ?></div>

		<!--65-->
		<div class="sa_linha"></div>
		<div class="sa_real_total_geral"><?= $valor_total1_real ? '<span class="text-branco">'.$valor_total1_text_branco.'</span>'.$valor_total1_real : '&nbsp;' ?></div>
		<div class="sa_centavos_total_geral"><?= $valor_total1_centavos ? $valor_total1_centavos : '&nbsp;' ?></div>

		<div class="sa_convenio_nome">&nbsp;</div>
		

		<!--56-->
		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56"><?= isset($list_atendimento1[0]->dia) ? $list_atendimento1[0]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56"><?= isset($list_atendimento1[0]->mes) ? $list_atendimento1[0]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56"><?= isset($list_atendimento1[0]->ano) ? $list_atendimento1[0]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_2"><?= isset($list_atendimento1[1]->dia) ? $list_atendimento1[1]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_2"><?= isset($list_atendimento1[1]->mes) ? $list_atendimento1[1]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_2"><?= isset($list_atendimento1[1]->ano) ? $list_atendimento1[1]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_3"><?= isset($list_atendimento1[2]->dia) ? $list_atendimento1[2]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_3"><?= isset($list_atendimento1[2]->mes) ? $list_atendimento1[2]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_3"><?= isset($list_atendimento1[2]->ano) ? $list_atendimento1[2]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_4"><?= isset($list_atendimento1[3]->dia) ? $list_atendimento1[3]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_4"><?= isset($list_atendimento1[3]->mes) ? $list_atendimento1[3]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_4"><?= isset($list_atendimento1[3]->ano) ? $list_atendimento1[3]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_5"><?= isset($list_atendimento1[4]->dia) ? $list_atendimento1[4]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_5"><?= isset($list_atendimento1[4]->mes) ? $list_atendimento1[4]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_5"><?= isset($list_atendimento1[4]->ano) ? $list_atendimento1[4]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_6"><?= isset($list_atendimento2[0]->dia) ? $list_atendimento2[0]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_6"><?= isset($list_atendimento2[0]->mes) ? $list_atendimento2[0]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_6"><?= isset($list_atendimento2[0]->ano) ? $list_atendimento2[0]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_7"><?= isset($list_atendimento2[1]->dia) ? $list_atendimento2[1]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_7"><?= isset($list_atendimento2[1]->mes) ? $list_atendimento2[1]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_7"><?= isset($list_atendimento2[1]->ano) ? $list_atendimento2[1]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_8"><?= isset($list_atendimento2[2]->dia) ? $list_atendimento2[2]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_8"><?= isset($list_atendimento2[2]->mes) ? $list_atendimento2[2]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_8"><?= isset($list_atendimento2[2]->ano) ? $list_atendimento2[2]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_9"><?= isset($list_atendimento2[3]->dia) ? $list_atendimento2[3]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_9"><?= isset($list_atendimento2[3]->mes) ? $list_atendimento2[3]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_9"><?= isset($list_atendimento2[3]->ano) ? $list_atendimento2[3]->ano : '&nbsp;' ?></div>

		<div class="sa_linha"></div>
		<div class="sa_dia_atendimento_56_10"><?= isset($list_atendimento2[4]->dia) ? $list_atendimento2[4]->dia : '&nbsp;' ?></div>
		<div class="sa_mes_atendimento_56_10"><?= isset($list_atendimento2[4]->mes) ? $list_atendimento2[4]->mes : '&nbsp;' ?></div>
		<div class="sa_ano_atendimento_56_10"><?= isset($list_atendimento2[4]->ano) ? $list_atendimento2[4]->ano : '&nbsp;' ?></div>

		<div class="sa_convenio">
			<?php if($tratamento->convenio_logo): ?>
				<img src='<?= base_url(); ?>uploads/convenios/<?= $tratamento->convenio_logo ?>' style='margin-top:-14px' height='45px'>

			<?php else: ?>
				<?= $tratamento->convenio ?>
			<?php endif; ?>
		</div>
		<div class="sa_linha"></div>
		<div class="cpf_profissional"><div <?php if(!$tratamento->convenio_logo): ?>style='margin-top: 6px'<?php endif; ?>><?= $tratamento->cpf_profissional ? preg_replace("/[^0-9]/", "", $tratamento->cpf_profissional) : '&nbsp;' ?></div></div>
		<div class="nome_profissional"><?= $tratamento->profissional ? $tratamento->profissional : '&nbsp;' ?></div>
		<div class="tipo_registro"><?= $tratamento->tipo_registro ? $tratamento->tipo_registro : '&nbsp;' ?></div>
		<div class="crefito"><?= $tratamento->crefito ? $tratamento->crefito : '&nbsp;' ?></div>
		<div class="uf_conselho"><?= $tratamento->uf_conselho ? $tratamento->uf_conselho : '&nbsp;' ?></div>
		<div class="codigo_cbo">&nbsp;</div>

		<div class="sa_assinatura_beneficiario"><?= isset($list_atendimento1[0]->dia) ? $list_atendimento1[0]->dia : '&nbsp;' ?>/<?= isset($list_atendimento1[0]->mes) ? $list_atendimento1[0]->mes : '&nbsp;' ?>/<?= isset($list_atendimento1[0]->ano) ? $list_atendimento1[0]->ano : '&nbsp;' ?></div>

	</div>
</body>
</html>
