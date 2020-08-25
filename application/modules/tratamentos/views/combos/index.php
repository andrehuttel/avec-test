<?php if($this->auth_library->check_permission('tratamentos', 'combos', 'novo', 'Registrar Combos')): ?>
<a href="<?php echo base_url(); ?>tratamentos/combos/novo" class="btn btn-success pull-right input_aguarde"> <i class="glyphicon glyphicon-plus-sign"></i> Novo Combo</a>
<?php endif; ?>

<br class='clear'><br>

<div class="scroll">
	<form method="post" action="<?php echo current_url(); ?>" class="sem-borda-conteudo">
		<table class="table table-bordered" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td>
					<input type="text" id="nome" name="nome" class="form-control" placeholder='Nome'>
				</td>

				<td width="110" align="center">
					<button class="btn btn-default" name="filtrar" value="1"><i class="glyphicon glyphicon-search"></i></button>
					<button class="btn btn-<?php echo ($this->session->userdata('filtro_pacote_combo') ? "danger" : "default"); ?>" name="limpar" value="1"><i class="glyphicon glyphicon-erase"></i></button>
				</td>
			</tr>
		</table>
	</form>
</div>

<div class="scroll">
	<table class="table table-bordered">
		<thead>
			<th>Nome</th>
			
			<?php if($this->auth_library->check_permission('tratamentos', 'combos', 'editar', 'Editar Combos')): ?>
			<th width="20">Editar</th>
			<?php endif; ?>

			<?php if($this->auth_library->check_permission('tratamentos', 'combos', 'excluir', 'Remover Combos')): ?>
			<th width="20">Excluir</th>
			<?php endif; ?>
		</thead>

		<?php if($pacotes_combo): ?>
			<?php foreach($pacotes_combo as $pc): ?>
				<tr class='hover2'>
					<td> <?php echo $pc->nome; ?> </td>

					<?php if($this->auth_library->check_permission('tratamentos', 'combos', 'editar', 'Editar Combos')): ?>
					<td><center><a href="<?php echo base_url().'tratamentos/combos/editar/' . $pc->id; ?>" data-toggle="popover" data-placement="top" data-content="Editar" class="spanpop glyphicon glyphicon-edit"></a></center></td>
					<?php endif; ?>

					<?php if($this->auth_library->check_permission('tratamentos', 'combos', 'excluir', 'Remover Combos')): ?>
					<td><center><a href="<?php echo base_url().'tratamentos/combos/excluir/' . $pc->id; ?>" data-toggle="popover" data-placement="top" data-content="Excluir" class="spanpop glyphicon glyphicon-remove deletar"></a></center></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>

		<?php else: ?>
			<tr>
				<td colspan="3">Não contém registros.</td>
			</tr>
		<?php endif ;?>
	</table>
</div>

<div class="breadcrumb">
	<span class="active" style='font-size: 11px;'>
		Total de Resultados: <?= $total_registros; ?>
	</span>

	<span class="pull-right" style="margin-top: -16px; margin-right: -15px;">
		<?php echo $paginacao; ?>
	</span>
</div>