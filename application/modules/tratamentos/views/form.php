<a href="<?php echo base_url()."tratamentos" ?>" class="btn btn-default" style="margin-bottom: 15px;">
	<i class="glyphicon glyphicon-chevron-left"></i> Voltar
</a>

<div class="main-form novo-tratamento sem-borda-conteudo">
<?php echo form_open( current_url() ); ?>

<input type="hidden" name="tratamento_id" id="tratamento_id" value="">

<div class="row-fluid">
	<div class="col-md-6">
		<label for="pacientes">Paciente <span class="required">*</span></label><br>
		<span class="input-group-btn">
			<?php echo form_input($paciente); ?>
			<?php echo form_input($id_paciente); ?>
			<button type="button" class="btn btn-default" id="" data-toggle="modal" data-target=".bs-example-modal-sm"> <i class="glyphicon glyphicon-plus"></i> </button>
		</span>
	</div>
	<div class="col-md-3">
		<?php echo form_label( 'Convênio', 'convenio' ); ?>
		<?php echo form_dropdown( 'convenio', $convenios, set_value('convenio', isset($set_convenio) ? set_value('convenio') : ''), 'class="form-control" id="convenio" required="required"' ); ?>
	</div>
	<div class="col-md-3">
		<?php echo form_label( 'Tipo', 'categorias' ); ?>
		<?php echo form_dropdown( 'categoria', $categorias, set_value('categoria', isset($tratamento) ? $tratamento->categoria_tratamento_id : ''), 'class="form-control" id="categorias" required="required"' ); ?>
	</div>
</div>

<?php if($this->auth_library->check_permission('tratamentos', 'combos', 'index', 'Combos')): ?>
<div class="row-fluid pacote-combo" style="display: none;">
	<div class="col-md-12">
		<?php echo form_label( 'Combos <small>(Selecione um combo pré cadastrado para agilizar o processo)</small>', 'combos' ); ?>
		<?php echo form_dropdown( 'combo', $combos, set_value('combo'), 'class="form-control" id="combos"' ); ?>
	</div>
</div>
<?php endif; ?>

<?php if($this->auth_library->check_permission('tratamentos', 'mensalidades', 'index', 'Mensalidades')): ?>
<div class="row-fluid tratamento-mensalidade" style="display: none;">
	<div class="col-md-12">
		<?php echo form_label( 'Mensalidade <small>(Selecione um tipo de mensalidade)</small>', 'mensalidades' ); ?>
		<?php echo form_dropdown( 'mensalidade', $mensalidades, set_value('mensalidade'), 'class="form-control" id="mensalidades"' ); ?>
	</div>

	<div class="col-md-12">
		<ul class="list-group" style='position: relative;top:5px; display: none' id='mensalidade-detalhes'>
			<li class="list-group-item" style="font-size: 13px">
				<i class="glyphicon glyphicon-calendar"></i> <strong>Quantas sessões pode recuperar no mês: </strong><span class='mensalidade-recuperar-mes'></span><br>
				<i class="glyphicon glyphicon-calendar"></i> <strong>Dias para recuperar uma sessão: </strong><span class='mensalidade-dias-recuperar'></span>
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
		<?php echo form_label( 'Especialidade', 'especialidades' ); ?>
		<?php echo form_dropdown( 'especialidade', $especialidades, set_value('especialidade', isset($tratamento) ? $tratamento->especialidade_id : ''), 'class="form-control" id="especialidades" required="required"' ); ?>
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
		<?php echo form_label( 'Procedimentos', 'procedimentos' ); ?>
		<?php echo form_multiselect( 'procedimento[]', isset($procedimentos) ? $procedimentos : array(), set_value('procedimento[]', isset($set_procedimentos) ? $set_procedimentos : ''), 'class="form-control" id="procedimentos" exibir-valor="'.($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários') ? '1' : '0').'"'); ?>
	</div>
</div>

<div id="load-form">
	<?php if(isset($form) && $form): ?>
		<?php $this->load->view($form)?>
	<?php endif; ?>
</div>

<br>

<div class="row-fluid">
	<div class="col-md-12">
		<br>
		<?php echo form_submit( $submit ); ?>
		<br><br>
	</div>
</div>
<?php echo form_close(); ?>

</div>

<?php $this->load->view('novo_paciente'); ?>
