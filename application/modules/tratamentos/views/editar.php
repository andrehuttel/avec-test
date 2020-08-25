<a href="<?php echo base_url()."tratamentos" ?>" class="btn btn-default" style="margin-bottom: 15px;">
	<i class="glyphicon glyphicon-chevron-left"></i> Voltar
</a>

<a href="<?php echo base_url() . 'tratamentos/visualizar/' . $id; ?>" class="btn btn-info pull-right" style="margin-bottom: 15px;">
	<i class="glyphicon glyphicon-list-alt"></i> Visualizar
</a>


<div class="main-form editar-tratamento sem-borda-conteudo">
	<?php echo form_open( current_url(), 'id="editar_tratamento"' ); ?>

	<input type="hidden" name="tratamento_id" id="tratamento_id" value="<?php echo $tratamento->id;?>">
	<input type="hidden" name="avaliacao_row" id="avaliacao_row" value="<?php echo $avaliacao_row;?>">
	<input type="hidden" name="combo_utilizado" id="combo_utilizado" value="<?php echo $combo_utilizado;?>">

	<div class="row-fluid">
		<div class="col-md-6">
			<?php echo form_label( 'Paciente <span class="required">*</span>', 'pacientes' ); ?>
			<?php echo form_input( $paciente ); ?>
			<?php echo form_input( $id_paciente ); ?>
		</div>
		<div class="col-md-3">
			<?php echo form_label( 'Convênio', 'convenio' ); ?>
			<?php echo form_dropdown( 'convenio', $convenios, set_value('convenio', isset($tratamento) ? $tratamento->convenio_id : ''), 'class="form-control" id="convenio" required="required"' ); ?>
		</div>
		<div class="col-md-3">
			<?php echo form_label( 'Tipo', 'categorias' ); ?>
			<?php echo form_dropdown( 'categoria', $categorias, set_value('categoria', isset($tratamento) ? $tratamento->categoria_tratamento_id : ''), 'class="form-control" id="categorias" required="required"' ); ?>
		</div>
	</div>

	<?php if($this->auth_library->check_permission('tratamentos', 'mensalidades', 'index', 'Mensalidades')): ?>
	<div class="row-fluid tratamento-mensalidade" style="display: none;">
		<div class="col-md-12">
			<?php echo form_label( 'Mensalidade <small>(Selecione um tipo de mensalidade)</small>', 'mensalidades' ); ?>
			<?php echo form_dropdown( 'mensalidade', $mensalidades, set_value('mensalidade', isset($tratamento) ? $tratamento->tratamento_mensalidade_id : ''), 'class="form-control" id="mensalidades"' ); ?>
		</div>

		<div class="col-md-12">
			<ul class="list-group" style='position: relative;top:5px;' id='mensalidade-detalhes'>
				<li class="list-group-item" style="font-size: 13px">
					<i class="glyphicon glyphicon-calendar"></i> <strong>Quantas sessões pode recuperar no mês: </strong><span class='mensalidade-recuperar-mes'><?= $tratamento->recuperar_mes ?></span><br>
					<i class="glyphicon glyphicon-calendar"></i> <strong>Dias para recuperar uma sessão: </strong><span class='mensalidade-dias-recuperar'><?= $tratamento->dias_recuperar ?></span>
				</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<div class="row-fluid">
		<div class="col-md-6">
			<?php echo form_label( 'Necessidade', 'necessidades' ); ?>
			<?php echo form_dropdown( 'necessidade', $necessidades, set_value('necessidades', isset($tratamento) ? $tratamento->necessidade_id : ''), 'class="form-control" id="necessidades"' ); ?>
		</div>
		<div class="col-md-6">
			<?php if ($avaliacao_row > 0) {
				echo form_label( 'Especialidade', 'especialidades' ).'<div class="label label-danger">Avaliação realizada, não será possivel alterar este campo!</div>';
				echo form_dropdown( 'especialidade', $especialidades, set_value('especialidade', isset($tratamento) ? $tratamento->especialidade_id : ''), 'class="form-control" id="especialidades" required="required" disabled' );
				echo form_hidden('especialidade', $tratamento->especialidade_id);
			} else {
				echo form_label( 'Especialidade', 'especialidades' );
				echo form_dropdown( 'especialidade', $especialidades, set_value('especialidade', isset($tratamento) ? $tratamento->especialidade_id : ''), 'class="form-control" id="especialidades" required="required"' );
			} ?>
		</div>
	</div>

	<div class="row-fluid">
		<div class="col-md-<?= (!$controle_clinicas ? '12' : '6') ?>">
			<?php echo form_label( 'Profissional', 'profissionais' ); ?>
			<?php echo form_dropdown( 'profissional', $profissionais, set_value('profissional', isset($tratamento) ? $tratamento->profissional_id : ''), 'class="form-control" id="profissionais" required="required"' ); ?>
		</div>

		<?php
		echo '<div '.(!$controle_clinicas ? 'style="display: none"' : null).'>';
			echo '<div class="col-md-6">';
				echo form_label('Clínica: <span class="required">*</span>', 'clinica');
				echo br();
				echo form_dropdown('clinica', $clinica, $set_clinica, 'class="form-control" id="clinica" required');
			echo '</div>';
			echo br();
		echo '</div>';
		?>
	</div>

	<div class="row-fluid" id="procedimento">
		<div class="col-md-12">
			<?php echo form_label( 'Procedimentos', 'procedimentos' ) ?>
			<?php echo form_multiselect( 'procedimento[]', isset($procedimentos) ? $procedimentos : array(), set_value('procedimento', isset($trata_proced) ? $trata_proced : ''), 'class="form-control" id="procedimentos" exibir-valor="'.($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? '1' : '0').'"' ); ?>
		</div>
	</div>
	<div class="row-fluid" id='procedimento-aplicar'>
		<div class="col-md-12">
			<input type="checkbox" name="aplicar_procedimento" value="1"><strong> Aplicar em todas as sessões (Abertas e Finalizadas)</strong>
		</div>
	</div>

	<div id="load-form">
		<?php if(isset($form) && $form): ?>
			<?php $this->load->view($form)?>
		<?php endif; ?>
	</div>
		
	<div class="row-fluid">
		<div class="col-md-12">
			<br>
			<?php echo form_submit( $submit ); ?>
			<br><br>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>

<br>

<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'detalhes_lancamento', 'Detalhes do Lançamento Caixa') ): ?>
	<?php if(isset($registro) && $registro): ?>
	
	<h4>Lançamento Caixa Atual</h4>

	<?php echo $this->load->view('financeiro/detalhes_lancamento'); ?>

	<?php endif; ?>
<?php endif; ?>

<?php $categoria_verif = isset($tratamento->categoria_tratamento_id) ? $tratamento->categoria_tratamento_id : null; ?>
<input type='hidden' id='editar-tratamento-convenio' value='<?= ($categoria_verif == 4 && $convenio_financeiro_config->valor ? 1 : 0) ?>'>