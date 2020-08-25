<div class="col-md-12">
	<label><input type="checkbox" value="1" id="comissao-prof-mensalidade" name="comissao_prof_mensalidade" <?php echo isset($tratamento->comissao)&&$tratamento->comissao ? 'checked="checked"' : ''?>> Comissão da mensalidade para mais de um profissional <i class="glyphicon glyphicon-info-sign spanpop-info" data-toggle="popover" data-placement="top" data-content="Essa divisão de comissão servirá para cálculo no relatório de Valor por Profissional."></i></label>
</div>

<div class="row-fluid form-tratamento" id="mensalidade">

	<div class="row-fluid" id="profissional_comissao" style="display: none;">
		<div class="col-md-12">
			<?php echo form_label( 'Profissional', 'profissional_comissao' ); ?>
			<?php echo form_multiselect( 'profissional_comissao[]', isset($profissionais) ? $profissionais : array(), set_value('profissional_comissao', isset($set_profissionais_porcentagem) ? $set_profissionais_porcentagem : ''), 'class="form-control" id="profissionais_comissao"'); ?>
		</div>
	</div>

	<div class="col-md-12" id="comissao-profissionais" style="display: none;">
		<div class="row-fluid comissao-profissionais">
			<table class="table">
				<thead>
					<tr>
						<th>Profissional</th>
						<th width="180">Porcentagem</th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($profissionais_combo)): ?>
						<?php foreach($profissionais_combo as $p): ?>
							<tr class="<?php echo $p->profissional_id; ?>">
								<td><?php echo $p->profissional; ?> <input type="hidden" class="valor_profissional" value="<?php echo $p->porcentagem; ?>"> </td>
								<td><input type="text" name="comissao_prof[<?php echo $p->profissional_id; ?>]" class="form-control money comissao-prof" value="<?php echo number_format($p->porcentagem,2,',','.'); ?>" max="100"></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="row-fluid">
		<div class="col-md-2">
			<?php echo form_label('Controle de Sessões? <i class="glyphicon glyphicon-info-sign spanpop-info" data-toggle="popover" data-placement="top" data-content="Ao assinalar este item o sistema fará o cálculo do número de sessões de acordo com os dias da semana informados."></i>', 'controle_sessoes'); ?> <br>
			<label for="sim">
				<input type="radio" name="controle" class="controle" value="sim" id="sim" <?php if($data_inicio['value']): ?>checked<?php endif; ?>> Sim
			</label>
			<label for="nao">
				<input type="radio" name="controle" class="controle" value="nao" id="nao" <?php if(!$data_inicio['value']): ?>checked<?php endif; ?>> Não
			</label>
			
		</div>
		<div class="controle-sessoes" <?php if(!$data_inicio['value']): ?>style="display:none;"<?php endif; ?>>
			<div class="col-md-4">
				<?php echo form_label('Dias da Semana', 'dias_semana'); ?>
				<?php echo form_multiselect('dias_semana[]', $dias_semana, set_value('dias_semana', isset($set_dias_semana) ? $set_dias_semana : null), 'class="form-control" id="dias_semana"'); ?>
			</div>
			<div class="col-md-4">
				<?php echo form_label('Data de Início', 'data_inicio_tratamento'); ?>
				<?php echo form_input($data_inicio); ?>
			</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="col-md-2">
			<?php echo form_label( 'Plano', 'plano' ); ?>
			<?php echo form_dropdown('plano', $planos, set_value('plano', isset($tratamento) ? $tratamento->plano_mensal : ''), 'class="form-control" id="planos"'); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Sessões', 'sessoes' ); ?>
			<?php echo form_input( $sessoes_fisioterapia ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( '<div class="nome-valor">Valor da Mensalidade</div>', 'valor' ); ?>
			<?php echo form_input( $valor ); ?>
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
		
	</div>

	<div class="row-fluid">
		<div class="col-md-2">
			<?php echo form_label('Data Vencimento', 'data_vencimento'); ?>
			<?php echo form_input($data_vencimento); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label('Data Limite <i class="glyphicon glyphicon-info-sign spanpop-info" data-toggle="popover" data-placement="top" data-content="Serve para limitar no momento do agendamento e para o relatório de planos a vencer."></i>', 'data_limite'); ?>
			<?php echo form_input($data_limite); ?>
		</div>

		<div class="col-md-4">
			<?php echo form_label( 'No Guia <i class="glyphicon glyphicon-info-sign spanpop-info" data-toggle="popover" data-placement="top" data-content="Este campo também pode ser utilizado para número de controle."></i>', 'num_guia' ); ?>
			<?php echo form_input( $num_guia ); ?>
		</div>
	</div>

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
