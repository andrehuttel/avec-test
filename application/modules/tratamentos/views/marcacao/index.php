<div class="content">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	    <h4 class="modal-title" id="myModalLabel">Regiões do Corpo</h4>
  	</div>

  	<div class="modal-body">
	  	<a href="<?= base_url(); ?>tratamentos/marcacao_novo/<?= $id_tratamento; ?>" data-toggle="modal" data-target="#modal-marcacao-imagem" class="btn btn-success pull-right">
			<i class="glyphicon glyphicon-plus-sign"></i> Nova Imagem
		</a>

		<br style='clear:both'><br>
			
		<table class="table table-bordered lista-imagens" id="t<?= $id_tratamento?>">
			<thead>
				<tr>
					<th>Imagem</th>
					<th>Data</th>
					<th>Remover</th>
				</tr>
			</thead>

			<tbody>
				<?php if($marcacao_list): ?>			
					<?php foreach($marcacao_list as $imagem): ?>
						<tr class='hover2'>
							<td> 
								<a href="<?= base_url(); ?>tratamentos/marcacao_visualizar/<?= $imagem->id; ?>" data-toggle="modal" data-target="#modal-marcacao-imagem"><?= mb_convert_case(str_replace('-',' ',$imagem->imagem), MB_CASE_TITLE, 'UTF-8');?> <?= $imagem->observacao ? '(' . $imagem->observacao . ')' : '';?></a>
							</td>

							<td><?= bd2data($imagem->data); ?></td>

							<td>
								<a href="#" id="<?= $imagem->id?>" data-toggle="popover" data-placement="top" data-content="Remover Imagem" class="spanpop glyphicon glyphicon-remove marcacao-remove-imagem"></a>
							</td>
						</tr>
					<?php endforeach; ?>

				<?php else: ?>
					<tr>
						<td colspan="3">Nenhuma imagem adicionada até o momento.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>	

		<br style='clear:both'><br>

		<button type="button" data-dismiss="modal" class="btn btn-default">Fechar</button>
	</div>
</div>
