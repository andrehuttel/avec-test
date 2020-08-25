<a href="<?php echo base_url()."tratamentos/mensalidades" ?>" class="btn btn-default" style="margin-bottom: 20px;">
	<i class="glyphicon glyphicon-chevron-left"></i> Voltar
</a>

<?php echo form_open(current_url()); ?>
	<div class="row-fluid">
		<div class="col-md-6">
			<?php echo form_label('Nome', 'nome'); ?>
			<?php echo form_input($nome); ?>
		</div>

		<div class="col-md-6">
			<?php echo form_label('Valor da Mensalidade', 'valor'); ?>
			<?php echo form_input($valor); ?>
		</div>
	</div>

	<br>

	<div class="row-fluid">
		<div class="col-md-6">
			<?php echo form_label('Quantas sessões pode recuperar no mês', 'recuperar_mes'); ?>
			<?php echo form_input($recuperar_mes); ?>
		</div>

		<div class="col-md-6">
			<?php echo form_label('Dias para recuperar uma sessão', 'dias_recuperar'); ?>
			<?php echo form_input($dias_recuperar); ?>
		</div>
	</div>

	<div class="row-fluid">
		<div class="col-md-12">
			<br>
			<?php echo form_submit($submit); ?>
			<br><br>
		</div>
	</div>

<?php echo form_close(); ?>