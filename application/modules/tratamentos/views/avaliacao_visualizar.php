<?php if($tratamento): ?>
	<div class="info-paciente borda-conteudo">
		<table class="table table-bordered cor-table-body">
			<tr>
				<td colspan="2">
					<small>Paciente:</small><br>
					<strong><?php echo $tratamento->paciente_id; ?> - <?php echo $tratamento->nome;?></strong>
				</td>
				<td>
					<small>Sexo:</small><br>
					    <?php if($tratamento->sexo=='M'): ?>
					        Masculino 
					    <?php elseif($tratamento->sexo=='F'): ?>
					        Feminino
					    <?php endif; ?>
				</td>
				<td>
					<small>Data Nascimento:</small><br>
					<?php echo bd2data($tratamento->data_nascimento);?>
				</td>
				<td>
					<small>Idade:</small><br>
					<?php echo calcular_idade($tratamento->data_nascimento);?>
				</td>
			</tr>

			<tr>
				<td colspan="3">
					<small>Procedimento:</small><br>
					<?php echo $tratamento->procedimento; ?>
				</td>
				<td>
					<small>Especialidade:</small><br>
					<?php echo $tratamento->especialidade;?> <?php echo $tratamento->necessidade ? '/ ' . $tratamento->necessidade : ''; ?>
				</td>
				<td>
					<small>Profissional:</small><br>
					<?php echo $tratamento->profissional; ?>
				</td>
			</tr>

			<?php $this->load->view($view); ?>
		</table>

		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_avaliacao', 'Visualizar Avaliação')): ?>
		<br>
			<?php $this->load->view('tratamentos/avaliacao'); ?>
		<?php endif; ?>
	</div>

<?php else: ?>	
	<?php echo br(3); ?>
		<div class="alert alert-warning" style="text-align: left!important;">
			<div class="row" style="margin-left: 0px;">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<b>Atenção!</b>
					<br>
					O tratamento não existe ou foi removido.
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6 text-right">
					<a href="<?php echo base_url() . "tratamentos/novo"; ?>" class="btn btn-success">
						<i class="glyphicon glyphicon-plus"></i> Novo Tratamento
					</a>
				</div>
			</div>
		</div>
<?php endif ?>
