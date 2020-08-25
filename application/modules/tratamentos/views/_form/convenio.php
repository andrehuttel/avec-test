<div class="row-fluid form-tratamento" id="guia-convenio">
	
	<div class="col-md-4">
		<?php if (!isset($tratamento->tiss_versao_id)): ?>
			<?php echo form_label( 'Médico', 'medicos' ); ?>
		<?php else: ?>
			<?php echo form_label( 'Médico <span class="required">*</span>', 'medicos' ); ?>
		<?php endif ?>
		<div class="input-group">
			<?php echo form_dropdown( 'medico', $medicos, set_value('medico', isset($tratamento) ? $tratamento->medico_id : ''), 'class="form-control" id="medicos" required' ); ?>
			<span class="input-group-btn">
		        <button class="btn btn-default" style="top:-1px;" data-toggle="modal" data-target="#addMedico" type="button" title="Adicionar Médico"><i class="glyphicon glyphicon-plus"></i></button>
		      </span>
		</div>
	</div>
	<div class="col-md-2">
		<?php echo form_label( 'CID', 'cid' ); ?>
		<?php echo form_input( $cid ); ?>
	</div>
	<div class="col-md-2">
		<?php if (!isset($tratamento->tiss_versao_id)): ?>
			<?php echo form_label( 'No Guia', 'num_guia' ); ?>
		<?php else: ?>
			<?php echo form_label( 'No Guia <span class="required">*</span>', 'num_guia' ); ?>
		<?php endif ?>
		<?php echo form_input( $num_guia ); ?>
	</div>
	<div class="col-md-2">
		<?php if (!isset($tratamento->tiss_versao_id)): ?>
			<?php echo form_label( 'Sessões', 'sessoes' ); ?>
		<?php else: ?>
			<?php echo form_label( 'Sessões  <span class="required">*</span>', 'sessoes' ); ?>
		<?php endif ?>
		<?php echo form_input( $sessoes_fisioterapia ); ?>
	</div>
	<div class="col-md-2">
		<?php echo form_label( 'Autorização <span class="required">*</span>', 'autorizacao' ); ?> <br>
		
		<label>
		<?php echo form_radio(array('name'=>'autorizacao','value'=>'sim', 'id'=>'sim', 'required'=>'required', 'checked'=>(isset($tratamento)&&$tratamento->autorizacao=='sim'?TRUE:FALSE))); ?>
			Sim
		</label>

		<label>
		<?php echo form_radio(array('name'=>'autorizacao','value'=>'nao', 'id'=>'nao', 'required'=>'required', 'checked'=>(isset($tratamento)&&$tratamento->autorizacao=='nao'?TRUE:FALSE))); ?>
			Não
		</label>

	</div>
	
	<br>

	<?php if(isset($tratamento)&&$tratamento->autorizacao=='sim'):?>
	<div id="autorizacao">
	<?php else: ?>
	<div id="autorizacao" style="display:none;">
	<?php endif; ?>
		
		<div class="col-md-4">
			<?php echo form_label('Data da Autorização', 'data_autorizacao'); ?>
			<?php isset($tratamento)&&$tratamento->autorizacao=='sim' ? $data_autorizacao['value'] = bd2data($tratamento->data_autorizacao) : $data_autorizacao['value'] = null;?>
			<?php echo form_input($data_autorizacao); ?>
		</div>

		<div class="col-md-4">
			<?php echo form_label('Senha','senha'); ?>
			<?php isset($tratamento)&&$tratamento->senha ? $senha['value'] = $tratamento->senha : $senha['value'] = '';?>
			<?php echo form_input($senha); ?>
		</div>

		<div class="col-md-4">
			<?php echo form_label('Vencimento da Autorização', 'vencimento_autorizacao'); ?>
			<?php isset($tratamento)&&$tratamento->autorizacao=='sim' ? $vencimento_autorizacao['value'] = bd2data($tratamento->vencimento_autorizacao) : $vencimento_autorizacao['value'] = null;?>
			<?php echo form_input($vencimento_autorizacao); ?>
		</div>
	</div>

	<br>

	<div id="dados-tiss" <?php if(!isset($tratamento->tiss_versao_id)): ?>style="display:none;"<?php endif; ?>>
		<div class="col-md-3">
			<?php echo form_label( 'Caráter Atendimento <span class="required">*</span>', 'carater' ); ?>
			<?php echo form_dropdown( 'carater', $carater, set_value('carater', isset($tratamento) ? $tratamento->tiss_carater_id : ''), 'class="form-control" id="tiss-campo-carater"' ); ?>
		</div>

		<div class="col-md-3">
			<?php echo form_label( 'Tipo Atendimento <span class="required">*</span>', 'tipo_atendimento' ); ?>
			<?php echo form_dropdown( 'tipo_atendimento', $tipo_atendimento, set_value('tipo_atendimento', isset($tratamento) ? $tratamento->tiss_tipo_atendimento_id : ''), 'class="form-control" id="tiss-campo-tipo-atendimento"' ); ?>
		</div>

		<div class="col-md-3">
			<?php echo form_label( 'Indicação Acidente <span class="required">*</span>', 'acidente' ); ?>
			<?php echo form_dropdown( 'acidente', $acidente, set_value('acidente', isset($tratamento) ? $tratamento->tiss_acidente_id : ''), 'class="form-control" id="tiss-campo-acidente"' ); ?>
		</div>

		<div class="col-md-3">
			<?php echo form_label('Indicação Clínica <i class="glyphicon glyphicon-info-sign spanpop-info" data-toggle="popover" data-placement="top" data-content="A informação deste campo será apresentada na impressão da guia SP/SADT."></i>', 'indicacao_clinica'); ?>
			<?php echo form_input($indicacao_clinica); ?>
		</div>

		<br style='clear:both'>
	</div>

	<?php if($convenio_financeiro_config->valor): ?>
	<div class="row-fluid">
		<div class="col-md-2">
			<?php echo form_label( '<div class="nome-valor">Valor Unitário</div>', 'valor' ); ?>
			<?php echo form_input( $valor ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Subtotal', 'subtotal' ); ?>
			<?php echo form_input( $subtotal ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Desconto em Real', 'desconto' ); ?>
			<?php echo form_input( $desconto_real ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Desconto %', 'desconto' ); ?>
			<?php echo form_input( $desconto_porcento ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Acréscimo', 'acrescimo' ); ?>
			<?php echo form_input( $acrescimo ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Total', 'total' ); ?>
			<?php echo form_input( $total ); ?>
		</div>
		
		<div class="col-md-2">
			<?php echo form_label('Data Vencimento', 'data_vencimento'); ?>
			<?php echo form_input($data_vencimento); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if($ta_retorno->status == 1): ?>
	<div class="row-fluid">
		<div class="col-md-2">
			<?php echo form_label('Qnt. Retornos', 'qnt_retorno'); ?>
			<?php echo form_input($sessoes_retorno); ?>
		</div>
	</div>
	<?php endif; ?>
	
	<br>
</div>

<div id="data_tiss" class="row-fluid">
	<hr>
	<h4>Data TISS</h4>
	<div class="row-fluid">
		<div class="col-md-3">
			<label>Data Inicial TISS</label>
			<input type="text" name="data_tiss_inicio" class="form-control date">
		</div>

		<div class="col-md-3">
			<label>Frequência</label>
			<select name="dias_semana_tiss[]" class="form-control" multiple="multiple">
				<option value="0">Domingo</option>
				<option value="1">Segunda-Feira</option>
				<option value="2">Terça-Feira</option>
				<option value="3">Quarta-Feira</option>
				<option value="4">Quinta-Feira</option>
				<option value="5">Sexta-Feira</option>
				<option value="6">Sábado</option>
			</select>
		</div>

		<div class="col-md-3">
			<label>Hora Início</label>
			<input type="text" name="hora_tiss_inicio" class="form-control hora">
		</div>

		<div class="col-md-3">
			<label>Hora Fim</label>
			<input type="text" name="hora_tiss_fim" class="form-control hora">
		</div>
	</div>
</div>

<?php if($convenio_financeiro_config->valor): ?>
<hr>

<?php if(!isset($historico_pagamento_verf) && !isset($lancamento_transacao_sucess->id) && !isset($boleto_gerado)): ?>

<div class="row-fluid" id="faturamento">
	<h4>Faturamento</h4>

	<label><input type="checkbox" value="1" id="fluxo-caixa" name="fluxo_caixa" <?php echo isset($tratamento->lancamento)&&$tratamento->lancamento ? 'checked="checked"' : ''?>> Lançar no fluxo de caixa?</label>
	<br style='clear:both'>

	<div class="row-fluid" id="credito-paciente" style="display: none;">
		<div class="col-md-12">
			<div class="alert alert-info" style="text-align: left;">
				<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
				<b><i class="glyphicon glyphicon-info-sign"></i> Aviso de Crédito</b><br>
				Este paciente possui <strong>R$ <span id='saldo-credito-view'></span></strong> de crédito, informe o valor abaixo caso desejar descontar do crédito.
			</div>

			<?php echo form_label('Valor do crédito a ser descontado', 'credito_utilizado' ); ?>
			<?php echo form_input($credito_utilizado); ?>
		</div>
	</div>

	<!--Opção de adicionar novas forma de pagamento-->
	<?php $this->load->view('nova_forma_pagamento'); ?>

	<div class="col-md-4">
		<?php echo form_label('Tipo de Pagamento', 'tipo_pagamento'); ?>
		<?php echo form_dropdown('tipo_pagamento', $tipos_pagamento, set_value('tipo_pagamento', isset($tratamento->tipo_pagamento_id) ? $tratamento->tipo_pagamento_id : $set_tipo_pagamento), 'class="form-control" id="tipo_pagamento"' ); ?>
	</div>
	<div class="col-md-8">
		<?php echo form_label('Formas de pagamento', 'formas_pagamento'); ?>

		<table class="table">
			<tr>
				<td>
					<table border="0" id="formas-pagamento" val="<?php echo isset($tratamento->forma_pagamento) ? $tratamento->forma_pagamento : ''?>">
						
					</table>
				</td>
				<td>
					<table class="table table-condensed" id="prazos">
						
					</table>
				</td>
			</tr>
		</table>
	</div>

	<!--Cartão-->
	<?php $this->load->view('cartao'); ?>

	<div class="row-fluid" id="financeiro" style="display: none;">		
		<div class="col-md-4">
			<?php echo form_label( 'Categoria <span class="required">*</span>', 'tipo_plano_conta' ); ?>
			<?php echo form_dropdown( 'tipo_plano_conta', $categorias_conta, set_value('tipo_plano_conta', isset($tratamento->tipo_plano_conta_id) ? $tratamento->tipo_plano_conta_id : ''), 'class="form-control" id="tipo_plano_conta"' ); ?>
		</div>
		<div class="col-md-4">
			<?php echo form_label( 'Subcategoria <span class="required">*</span>', 'plano_conta' ); ?>
			<?php echo form_dropdown( 'plano_conta', $subcategorias, set_value('plano_conta', isset($tratamento->plano_conta_id) ? $tratamento->plano_conta_id : ''), 'class="form-control" id="plano_conta"' ); ?>
		</div>
		<div class="col-md-4">
			<?php echo form_label( 'Conta <span class="required">*</span>', 'conta' ); ?>
			<?php echo form_dropdown( 'conta', $contas, set_value('conta', isset($tratamento->conta_id) ? $tratamento->conta_id : ''), 'class="form-control" id="conta"' ); ?>
		</div>

		<div class="col-md-12">
			<?php echo form_label('Observações', 'observacoes'); ?>
			<?php echo form_textarea(array('name' => 'observacoes_fluxo', 'class' => 'form-control', 'value' => isset($tratamento->observacao) ? $tratamento->observacao : '')); ?>
		</div>

		<div class="col-md-12">
			<?php echo form_checkbox('pagar_lancamento', true); ?>
			<span style='position:relative; left:5px; top: -2px; font-weight:bold;'>Desejo pagar esse lançamento</span>
		</div>
	</div>
</div>

<?php endif; ?>

<?php endif; ?>

<input type='hidden' id='convenio-financeiro-config' value='<?= $convenio_financeiro_config->valor; ?>'>

<!-- Modal -->
<div class="modal fade" id="addMedico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Inserir Médico</h4>
      </div>
      <div class="modal-body">
      	<div class="form-group">
      		<div class="row-fluid">
      			<div class="col-md-12">
			      	<label>Nome: <span class="required">*</span></label>
			        <input type="text" name="add_medico" id="add-medico" class="form-control">
			    </div>
		    </div>

	        <br>

			<div class="row-fluid">
				<div class="col-md-4">
					<?php echo form_label( 'Conselho <span class="required">*</span>', 'conselho' ); ?>
					<?php echo form_dropdown('conselho', $conselho, $set_conselho, 'class="form-control" id="conselho-medico"'); ?>
				</div>
				
				<div class="col-md-4">
					<?php echo form_label( 'UF Conselho <span class="required">*</span>', 'uf_conselho' ); ?>
					<?php echo form_dropdown('uf_conselho', $uf_conselho, $set_uf_conselho, 'class="form-control" id="uf-medico"'); ?>
				</div>

				<div class="col-md-4">
					<?php echo form_label( 'Núm. Conselho <span class="required">*</span>', 'crm' ); ?>
					<?php echo form_input( $crm ); ?>
				</div>
			</div>

			<br>

			<div class="row-fluid">
				<div class="col-md-12">
					<?php echo form_label( 'Especialidade (CBOS) <span class="required">*</span>', 'cbo' ); ?>
					<?php echo form_dropdown( 'cbos', $cbos, $set_cbos, 'class="form-control" id="cbos-medico"' ); ?>
				</div>
			</div>
	    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="salvar-medico">Salvar</button>
      </div>
    </div>
  </div>
</div>
