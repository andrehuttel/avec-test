<div class="row-fluid sessoes-procedimentos" id="sessoes-procedimentos">
	<table class="table">
		<thead>
			<tr>
				<th>Procedimento</th>
				<th width="180">Sessões</th>
				<th width="180">Valor</th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($procedimentos_combo)): ?>
				<?php foreach($procedimentos_combo as $p): ?>
					<tr class="<?php echo $p->procedimento_id; ?>">
						<td><?php echo $p->codigo . ' - ' . $p->procedimento; ?> <input type="hidden" class="valor_procedimento" value="<?php echo $p->valor_procedimento; ?>"> </td>
						<td><input type="text" name="sessoes_procedimento[<?php echo $p->procedimento_id; ?>]" class="form-control sessoes_procedimento" value="<?php echo $p->sessoes_totais; ?>" max="<?php echo $p->sessoes_utilizadas; ?>"></td>
						<td><input type="text" readonly="readonly" name="total_procedimento[<?php echo $p->procedimento_id; ?>]" class="form-control total_procedimento" value="<?php echo number_format($p->sessoes_totais * $p->valor_procedimento,2,',','.'); ?>"></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<div class="row-fluid form-tratamento" id="combo">
	
	<div class="row-fluid">
		<div class="col-md-2">
			<?php $sessoes_fisioterapia['readonly'] = 'readonly'; ?>
			<?php echo form_label( 'Sessões', 'sessoes' ); ?>
			<?php echo form_input( $sessoes_fisioterapia ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Subtotal', 'subtotal' ); ?>
			<?php echo form_input( $subtotal ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Desconto em Real', 'desconto_real' ); ?>
			<?php echo form_input( $desconto_real ); ?>
		</div>
		<div class="col-md-2">
			<?php echo form_label( 'Desconto %', 'desconto_porcento' ); ?>
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
			<?php echo form_label( 'Data de Vencimento', 'data_vencimento' ); ?>
			<?php echo form_input( $data_vencimento ); ?>
		</div>

		<div class="col-md-4">
			<?php echo form_label( 'No Guia <i class="glyphicon glyphicon-info-sign spanpop-info" data-toggle="popover" data-placement="top" data-content="Este campo também pode ser utilizado para número de controle."></i>', 'num_guia' ); ?>
			<?php echo form_input( $num_guia ); ?>
		</div>
	</div>
	
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
