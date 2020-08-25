<?php echo form_open( current_url() ); ?>

<div class="row-fluid">
	<div class="col-md-3">
		<?php echo form_label( 'Paciente', 'pacientes' ); ?>
		<?php echo form_input( $paciente ); ?>
		<?php echo form_input( $id_paciente ); ?>
	</div>
	<div class="col-md-2">
		<?php echo form_label( 'Médico', 'medicos' ); ?>
		<?php echo form_dropdown( 'medico', $medicos, set_value('medico'), 'class="form-control" id="medicos" required="required"' ); ?>
	</div>
	<div class="col-md-3">
		<?php echo form_label( 'No Guia', 'num_guia' ); ?>
		<?php echo form_input( $num_guia ); ?>
	</div>
	<div class="col-md-2">
		<?php echo form_label( 'Total de Sessões', 'sessoes' ); ?>
		<?php echo form_input( $sessoes ); ?>
	</div>
	<div class="col-md-2">
		<?php echo form_label( 'Sessões Restantes', 'sessoes_restantes' ); ?>
		<?php echo form_input( $sessoes_restantes ); ?>
	</div>
</div>
<br>

<div class="row-fluid">
	<div class="col-md-6">
		<?php echo form_label( 'Especialidade', 'especialidades' ); ?>
		<?php echo form_dropdown( 'especialidade', $especialidades, set_value('especialidade'), 'class="form-control" id="especialidades" required="required"' ); ?>
	</div>
	<div class="col-md-6">
		<?php echo form_label( 'Profissional', 'profissionais' ); ?>
		<?php echo form_dropdown( 'profissional', $profissionais, set_value('profissional'), 'class="form-control" id="profissionais" required="required"' ); ?>
	</div>
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