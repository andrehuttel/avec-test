<?php if($tratamento->pausa == 1){ ?>
	<div class="alert alert-warning alert-dismissible" role="alert" style="margin-bottom: 30px!important;">
  		<strong>Tratamento Pausado!</strong> Este tratamento encontra-se pausado.
  		<br><br>
  		<a href="<?php echo base_url() . 'tratamentos/reativacao_tratamento/' . $id; ?>" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-ok-circle"></i> Reativar Tratamento</a>
	</div>
<?php } ?>
<a href="<?php echo base_url() . 'tratamentos';?>" id="" class="btn btn-default pull-left" style="margin:0 5px;">
	<i class="glyphicon glyphicon-chevron-left"></i> Voltar
</a>

<?php if($tratamento): ?>
	<style>table, .alert{margin-bottom: 0px!important;}</style>

	<div class="btn-group pull-right" id="acoes-tratamento">
		<a href="<?php echo base_url() . 'tratamentos/editar/' . $id; ?>" id="" class="btn btn-info">
			<i class="glyphicon glyphicon-edit"></i> Editar
		</a>
		
		<a href="<?php echo base_url() . 'tratamentos/anexos/' . $id; ?>" id="" class="btn btn-info">
			<i class="glyphicon glyphicon-paperclip"></i> Anexos
		</a>

		<a id="btn-imprimir" class="btn btn-info">
			<i class="glyphicon glyphicon-print"></i> Imprimir
		</a>

		<a href="<?php echo base_url() . 'tratamentos/imprimir_contrato/' . $id; ?>" target="_blank" class="btn btn-info">
			<i class="glyphicon glyphicon-copy"></i> Contrato
		</a>

		<a href="<?php echo base_url() . 'tratamentos/imprimir_termo/' . $tratamento->especialidade_id; ?>" target="_blank" class="btn btn-info">
			<i class="glyphicon glyphicon-copy"></i> Termo
		</a>

		<a href="<?php echo base_url() . 'pacientes/prontuario/' . $tratamento->paciente_id; ?>" class="btn btn-info">
			<i class="glyphicon glyphicon-list-alt"></i> Prontuário
		</a>

		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'marcacao', 'Regiões do Corpo')): ?>
			<a href="<?php echo base_url(); ?>tratamentos/marcacao/<?php echo $id; ?>" data-toggle="modal" data-target="#modal-marcacao" class="btn btn-info">
				<i class="glyphicon glyphicon-th"></i> Regiões do Corpo
			</a>
		<?php endif; ?>

		<button class="btn btn-info dropdown-toggle" type="button" id="outros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
	    	Outras Ações
	    	<span class="caret"></span>
	    </button>
		  <ul class="dropdown-menu" aria-labelledby="outros">
			<?php
				if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'alta_tratamento', 'Alta no Tratamento') and !$historico_alta_tratamento and !$historico_cancelamento_tratamento){ 
					$info_alta_tratamento = "";
					$info_btn_alta_tratamento = "btn-alta-tratamento";
					// if($tratamento->sessao_ativa == 0){
					// 	$info_alta_tratamento = "disabled data-toggle='tooltip' title='Não é possível dar alta no tratamento sem evolução.'";
					// 	$info_btn_alta_tratamento = "";
					// }else if($tratamento->sessoes_restantes == 0){
					if($tratamento->sessoes_restantes == 0){
						$info_alta_tratamento = "disabled data-toggle='tooltip' title='Tratamento já finalizado!'";
						$info_btn_alta_tratamento = "btn-alta-tratamento2";
					}
			?>
				<li class="<?php echo $info_btn_alta_tratamento; ?>" <?php echo $info_alta_tratamento; ?>>
					<a href="#"><i class="glyphicon glyphicon-thumbs-up"></i> Alta no Tratamento</a>
				</li>
			<?php } ?>
			<?php
			if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'pausa_tratamento', 'Pausa no Tratamento') and !$historico_alta_tratamento and !$historico_cancelamento_tratamento){ 
					if($tratamento->pausa == 1){
						$info_pausa_tratamento = "";
						$info_btn_pausa_tratamento = "btn-reativar-tratamento";
					}else{
						$info_pausa_tratamento = "";
						$info_btn_pausa_tratamento = "btn-pausa-tratamento";
					}
					if($tratamento->sessoes_restantes == 0){
						$info_pausa_tratamento = "disabled data-toggle='tooltip' title='Tratamento já finalizado!'";
						$info_btn_pausa_tratamento = "btn-pausa-tratamento2";
					}
			?>	
				<?php if($tratamento->pausa == 1){ ?>
					<li class="<?php echo $info_btn_pausa_tratamento; ?>" <?php echo $info_pausa_tratamento; ?>>
						<a href="<?php echo base_url() . 'tratamentos/reativacao_tratamento/' . $id; ?>"><i class="glyphicon glyphicon-ok-circle"></i> Reativar Tratamento</a>
					</li>
				<?php }else{ ?>
					<li class="<?php echo $info_btn_pausa_tratamento; ?>" <?php echo $info_pausa_tratamento; ?>>
						<a href="#"><i class="glyphicon glyphicon-ban-circle"></i> Pausa no Tratamento</a>
					</li>
				<?php } ?>
			<?php } ?>
			<?php
				if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'cancelamento_tratamento', 'Cancelamento no Tratamento') and !$historico_cancelamento_tratamento and !$historico_alta_tratamento){ 
					$info_cancelamento_tratamento = "";
					$info_btn_cancelamento_tratamento = "btn-cancelamento-tratamento";
					if($tratamento->sessoes_restantes == 0){
						$info_cancelamento_tratamento = "disabled data-toggle='tooltip' title='Tratamento já finalizado!'";
						$info_btn_cancelamento_tratamento = "btn-cancelamento-tratamento2";
					}
				
			?>
				<li class="<?php echo $info_btn_cancelamento_tratamento; ?>" <?php echo $info_cancelamento_tratamento; ?>>
					<a href="#"><i class="glyphicon glyphicon-remove-circle"></i> Cancelamento</a>
				</li>
			<?php } ?>
			<?php
				if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'alta_tratamento', 'Alta no Tratamento') and $historico_alta_tratamento){ 
					$info_alta_tratamento = "data-toggle='tooltip' title='Tratamento já finalizado!'";
					$info_btn_alta_tratamento = "btn-alta-tratamento3";

			?>
				<li class="<?php echo $info_btn_alta_tratamento; ?>" <?php echo $info_alta_tratamento; ?>>
					<a href="#"><i class="glyphicon glyphicon-thumbs-up"></i> Alta no Tratamento</a>
				</li>
			<?php } ?>
			<?php
				if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'cancelamento_tratamento', 'Cancelamento no Tratamento') and $historico_cancelamento_tratamento){ 
					$info_cancelamento_tratamento = "data-toggle='tooltip' title='Tratamento já finalizado!'";
					$info_btn_cancelamento_tratamento = "btn-cancelamento-tratamento3";

			?>
				<li class="<?php echo $info_btn_cancelamento_tratamento; ?>" <?php echo $info_cancelamento_tratamento; ?>>
					<a href="#"><i class="glyphicon glyphicon-remove-circle"></i> Cancelamento</a>
				</li>
			<?php } ?>
				<?php if ($tratamento->convenio_id != 1): ?>
					<li>
						<a href="<?php echo base_url() . 'tratamentos/imprimir_guia/' .$id; ?>" target="_blank"><i class="glyphicon glyphicon-file"></i> Guia SP/SADT</a>
					</li>
				<?php endif ?>
		  </ul>
		</div>

	<?php echo br(3); ?>

	<!-- Descrição de alta do tratamento -->
	<form action="<?php echo base_url() . "tratamentos/alta_tratamento/$id"; ?>" method="post" class="fundo-descricao-alta-tratamento" style="display: none; margin: 20px 0px; padding: 0px; border: 0px;">
		<div class="panel panel-default">
			<div class="panel-heading" style="padding-bottom: 40px;">
				<h4 class="panel-title">
					Alta no Tratamento

					<textarea name="descricao" class="form-control" rows="10" style="margin: 5px 0px; max-width: 100%; min-width: 100%;" placeholder="Escreva o motivo da alta no tratamento..." required maxlength="500"></textarea>

					<button type="submit" class="btn btn-success pull-right">
						<i class="glyphicon glyphicon-ok"></i> Enviar
					</button>
				</h4>
			</div>
		</div>
	</form>
	<form action="<?php echo base_url() . "tratamentos/pausa_tratamento/$id"; ?>" method="post" class="fundo-descricao-pausa-tratamento" style="display: none; margin: 20px 0px; padding: 0px; border: 0px;">
		<div class="panel panel-default">
			<div class="panel-heading" style="padding-bottom: 40px;">
				<h4 class="panel-title">
					Pausa no Tratamento

					<textarea name="descricao" class="form-control" rows="10" style="margin: 5px 0px; max-width: 100%; min-width: 100%;" placeholder="Escreva o motivo da pausa no tratamento..." required maxlength="500"></textarea>

					<button type="submit" class="btn btn-success pull-right">
						<i class="glyphicon glyphicon-ok"></i> Enviar
					</button>
				</h4>
			</div>
		</div>
	</form>
	<form action="<?php echo base_url() . "tratamentos/cancelamento_tratamento/$id"; ?>" method="post" class="fundo-descricao-cancelamento-tratamento" style="display: none; margin: 20px 0px; padding: 0px; border: 0px;">
		<div class="panel panel-default">
			<div class="panel-heading" style="padding-bottom: 40px;">
				<h4 class="panel-title">
					Cancelamento

					<textarea name="descricao" class="form-control" rows="10" style="margin: 5px 0px; max-width: 100%; min-width: 100%;" placeholder="Escreva o motivo do cancelamento..." required maxlength="500"></textarea>

					<br>

					Valor do Crédito
					<input type='text' class='form-control money' name='valor_credito' placeholder="Preencher caso o paciente tenha algum crédito no tratamento">

					<br>

					<button type="submit" class="btn btn-success pull-right">
						<i class="glyphicon glyphicon-ok"></i> Enviar
					</button>
				</h4>
			</div>
		</div>
	</form>

	<div class="info-paciente borda-conteudo">
		
		<b><small>Cadastrado <?php echo ($tratamento->usuario_tratamento ? ' por '.$tratamento->usuario_tratamento : null); ?> em:</small></b>
		<?php echo $tratamento->data_cadastro ? bd2data($tratamento->data_cadastro) . ' ' . $tratamento->hora : br();?>
		<br><br>

		<table class="table table-bordered cor-table-body">
			<tr>
				<td colspan="4">
					<small>Carteirinha (matricula):</small><br>
					<strong><?php echo $tratamento->matricula ? $tratamento->matricula : br();?></strong>
				</td>

				<td>
					<small>ID:</small><br>
					<strong><?php echo $id; ?></strong>
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<small>Tipo:</small><br>
					<?php echo $tratamento->tipo; ?>
				</td>
			</tr>
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
					<?php if($tratamento->categoria_tratamento_id != 3): ?>
						<small>Procedimento:</small><br>
						<?php echo $tratamento->procedimento; ?>

					<?php else: ?>
						<small>Mensalidade:</small><br>
						<?php echo $tratamento->tratamento_mensalidade; ?>
					<?php endif; ?>
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

			<tr>
				<td colspan="5">
					<small>Observações do Financeiro:</small><br>
					<?php echo $tratamento->observacao_tratamento; ?>
				</td>
			</tr>
			
			<?php

				if($historico_alta_tratamento){
					echo "
					<tr>
						<td colspan='5'>
						<small>Observações do tratamento:</small>

						<table class='table table-bordered' style='margin-bottom: 0px; margin-top: 6px;'>
							<thead>
								<tr>
									<th class='text-center' colspan='4'>Alta no Tratamento</th>
								</tr>
							</thead>

							<thead>
								<tr>
									<th width='150' class='text-center'>Data e Hora</th>
									<th width='150' class='text-center'>Usuário</th>
									<th>Descrição</th>
									<th width='150' class='text-center'>Sessões Totais</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class='text-center'>". bd2datahora($historico_alta_tratamento->modificacao) ."</td>
									<td class='text-center'>". $historico_alta_tratamento->usuario ."</td>
									<td>". $historico_alta_tratamento->descricao ."</td>
									<td class='text-center'>". $historico_alta_tratamento->sessoes_totais ."</td>
								</tr>
							</tbody>
						</table>

						</td>
					</tr>
					";
				}

			?>
			<?php

				if($historico_pausa_tratamento){
					echo "
					<tr>
						<td colspan='5'>
						<small>Observações do tratamento:</small>

						<table class='table table-bordered' style='margin-bottom: 0px; margin-top: 6px;'>
							<thead>
								<tr>
									<th class='text-center' colspan='4'>Pausa no Tratamento</th>
								</tr>
							</thead>

							<thead>
								<tr>
									<th width='150' class='text-center'>Data e Hora</th>
									<th width='150' class='text-center'>Usuário</th>
									<th>Descrição</th>
								</tr>
							</thead>
							<tbody>";
								foreach ($historico_pausa_tratamento as $key => $value) {
									echo "<tr>
										<td class='text-center'>". bd2datahora($value->modificacao) ."</td>
										<td class='text-center'>". $value->usuario_nome ."</td>
										<td>". $value->descricao ."</td>
									</tr>";
								}
							echo "</tbody>
						</table>

						</td>
					</tr>
					";
				}

			?>
			<?php

				if($historico_reativacao_tratamento){
					echo "
					<tr>
						<td colspan='5'>
						<small>Observações do tratamento:</small>

						<table class='table table-bordered' style='margin-bottom: 0px; margin-top: 6px;'>
							<thead>
								<tr>
									<th class='text-center' colspan='4'>Reativação no Tratamento</th>
								</tr>
							</thead>

							<thead>
								<tr>
									<th width='150' class='text-center'>Data e Hora</th>
									<th width='150' class='text-center'>Usuário</th>
								</tr>
							</thead>
							<tbody>";
								foreach ($historico_reativacao_tratamento as $key => $value) {
									echo "<tr>
										<td class='text-center'>". bd2datahora($value->modificacao) ."</td>
										<td class='text-center'>". $value->usuario_nome ."</td>
									</tr>";
								}
							echo "</tbody>
						</table>

						</td>
					</tr>
					";
				}

			?>
			<?php

				if($historico_cancelamento_tratamento){
					echo "
					<tr>
						<td colspan='5'>
						<small>Observações do tratamento:</small>

						<table class='table table-bordered' style='margin-bottom: 0px; margin-top: 6px;'>
							<thead>
								<tr>
									<th class='text-center' colspan='5'>Cancelamento</th>
								</tr>
							</thead>

							<thead>
								<tr>
									<th width='150' class='text-center'>Data e Hora</th>
									<th width='150' class='text-center'>Usuário</th>
									<th>Descrição</th>
									<th>Valor do Crédito</th>
									<th width='150' class='text-center'>Sessões Totais</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class='text-center'>". bd2datahora($historico_cancelamento_tratamento->modificacao) ."</td>
									<td class='text-center'>". $historico_cancelamento_tratamento->usuario ."</td>
									<td>". $historico_cancelamento_tratamento->descricao ."</td>
									<td>".($historico_cancelamento_tratamento->valor_credito ? 'R$ '.number_format($historico_cancelamento_tratamento->valor_credito, 2, ',', '.') : '-')."</td>
									<td class='text-center'>". $historico_cancelamento_tratamento->sessoes_totais ."</td>
								</tr>
							</tbody>
						</table>

						</td>
					</tr>
					";
				}

			?>
			
		</table>

		<?php if($mensalidade): ?>
		<table class="table table-bordered">
			<tr>
				<th colspan="4"><center>Mensalidade</center></th>
			</tr>
			<tr>
				<th>Mês de Vencimento</th>
				<th>Competência</th>
				<th>Sessões</th>
				<th>Valor</th>
			</tr>
			<?php foreach($mensalidade as $mensal): ?>
				<tr>
					<td><?php echo bd2data($mensal->vencimento); ?></td>
					<td><?php echo bd2data($mensal->competencia); ?></td>
					<td><?php echo $mensal->sessoes; ?></td>
					<td>R$ <?php echo number_format($mensal->valor,2,',','.'); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php endif; ?>

		<br>

		<?php if($tratamento->categoria_tratamento_id != 3): ?>
		<div class="panel-group" id="accordionRelatorio" role="tablist" aria-multiselectable="true">

		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'procedimentos_das_evolucoes', 'Visualizar Procedimentos Previstos') ): ?>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingRelatorio">
		      <h4 class="panel-title">
		        <a role="button" data-toggle="collapse" data-parent="#accordionRelatorio" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
		          Procedimentos Previstos
		        </a>
		      </h4>
		    </div>
		    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRelatorio">
		      <div class="panel-body" style="padding: 0px;">
		      	<div style='color: #999; padding: 5px; font-size: 11px'>
		      		<i class='glyphicon glyphicon-info-sign'></i> Procedimento selecionado no momento de registro do tratamento.
		      	</div>

		        <table class="table table-bordered">
		          <thead>
			          <tr>
			            <th width="100">Data</th>
			            <th width="80">Sessão</th>
			            <th>Procedimento</th>
			            <th width="180"><?php if($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários')): ?>Valor<?php endif; ?></th>
			          </tr>
		      	  </thead>
		      	  <tbody>
					<?php 
						$count_rel_proc_prev = 0;
						foreach($relatorio_procedimentos->previsto as $previsto): 
					?>
						<tr>
							<td><?php echo bd2data($previsto->data); ?></td>
							<td><?php echo $previsto->sessao; ?></td>
							<td><?php echo $previsto->procedimento; ?></td>
							<td><?php if($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários')): ?>R$ <?php echo number_format($previsto->valor,2,',','.'); ?><?php endif; ?></td>
						</tr>
					<?php 
						$count_rel_proc_prev++;
						endforeach;

						if($count_rel_proc_prev == 0){
							echo "
							<tr>
								<td colspan='4'>Nenhuma informação encontrada.</td>
							</tr>
							";
						}
					?>
		      	  </tbody>
		        </table>

		      </div>
		    </div>
		  </div>
		<?php endif; ?>

		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'procedimentos_realizados', 'Visualizar Procedimentos Realizados') ): ?>
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingRelatorio">
		      <h4 class="panel-title">
		        <a role="button" data-toggle="collapse" data-parent="#accordionRelatorio" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
		          Procedimentos Realizados
		        </a>
		      </h4>
		    </div>
		    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRelatorio">
		      <div class="panel-body" style="padding: 0px;">
		      
		        <table class="table table-bordered">
		        	<thead>
			          <tr>
			            <th width="100">Data</th>
			            <th width="80">Sessão</th>
			            <th>Procedimento</th>
			            <th width="180"><?php if($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários')): ?>Valor<?php endif; ?></th>
			          </tr>
		          	</thead>
		          	<tbody>
						<?php 
							$count_rel_proc_rea = 0;
							foreach($relatorio_procedimentos->realizados as $realizado): 
						?>
							<tr>
								<td><?php echo bd2data($realizado->data); ?></td>
								<td><?php echo $realizado->sessao; ?></td>
								<td>
									<?php if($tratamento->categoria_tratamento_id == 5 && isset($movimentacao[$realizado->id])): ?>
										<table class="table table-bordered">
											<tr>
												<th>Procedimento</th>
												<th width="60">Sessões</th>
											</tr>
											<?php $valor_procedimento_realizado = 0; ?>
											<?php foreach($movimentacao[$realizado->id] as $mov): ?>
												<?php $valor_procedimento_realizado += $mov->valor; ?>
												<tr>
													<td><?php echo $mov->codigo . ' - ' . $mov->procedimento; ?></td>
													<td><center><?php echo $mov->sessoes_utilizadas; ?></center></td>
												</tr>
											<?php endforeach; ?>
										</table>

									<?php else: ?>
										<?php echo $realizado->procedimento; ?>
									<?php endif; ?>
										
								</td>
								<td>
									<?php if($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários')): ?>
										<?php if($tratamento->categoria_tratamento_id == 5): ?>
											R$ <?php echo number_format($valor_procedimento_realizado,2,',','.'); ?>

										<?php else: ?>
											R$ <?php echo number_format($realizado->valor,2,',','.'); ?>
										<?php endif; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php 
							$count_rel_proc_rea++;
							endforeach; 

							if($count_rel_proc_rea == 0){
								echo "
								<tr>
									<td colspan='4'>Nenhuma informação encontrada.</td>
								</tr>
								";
							}
						?>
		          	</tbody>
		        </table>

		      </div>
		    </div>
		  </div>
		<?php endif; ?>

		</div>
		<?php endif; ?>

		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_avaliacao', 'Visualizar Avaliação')): ?>
		<br>

		<h4>Avaliação</h4>
		
		<?php $this->load->view('tratamentos/avaliacao'); ?>

		<br>

		<?php if($avaliacao['formulario']): ?>
			<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'habilitar_avaliacao_tratamento', 'Habilitar Edição da Avaliação') ): ?>
				<?php if($tratamento->data_edicao == NULL || $tratamento->data_edicao != date('Y-m-d')): ?>
				<a href="<?php echo base_url() . 'tratamentos/editar_avaliacao/' . (int) $tratamento->id_avaliacao . '/' . $id; ?>" class="btn btn-success" title="Habilitar edição de avaliação">Habilitar Edição</a>
				<?php else: ?>
				<a href="<?php echo base_url() . 'tratamentos/editar_avaliacao/' . (int) $tratamento->id_avaliacao . '/' . $id; ?>" class="btn btn-danger" title="Desabilitar edição de avaliação">Desabilitar Edição</a>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

		<br><br>

		<?php endif; ?>

		<?php if($this->auth_library->check_permission('relatorio', 'relatorio', 'controle_presenca', 'Controle de Presença')): ?>
			<a href="<?=base_url()?>relatorio/controle_presenca/<?=$tratamento->paciente_id?>" target="_blank"><span class="btn btn-primary">Controle de Presença</span></a>
			<br><br>
		<?php endif; ?>

		<!-- Historico de atendimentos da especialidade -->
		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'historico_atendimentos_especialidade', 'Histórico de Atendimentos')){ ?>
			<?php $this->load->view('historico_atendimentos_especialidade'); ?>
		<?php } ?>

		<!-- Historico de todos atendimentos do paciente -->
		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'historico_todos_atendimentos_paciente', 'Histórico de Todos os Atendimentos')){ ?>
			<?php $this->load->view('historico_todos_atendimentos_paciente'); ?>
		<?php } ?>

		<!-- Historico de avaliações do paciente -->
		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'avaliacao_visualizar', 'Histórico de Avaliações do Paciente')): ?>
			<?php $this->load->view('tratamentos/historico_avaliacao'); ?>
		<?php endif; ?>

		<!-- View de Antes e Depois -->
		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_antes_depois', 'Antes e Depois')): ?>
			<h4>Antes e Depois</h4>

			<form action="<?php echo base_url("tratamentos/salvar_antes_depois/$tratamento->tratamento_id"); ?>" method="post" class="sem-borda-conteudo" enctype="multipart/form-data">
				<?php echo $this->load->view("tratamentos/antes_depois"); ?>
			</form>

			<!-- Historico de Antes e Depois -->
			<div class="panel-group" id="accordion" style="margin-top: 15px;">
			    <div class="panel panel-default">
			        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#historico_antes_depois">
			            <h4 class="panel-title">
			                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
			                    <i class='glyphicon glyphicon-time'></i> Histórico do Antes e Depois
			                </a>
			            </h4>
			        </div>
			        <div id="historico_antes_depois" class="panel-collapse collapse">

			        	<table class="table table-bordered table-condensed">
			        		<thead>
			        			<tr>
			        				<th class="text-center">Foto Antes</th>
			        				<th class="text-center">Foto Depois</th>
			        			</tr>
			        		</thead>
			        		<tbody>
			        			<?php

			        				if($tratamento->antes_depois){
			        					foreach($tratamento->antes_depois as $key => $foto){
			        						echo "
			        						<tr>
			        							<td class='conteudo-imagem-historico'>";
			        							if($foto->data_antes){
			        								echo "
			        								<div class='box-antes'>".bd2data($foto->data_antes)."
			        								<i class='glyphicon glyphicon-remove pull-right remover-img' style='color:#b92c28;cursor:pointer;margin-right:2px;font-size:18px;' hist-id='$foto->id' img-remover='antes'></i>
			        								</div>
			        								<img src='". base_url("uploads/antes_depois/$foto->antes") ."'>";
			        							} else {
			        								echo "
				        								<form action='".base_url("tratamentos/salvar_antes_depois/$tratamento->tratamento_id")."' method='post' class='sem-borda-conteudo' enctype='multipart/form-data'>
					        								
					        								<div id='preview-antes-modificacao$foto->id'>
					        									<img src='". base_url("uploads/antes_depois/sem-imagem.jpeg") ."'>
					        								</div>

					        								<label class='btn btn-primary btn-foto' for='foto-antes-modificacao$foto->id' onClick='uploadImagemAntesModificacao($foto->id, this);'>
					        									<i class='glyphicon glyphicon-camera'></i> Foto do Antes
					        								</label>
															<input id='foto-antes-modificacao$foto->id' type='file' style='display: none;' name='foto_antes_modificacao'>
															<input type='hidden' name='id' value='$foto->id'>

															<button type='submit' class='btn btn-success btn-foto' id='salvar_antes_foto$foto->id' style='display: none;'>
																<i class='glyphicon glyphicon-ok'></i> Salvar
															</button>
														</form>
				        								";
			        							}
			        							echo "</td>";

			        							echo "<td class='conteudo-imagem-historico'>";
			        								if($foto->data_depois){
				        								echo "
					        								<div class='box-depois'>".bd2data($foto->data_depois)."
					        								<i class='glyphicon glyphicon-remove pull-right remover-img' style='color:#b92c28;cursor:pointer;margin-right:2px;font-size:18px;' hist-id='$foto->id' img-remover='depois'></i>
					        								</div>
					        								<img src='". base_url("uploads/antes_depois/$foto->depois") ."'>
					        								
				        								";
				        							}else{
				        								echo "
				        								<form action='".base_url("tratamentos/salvar_antes_depois/$tratamento->tratamento_id")."' method='post' class='sem-borda-conteudo' enctype='multipart/form-data'>
					        								
					        								<div id='preview-depois-modificacao$foto->id'>
					        									<img src='". base_url("uploads/antes_depois/sem-imagem.jpeg") ."'>
					        								</div>

					        								<label class='btn btn-primary btn-foto' for='foto-depois-modificacao$foto->id' onClick='uploadImagemDepoisModificacao($foto->id, this);'>
					        									<i class='glyphicon glyphicon-camera'></i> Foto do Depois
					        								</label>
															<input id='foto-depois-modificacao$foto->id' type='file' style='display: none;' name='foto_depois_modificacao'>
															<input type='hidden' name='id' value='$foto->id'>

															<button type='submit' class='btn btn-success btn-foto' id='salvar_depois_foto$foto->id' style='display: none;'>
																<i class='glyphicon glyphicon-ok'></i> Salvar
															</button>
														</form>
				        								";
				        							}
			        							echo "</td>
			        						</tr>
			        						";
			        					}
			        				}else{
			        					echo "
			        					<tr>
			        						<td colspan='2'>Nenhum registro foi encontrado.</td>
			        					</tr>
			        					";
			        				}

			        			?>
			        		</tbody>
			        	</table>

			        </div>
			    </div>
			</div>

		<?php endif; ?>

		<!-- View de Peso e Medidas -->
		<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_peso_medidas', 'Peso e Medidas')): ?>
			<h4>Peso e Medidas</h4>

			<form action="<?php echo base_url("tratamentos/salvar_peso_medidas/$tratamento->tratamento_id"); ?>" method="post" class="sem-borda-conteudo">
				<?php echo $this->load->view("tratamentos/peso_medidas"); ?>
			</form>
		<?php endif; ?>

		<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_evolucao', 'Visualizar evolução') ): ?>

		<h4>Evolução</h4>

		<div class="scroll">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th width="100" class="text-center">Data</th>
						<th width="20" class="text-center">Sessão</th>
						<?php if( $tratamento->categoria_tratamento_id < 5 && $tratamento->categoria_tratamento_id != 3 && $this->auth_library->check_permission('tratamentos', 'tratamentos', 'editar_procedimento', 'Editar Procedimentos') ): ?>
						<th width="250">Procedimentos</th>
						<?php endif; ?>

						<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_descricao_evolucao', 'Visualizar Descrição da Evolução')): ?>
						<th>Descrição</th>
						<?php endif; ?>

						<th>Profissional</th>
						<th class="text-center">Status</th>
						<th class="text-center">Obs. Agenda</th>

						<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'voltar_condicao', 'Voltar Condição') ): ?>
						<th><center>Voltar Condição</center></th>
						<?php endif; ?>

						<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'transferir_evolucao', 'Transferir Evolução de Tratamentos') ): ?>
							<th width="1" class="text-center">Transferir</th>
						<?php endif; ?>

						<?php if($tratamento->convenio_id != 1): ?>
						<th width="100" class="text-center" colspan="2">Data TISS</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $evolucao as $evol ): ?>
						<tr id='linha-evolucao-<?= $evol->id; ?>'>
							<td style='display:none;'>
			                    <input type='hidden' class='tiss-id-evolucao' value='<?= $evol->id; ?>'>
			                </td>
							<td class="text-center">
								<?php if($evol->id_agenda): ?>
									<a href="<?php echo base_url() . 'agenda/index/'.$evol->clinica_id.'/P/'.$evol->id_profissional_agenda.'/'.$evol->data_inicio.'/'.$evol->id_agenda; ?>" data-toggle="popover" data-placement="right" data-content="Visualizar na Agenda" class="spanpop glyphicon glyphicon-calendar" style='margin-left: 0 !important; position:relative; left: -1px;'>
									</a>
								<?php endif; ?> 
								<?php echo bd2data($evol->data); ?> 
								<?php if($evol->hora_inicio && $evol->hora_fim): ?>
									- <?php echo $evol->hora_inicio; ?>h até  <?php echo $evol->hora_fim; ?>h
								<?php endif; ?>
							</td>
							<td class="text-center">
							<?php echo $evol->sessao; ?>
							<?php if($evol->tipo_agendamento_id == 17){echo '<br><span style="font-size:11px; color: #777">(Retorno)</span>';} ?>
							</td>
							<?php if( $tratamento->categoria_tratamento_id < 5 && $tratamento->categoria_tratamento_id != 3 && $this->auth_library->check_permission('tratamentos', 'tratamentos', 'editar_procedimento', 'Editar Procedimentos') ): ?>
							<td class="procedimento-descricao-tabela" title="<?php echo $evol->procedimento_descricao; ?>">
								<span class="procedimento-evolucao-descricao" data-id-evolucao="<?php echo $evol->id ?>"><?php echo (strlen($evol->procedimento_descricao)>60)? substr($evol->procedimento_descricao, 0,60).'...': substr($evol->procedimento_descricao, 0,60); ?></span>
								<?php if($evol->status == 'F' || $evol->status == 'NF' || $evol->status == ''): ?>
								<span class="editar-procedimento-evolucao" data-id-evolucao="<?php echo $evol->id ?>">
									<i class="glyphicon glyphicon-pencil"></i>
								</span>
								<span class="salvar-editar-procedimento-evolucao" data-id-evolucao="<?php echo $evol->id ?>">
									<i class="glyphicon glyphicon-ok"></i>
								</span>
								<span class="select-procedimentos" data-id-evolucao="<?php echo $evol->id ?>">
									<select name="procedimentos[]" id="select-procedimento-evoluca" class="form-control procedimentos-evolucao select-procedimento-evolucao" multiple="multiple" data-live-search='true' data-id-evolucao="<?php echo $evol->id ?>">
							  		</select>
								</span>
								<?php endif; ?>
							</td>
							<?php endif; ?>

							<?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_descricao_evolucao', 'Visualizar Descrição da Evolução')): ?>
							<td>
		                        <?php echo $evol->descricao; ?> <br>
		                        Obs.: <br>
		                        <?php echo $evol->observacao; ?>

		                        <?php if($tratamento->categoria_tratamento_id == 5 && isset($movimentacao[$evol->id])): ?>
									<table class="table table-bordered">
										<tr>
											<th>Procedimento</th>
											<th width="60">Sessões</th>
										</tr>
										<?php foreach($movimentacao[$evol->id] as $mov): ?>
											<tr>
												<td><?php echo $mov->codigo . ' - ' . $mov->procedimento; ?></td>
												<td><center><?php echo $mov->sessoes_utilizadas; ?></center></td>
											</tr>
										<?php endforeach; ?>
									</table>
								<?php endif; ?>

		                    </td>
		                	<?php endif; ?>

							<td><?php echo $evol->profissional . ' - ' . $evol->tipo_registro . ': ' . $evol->crefito; ?></td>
							<td class="text-center">
								<?php if($evol->status == 'F'): ?>
									Finalizado
								<?php elseif($evol->status == 'NF'): ?>
									Não Compareceu
								<?php elseif($evol->status == 'NC'): ?>
									Não Compareceu Sem Descontar
								<?php elseif($evol->status == 'FJ'): ?>
									Falta Justificada
								<?php elseif($evol->status == 'DP'): ?>
									Desmarcado p/ Profissional
								<?php else: ?>
									-
								<?php endif; ?>

								<?php if($evol->data_reposicao): ?>
								<br>
								<span style="color:#777; font-size:11px">(Recuperação de <?= $evol->data_reposicao ?>)</span>
								<?php endif ?>
							</td>
							<td width="20">
								<center>
									<?php if($evol->observacao_agenda != ''): ?>
										<?php echo "<span title='".$evol->observacao_agenda."'>".mb_substr($evol->observacao_agenda, 0,20); if(strlen($evol->observacao_agenda) > 20){echo "...";} echo "</span>"; ?>
									<?php endif ?>
								</center>
							</td>

							<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'voltar_condicao', 'Voltar Condição') ): ?>
							<td width="20">
								<center>
									<?php if($evol->status != ''): ?>

										<a href="<?php echo base_url() . 'tratamentos/voltar_condicao/' . $evol->id . '/' . $evol->agenda_id; ?>" class="btn btn-warning remover-evolucao" title="Voltar Condição"><i style="color:#fff;" class="glyphicon glyphicon-repeat"></i></a>
									<?php else: ?>
										-
									<?php endif ?>
								</center>
							</td>
							<?php endif; ?>
							<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'transferir_evolucao', 'Transferir Evolução de Tratamentos') ): ?>
							<td class="text-center">
								<?php if($evol->status != "" and $evol->categoria_tratamento_id != 5){ ?>
									<span class="btn btn-warning" onClick="buscarEvolucao(<?php echo "$evol->tratamento_id, $evol->id"; ?>);">
										<i class="glyphicon glyphicon-transfer"></i> Transferir
									</span>
								<?php }else{ ?>
									<span class="btn btn-warning" disabled>
										<i class="glyphicon glyphicon-transfer"></i> Transferir
									</span>
								<?php } ?>
							</td>
							<?php endif; ?>

							<?php if($tratamento->convenio_id != 1): ?>
							<td class='col-icone' style='border-right: 0'>
			                    <i class="glyphicon glyphicon-pencil tiss-editar-evolucao" style='color: #0F4E77' title='Editar Data da Evolução'></i>
			                </td>
							<td class='col-data' style='border-left: 0'><?= bd2data($evol->data_tiss); ?> </td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>

		<br>

		<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'detalhes_lancamento', 'Detalhes do Lançamento Caixa') ): ?>
			<?php if($registro): ?>
			
			<h4>Lançamento Caixa</h4>

			<?php echo $this->load->view('financeiro/detalhes_lancamento'); ?>

			<?php endif; ?>
		<?php endif; ?>
		
		<?php if($historico_evolucao){ ?>

		<br>

		<h4>Histórico de Transferência das Evoluções</h4>

		<div class="panel panel-default">
	        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#historicoEvolucao">
	            <h4 class="panel-title">
	                <a data-toggle="collapse" data-parent="#accordion" href="#historicoEvolucao">
	                    <i class='glyphicon glyphicon-filter'></i> Histórico de Transferência da Evolução
	                </a>
	            </h4>
	        </div>

	        <div id="historicoEvolucao" class="panel-collapse collapse">
	            <div class="panel-body">
					<b>Filtrar Histórico: </b>
					<input type='text' placeholder='Filtrar por...' class='form-control' style='width:200px !important;' onkeyup="filtrarTabela(this.value, 'historicoEvolucao');">

					<br>

	                <table class="table table-bordered" id="listaDeHistoricoEvolucao">
	                    <thead>
	                        <th width="1%" class="text-center">Matricula</th>
	                        <th width="1%" class="text-center">Data</th>
	                        <th width="20">Sessão</th>
	                        <th>Descrição</th>
	                        <th>Profissional</th>
	                        <th width="1%" class="text-center">Status</th>
	                        <th width="1%" class="text-center">Transferido</th>
	                    </thead>
	                    <tbody id="informacaoHistoricoEvolucao">
	                        <?php foreach ($historico_evolucao as $key => $evol) { ?>
	                            <tr>
	                                <td class="text-center"><?php echo $evol->num_guia; ?></td>
	                                <td class="text-center"><?php echo bd2data($evol->data); ?></td>
	                                <td class="text-center"><?php echo $evol->sessao; ?></td>
	                                <td>
	                                    <?php
	                                    
	                                        if($evol->descricao){
	                                            echo $evol->descricao;
	                                        }

	                                        if($evol->observacao){
	                                            echo "<br> Obs.: <br> $evol->observacao";
	                                        }

	                                        if($evol->descricao == "" and $evol->observacao == ""){
	                                            echo "Obs.:";
	                                        }

	                                    ?>
	                                </td>
	                                <td>
	                                    <?php 

	                                        if($evol->descricao == "Transferido"){
	                                            echo "-";
	                                        }else{
	                                            echo $evol->profissional . ' - ' . $evol->tipo_registro . ': ' . $evol->crefito;
	                                        }

	                                    ?>
	                                </td>
	                                <td>
	                                    <?php if($evol->status == 'F'): ?>
	                                        Finalizado
	                                    <?php elseif($evol->status == 'NF'): ?>
	                                        Não Compareceu
	                                    <?php elseif($evol->status == 'NC'): ?>
	                                        Não Compareceu Sem Descontar
	                                    <?php elseif($evol->status == 'FJ'): ?>
	                                        Falta Justificada
	                                    <?php elseif($evol->status == 'DP'): ?>
	                                        Desmarcado p/ Profissional
	                                    <?php else: ?>
	                                        -
	                                    <?php endif; ?>
	                                </td>
	                                <td class="text-center">
	                                	<a href="<?php echo base_url() . "tratamentos/visualizar/" . $evol->transferido; ?>" class="btn btn-warning" target="_blank">
	                                		<i class="glyphicon glyphicon-search" style="color: #ffffff;"></i> Guia
	                                	</a>
	                                </td>
	                            </tr>
	                        <?php } ?>
	                    </tbody>
	                    <tfoot id="resultadoHistoricoEvolucao" style="display: none;">
	                        <tr>
	                            <td colspan="6">Nenhum registro encontrado.</td>
	                        </tr>
	                    </tfoot>
	                </table>
	            </div>
	        </div>
	    </div>

		<?php } ?>

		<br>

		<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'historico_edicao_tratamento', 'Histórico de Edição do Tratamento') ): ?>
			<?php if($historico_edicao): ?>
			
			<h4>Histórico de Edição do Tratamento</h4>

			<?php echo $this->load->view('historico_edicao'); ?>

			<?php endif; ?>
		<?php endif; ?>

		<?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'historico_voltar_condicao', 'Histórico de Voltar Condição') ): ?>
			<?php if($historico_voltar_condicao): ?>
				<h4>Histórico de Voltar Condição</h4>
				<?php echo $this->load->view('historico_voltar_condicao'); ?>
			<?php endif ?>
		<?php endif ?>


	    <?php if($historico_unificar_paciente): ?>
		
			<h4>Histórico de Cadastro Unificado</h4>

			<?php echo $this->load->view('historico_cadastro_unificado'); ?>
		<?php endif; ?>
	</div>

	<!-- Modal -->
	<div id="modalTransferir" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">
	    
	    <form action="<?php echo base_url() . "tratamentos/transferir_evolucao" ?>" method="post" class="sem-borda-conteudo">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title"><i class="glyphicon glyphicon-transfer"></i> Transferir Evolução</h4>
		      </div>
		      <div class="modal-body">

		      	<span id="informativoEvolucao" style="display: none;">
			      	<h5 style="margin: 0px;">Evolução a ser Transferida:</h5>

			      	<table class="table table-bordered table-condensed">
			      		<thead>
			      			<tr>
			      				<th width="1%">Matricula</th>
								<th width="1%" class="text-center">Data</th>
								<th width="1%">Sessão</th>
								<th width="1">Descrição</th>
								<th width="1">Profissional</th>
								<th width="1%">Status</th>
			      			</tr>
			      		</thead>
			      		<tbody id="informacaoAtualEvolucao"></tbody>
			      	</table>
		      	</span>

		      	<hr style="box-shadow: 0px 0px 7px 2px #e0e0e0; border: transparent;">

		      	<h5 style="margin: 0px;">
		      		Transferir a Evolução para o Tratamento
		      		<b class="bg-warning" style="font-size: 10px; text-transform: uppercase; padding: 3px;">(Escolha um Tratamento)</b>:

		     		<input type="text" class="form-control pull-right" style="width: 230px; height: 30px; margin-top: -10px; font-size: 12px;" placeholder="Filtrar Tratamento" onkeyup="filtrarTabela(this.value, 'transferirEvolucaoTratamento');">

		      	</h5>

		      	<table class="table table-bordered table-condensed" id="listaDeTratamento">
		      		<thead>
		      			<tr>
		      				<th width="1%" class="text-center">Data de Cadastro</th>
		      				<th width="1%">Convênio</th>
							<th>Especialidade</th>
							<th>Guia</th>
							<th>Profissional</th>
							<th width="1%">Sessão</th>
		      			</tr>
		      		</thead>
		      		<tbody id="informacaoTratamento"></tbody>
		      		<tfoot id="resultadoTratamento" style="display: none;">
		      			<tr>
		      				<td colspan="6">Nenhum registro encontrado.</td>
		      			</tr>
		      		</tfoot>
		      	</table>

		      	<!-- CONTROLADORES -->
		      	<input type="hidden" name="tratamento_atual" id="tratamento_atual">
		      	<input type="hidden" name="evolucao_escolhido" id="evolucao_escolhido">
		      	<input type="hidden" name="tratamento_escolhido" id="tratamento_escolhido">

		      </div>
		      <div class="modal-footer" style="padding: 5px;">
		        <button type="button" class="btn btn-danger" data-dismiss="modal">
		        	<i class="glyphicon glyphicon-remove"></i> Cancelar
		    	</button>

		    	<button type="submit" class="btn btn-success" id="btnTransferir" disabled="true">
		        	<i class="glyphicon glyphicon-ok"></i> Transferir
		    	</button>
		      </div>
		    </div>
		</form>

	  </div>
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
<?php $this->load->view('modal_imprimir'); ?>
