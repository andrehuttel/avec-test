<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'novo', 'Novo Tratamento')): ?>
<a href="<?php echo base_url(); ?>tratamentos/novo" class="btn btn-success pull-right input_aguarde"> <i class="glyphicon glyphicon-plus-sign"></i> Novo Tratamento </a>
<?php endif; ?>

<br class='clear'><br>

<div class="scroll">
	<form method="post" action="<?php echo current_url(); ?>" class="sem-borda-conteudo">
		<table class="table table-bordered">
			<tr>
				<td>
					<input type="text" name="nome" id="busca_nome" class="form-control" placeholder='Nome'>
					<input type="hidden" name="id_paciente_filtro" id="id_paciente_filtro" value="">
				</td>
				<td>
					<input type="text" name="matricula" id="busca_matricula" class="form-control" placeholder='Matrícula'>
				</td>	
				<td width="150">
					<select name="especialidade_id" class="form-control">
						<option value="">Especialidade</option>
						<?php foreach( $especialidades as $especialidades ): ?>
							<option value="<?php echo $especialidades->id;?>"><?php echo $especialidades->nome; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td width="150">
					<select name="profissional_id" class="form-control">
						<option value="">Profissional</option>
						<?php foreach( $profissionais as $profissional ): ?>
							<option value="<?php echo $profissional->id;?>"><?php echo $profissional->nome; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td width="150">
					<select name="convenio_id" class="form-control">
						<option value="">Convênio</option>
						<?php foreach( $convenio as $conv ): ?>
							<option value="<?php echo $conv->id;?>"><?php echo $conv->nome; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td width="110" align="center">
					<button class="btn btn-default" name="filtrar" value="1"><i class="glyphicon glyphicon-search"></i></button>
					<button class="btn btn-<?php echo ($this->session->userdata('filtro_tratamento') ? "danger" : "default"); ?>" name="limpar" value="1"><i class="glyphicon glyphicon-erase"></i></button>
				</td>
			</tr>
		</table>
	</form>
</div>

<div class="scroll">
	<table class="table table-bordered">
		<thead>
			<th>ID</th>
			<th>Nome</th>
			<th>Matrícula</th>
			<th>Data</th>
			<th>Convênio</th>
			<th>Especialidade</th>
			<th>Guia</th>
			<th>Profissional</th>
			<th width="100">Sessão</th>
			<th width="20">Visual.</th>
			<th width="20">Contrl.</th>
			<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'editar', 'Editar') ): ?>
			<th width="20">Editar</th>
			<?php endif; ?>
			<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'excluir', 'Excluir') ): ?>
			<th width="20">Excluir</th>
			<?php endif; ?>
		</thead>

		<?php if( $tratamentos ): ?>
			<?php foreach( $tratamentos as $tratamento ): ?>
				<tr class='hover2'>
					<td> <?php echo $tratamento->id; ?> </td>
					<td><?php if($tratamento->pausa == 1){echo "<i class='glyphicon glyphicon-ban-circle' title='Este tratamento encontra-se pausado.' style='color:#ac2925'></i> ";} ?> <?php echo "<span title='".$tratamento->nome."'>".mb_substr($tratamento->nome, 0,15); if(strlen($tratamento->nome) > 15){echo "...";} echo "</span>"; ?></td>
					<td> <?php echo $tratamento->matricula; ?> </td>
					<td> <?php echo bd2data($tratamento->data); ?> </td>
					<td> <?php echo $tratamento->convenio; ?> </td>
					<td> <?php echo "<span title='".$tratamento->especialidade."'>".mb_substr($tratamento->especialidade, 0,15); if(strlen($tratamento->especialidade) > 15){echo "...";} echo "</span>"; ?> <?php echo $tratamento->necessidade ? '/ ' . $tratamento->necessidade : ''; ?> </td>
					<td> <?php echo $tratamento->num_guia; ?> </td>
					<td><?php echo "<span title='".$tratamento->profissional."'>".mb_substr($tratamento->profissional, 0,15); if(strlen($tratamento->profissional) > 15){echo "...";} echo "</span>"; ?></td>
					<?php if($tratamento->sessoes_totais): ?>
					<td class="text-center"> 
						<?php $porcentagem_evolucao = ($tratamento->sessao_aberto*100)/$tratamento->sessoes_totais; ?>
						<div class="progress" style='margin-bottom: 0px; text-shadow: 0px 0px 2px #ccc;'>
							<div class="progress-bar progress-bar-success porcentagem-completa-fluxo" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $porcentagem_evolucao ?>%; line-height:20px; display:none; color:#000">
							</div>

							<br style='clear:both'>

							<div class='progress-sessao' style='position:relative; top:-19px;'><?php echo $tratamento->sessao_aberto; ?>/<?php echo $tratamento->sessoes_totais; ?></div>
						</div>
					</td>
					<?php else: ?>
					<td> - </td>
					<?php endif; ?>
					<td><center><a href="<?php echo base_url() . 'tratamentos/visualizar/' . $tratamento->id; ?>" data-toggle="popover" data-placement="top" data-content="Visualizar" class="spanpop glyphicon glyphicon-list-alt"></a></center></td>
					<td><center><a href="<?php echo base_url() . 'tratamentos/controle_presenca/' . $tratamento->id; ?>" data-toggle="popover" data-placement="top" data-content="Controle de Presença" class="spanpop glyphicon glyphicon-th-list" target="_blank"></a></center></td>
					<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'editar', 'Editar') ): ?>
					<td><center><a href="<?php echo base_url() . 'tratamentos/editar/' . $tratamento->id; ?>" data-toggle="popover" data-placement="top" data-content="Editar" class="spanpop glyphicon glyphicon-edit"></a></center></td>
					<?php endif; ?>
					<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'excluir', 'Excluir') ): ?>
					<td><center><a href="<?php echo base_url() . 'tratamentos/excluir/' . $tratamento->id; ?>" data-toggle="popover" data-placement="top" data-content="Remover" class="spanpop glyphicon glyphicon-remove deletar"></a></center></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="13">Não contém registros.</td>
			</tr>
		<?php endif ;?>
	</table>
</div>

<div class="breadcrumb">
  <span class="active" style='font-size: 11px;'>
	Total de Resultados: <?= $count_results; ?>
  </span>
  <span class="pull-right" style="margin-top: -16px; margin-right: -15px;">
	<?php echo $paginacao; ?>
  </span>
</div>
