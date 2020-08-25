	<?php if($avaliacao['formulario']): ?>
	<?php if ($visualizar_avaliacao): ?>
	<?php foreach ($avaliacao['titulo'] as $key => $titulo): ?>
	<div class="panel-group" id="accordion">
	    <div class="panel panel-default">
	        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#<?=$titulo->registro?>">
	            <h4 class="panel-title">
	                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
	                    <?php
	                    	echo $titulo->titulo.' - '.bd2data($titulo->data_avaliacao);
	                    ?>
	                </a>
	            </h4>
	        </div>
	        <div id="<?=$titulo->registro?>" class="panel-collapse collapse">
	        	<table class="table" width="100%">
	       		<?php foreach($avaliacao['formulario'][$titulo->registro] as $av): ?>
				<?php if (($av->status == 1) || ($av->status == 0 && $av->resposta != '')): ?>
					<?php if($av->alinhamento != 1): ?>
						</td>
						<tr style="border-bottom: 1px #ddd solid !important;">
					<?php endif; ?>
						<?php if($av->tipo_resposta == 'row'): ?>
							<table class='table table-condensed' style='margin-bottom:0;'>
							<tr>
							<td>
							<label><?php echo $av->pergunta; ?></label>
							</td>

							<?php foreach($av->parent as $parent): ?>
								<td width='150'>
									<?php echo $parent->resposta; ?>
								</td>
							<?php endforeach; ?>

							</tr>
							</table>

						<?php elseif($av->tipo_resposta != 'image'): ?>
							<?php if(substr($av->tipo_resposta, 0,1) == 'h'): ?>
								<?php echo "<tr><td><".$av->tipo_resposta.">".$av->pergunta."<".$av->tipo_resposta."></td></tr>"; ?>
							<?php elseif($av->tipo_resposta == 'html'): ?>
								<?php echo $av->resposta; ?>
							<?php else: ?>

								<?php if($av->tipo_resposta == 'span'):?>
									<span><?php echo "<span><strong>".str_replace(array("\n", " ", "\t"), array('<br>', '&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), $av->pergunta)."</strong></span>";?></span><br>
								<?php else: ?>
									<td>
									<?php echo $av->pergunta; ?>
								<?php endif ;?>

								<?php if($av->tipo_resposta == 'eva'):?>
		                            <img src="<?php echo base_url();?>assets/img/avaliacoes/eva.jpg">
		                            <br>
		                            <?php for($i=0;$i<=10;$i++): ?>
										<div class='radio-eva'>
										<?php if($av->resposta == $i): ?>
											<input type='radio' disabled="disabled" checked='checked' value='<?=$i?>'>
										<?php else: ?>
											<input type='radio' disabled="disabled" value='<?=$i?>'>
										<?php endif; ?>
										</div>
									<?php endfor; ?>

		                        <?php else: ?>
										<strong><?php echo $av->resposta;?></strong></td>
		                        <?php endif; ?>
							<?php endif; ?>
						<?php else: ?>
							<td colspan="5">
								<?php if($av->alinhamento == 1): ?>
		                            <img src="<?php echo base_url() . 'uploads/avaliacao/' . $av->alternativas; ?>" style="max-width: 800px; float: right;">
		                        <?php else: ?>
		                        	<img src="<?php echo base_url() . 'uploads/avaliacao/' . $av->alternativas; ?>" style="max-width: 800px;">
		                        <?php endif; ?>
		                    </td>
		                    <?php foreach($av->parent as $parent): ?>
		                        <td>
		                            <span><?php echo $parent->pergunta; ?>: </span>
		                            <strong><?php echo $parent->resposta;?></strong> <br>
		                        </td>
		                    <?php endforeach; ?>
						<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			</table>
	    	</div>
		</div>
	</div>
	<?php endforeach ?>
	<?php else: ?>
		<div class='alert alert-warning fundo-alert-atendimento-paciente'>
			<i class='glyphicon glyphicon-exclamation-sign'></i> 
			Você não possui permissão para visualizar essa especialidade.
		</div>
	<?php endif ?>
	<?php else: ?>
		<div class="alert alert-info" role="alert">Avaliação do paciente não preenchida.</div>
	<?php endif; ?>