<?php if($this->auth_library->check_permission('tratamentos', 'mensalidades', 'novo', 'Registrar Mensalidades')): ?>
<a href="<?php echo base_url(); ?>tratamentos/mensalidades/novo" class="btn btn-success pull-right input_aguarde"> <i class="glyphicon glyphicon-plus-sign"></i> Nova Mensalidade</a>
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
					<button class="btn btn-<?php echo ($this->session->userdata('filtro_mensalidade') ? "danger" : "default"); ?>" name="limpar" value="1"><i class="glyphicon glyphicon-erase"></i></button>
				</td>
			</tr>
		</table>
	</form>
</div>

<div class="scroll">
	<table class="table table-bordered">
		<thead>
			<th>Nome</th>
			<?php if($this->auth_library->check_permission('tratamentos', 'mensalidades', 'editar', 'Editar Mensalidades')): ?>
			<th width="20">Editar</th>
			<?php endif; ?>

			<?php if($this->auth_library->check_permission('tratamentos', 'mensalidades', 'excluir', 'Remover Mensalidades')): ?>
			<th width="20">Excluir</th>
			<?php endif; ?>
		</thead>

		<?php if($mensalidades): ?>
			<?php foreach($mensalidades as $item): ?>
				<tr class='hover2'>
					<td> <?php echo $item->nome; ?> </td>

					<?php if($this->auth_library->check_permission('tratamentos', 'mensalidades', 'editar', 'Editar Mensalidades')): ?>
					<td><center><a href="<?php echo base_url().'tratamentos/mensalidades/editar/' . $item->id; ?>" data-toggle="popover" data-placement="top" data-content="Editar" class="spanpop glyphicon glyphicon-edit"></a></center></td>
					<?php endif; ?>

					<?php if($this->auth_library->check_permission('tratamentos', 'mensalidades', 'excluir', 'Remover Mensalidades')): ?>
					<td><center><a href="<?php echo base_url().'tratamentos/mensalidades/excluir/' . $item->id; ?>" data-toggle="popover" data-placement="top" data-content="Excluir" class="spanpop glyphicon glyphicon-remove deletar"></a></center></td>
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