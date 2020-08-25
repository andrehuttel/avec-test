<a href="<?php echo base_url() . "tratamentos/combos" ?>" class="btn btn-default" style="margin-bottom: 20px;">
	<i class="glyphicon glyphicon-chevron-left"></i> Voltar
</a>

<?php echo form_open( current_url() ); ?>
	<div class="row-fluid">
		<div class="col-md-12">
			<?php echo form_label( 'Nome', 'nome' ); ?>
			<?php echo form_input( $nome ); ?>
		</div>
	</div>

	<br>

	<div class="row-fluid">
		<div class="col-md-12">
			<table class="table table-bordered" id="procedimentos-combo">
				<thead>
					<tr>
						<th>Procedimentos</th>
						<th width="200">Sessoes</th>
						<th width="40">Remover</th>
					</tr>
				</thead>

				<tbody>
					<input type='hidden' id='combo-edicao' value='<?php echo (isset($combo) ? 1 : 0); ?>'>

					<?php if(isset($combo)): ?>
						<?php foreach($combo->procedimentos as $pc): ?>
							<tr>
								<td>
									<input type='hidden' name='valor_procedimento[]' class='combo-valor-procedimento' value='<?= $pc->valor_procedimento * $pc->sessao; ?>'>
									<select name="procedimentos[]" class="form-control combo-select-procedimento">
										<option></option>
										<?php foreach($procedimentos as $procedimento): ?>
											<option <?php echo ($pc->procedimento_id == $procedimento->id ? 'selected="selected"' : '') ?> value="<?php echo $procedimento->id;?>"><?php echo $procedimento->convenio.' / '.$procedimento->codigo.' - '.$procedimento->procedimento.' - R$ '.number_format($procedimento->valor, 2,",",".");?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td>
									<input type="text" maxlength="4" name="sessoes[]" value="<?php echo $pc->sessao; ?>" class="form-control combo-sessoes numero">
								</td>
								<td>
									<button type="button" class="btn btn-danger rm-procedimento-combo"><i class="glyphicon glyphicon-remove"></i></button>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>

					<tr>
						<td>
							<input type='hidden' name='valor_procedimento[]' class='combo-valor-procedimento'>
							<select name="procedimentos[]" class="form-control combo-select-procedimento">
								<option></option>
								<?php foreach($procedimentos as $procedimento): ?>
									<option value="<?php echo $procedimento->id;?>"><?php echo $procedimento->convenio.' / '.$procedimento->codigo.' - '.$procedimento->procedimento.' - R$ '.number_format($procedimento->valor, 2,",",".");?></option>
								<?php endforeach; ?>
							</select>
						</td>
						<td>
							<input type="text" maxlength="4" name="sessoes[]" class="form-control combo-sessoes numero">
						</td>
						<td>
							<button type="button" class="btn btn-danger rm-procedimento-combo"><i class="glyphicon glyphicon-remove"></i></button>
						</td>
					</tr>
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="3">
							<button type="button" class='btn btn-success' id="add-procedimento-combo"><i class="glyphicon glyphicon-plus"></i> Adicionar Linha</button>
						</td>
					</tr>
				</tfoot>
			</table>

			<table class="table table-bordered" id="procedimentos-combo">
				<thead>
					<tr>
						<th>Total de Sessões</th>
						<th>Subtotal</th>
						<th>Desconto (R$)</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type='text' class='form-control' id="combo-total-sessoes" readonly=""></td>
						<td><input type='text' class='form-control' id="combo-subtotal" readonly=""></td>
						<td><input type='text' class='form-control money' id="combo-desconto" name='combo_desconto' value='<?= (isset($combo) ? $combo->desconto : ''); ?>'></td>
						<td><input type='text' class='form-control' id="combo-total" readonly=""></td>
					</tr>
					
				</tbody>
			</table>
		</div>
	</div>

	<div class="row-fluid">
		<div class="col-md-12">
			<br>
			<?php echo form_submit( $submit ); ?>
			<br><br>
		</div>
	</div>

<?php echo form_close(); ?>