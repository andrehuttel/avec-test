<a href="<?php echo base_url() . "tratamentos/visualizar/$tratamento_id"; ?>" class="btn btn-default" style="margin-bottom: 15px;">
	<i class="glyphicon glyphicon-chevron-left"></i> Voltar
</a>

<div class="borda-conteudo">

	<h4>Área de Escolha de Documento(s):</h4>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalUploadDocumento" style="margin-bottom: 20px;">
		<i class="glyphicon glyphicon-paperclip"></i> Anexar Documento
	</button>

	<h4>Documentos Registrados <b>(<?php echo (isset($anexos) ? count($anexos) : 0); ?>)</b></h4>
	<table class="table table-bordered">
		<?php if($anexos): ?>
			<?php foreach($anexos as $anexo): ?>
				<tr>
					<td>
						
						<span class="pull-right btn btn-danger btn-xs" onClick="removerAnexoTratamento(<?php echo $anexo->id; ?>);">
		  					<i class="glyphicon glyphicon-trash"></i> Remover
		  				</span>

		  				<h5 style="margin: 0px!important; margin-top: -10px!important;">
		  					<?php echo $anexo->nome_original; ?>
		  				</h5>

		  				<div>
		      				<div class="col-md-6" style="border: 0px; padding: 0px;">
		      					<a href="<?php echo base_url() . 'uploads/anexos/' . $anexo->nome; ?>" target="_blank">
		      						<?php

		      						list($nome, $ext) = explode(".", $anexo->nome_original);
		      						$ext = strtolower($ext);

		      						if($ext == "png" or $ext == "gif" or $ext == "jpg" or $ext == "jpeg" or $ext == "svg"){

		      						?>
		      						<img src="<?php echo base_url() . 'uploads/anexos/' . $anexo->nome; ?>" width="100%">
		      						<?php }else{ ?>
		      						<img src="<?php echo base_url() . 'uploads/anexo.png'; ?>" width="90">
		      						<?php } ?>
		      					</a>
		      				</div>
		      				
		      				<div class="col-md-6" style="border: 0px;">
		      					<h4 style="font-weight: bold;">Descrição</h4>
		      					<p><?php 
									
									if($anexo->descricao == ""){
										echo "Nenhuma descrição foi inserida.";
									}else{
										echo $anexo->descricao;
									}

		      						list($data, $hora) = explode(" ", $anexo->modificacao);
		      						list($ano, $mes, $dia) = explode("-", $data);
		      						
		      						echo "<br><br> Modificação: $dia/$mes/$ano $hora";

		      					?></p>
		      				</div>
		  				</div>

					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td>Não há anexos disponiveis!</td>
			</tr>
		<?php endif; ?>
	</table>
</div>


<!-- Modal -->
<div id="modalUploadDocumento" class="modal fade" role="dialog">
  <div class="modal-dialog">

  	<form method="post" action="<?php echo base_url(); ?>tratamentos/anexar/<?php echo $tratamento_id; ?>" id="anexar-prontuario" enctype="multipart/form-data" accept-charset="utf-8" class="sem-borda-conteudo">
	    <div class="modal-content">
	      <div class="modal-body">
	      	
	      	<div>
		      	<label class="btn btn-primary" for="anexos">
		      		<i class="glyphicon glyphicon-search"></i> Escolher um Documento
		      	</label>
	      	</div>

	      	<input type="file" name="anexos[]" id="anexos" multiple="multiple" style="display:none;">

	      	<div id="preview-img" style="max-height: 460px; overflow-y: auto; margin-top: 15px; display: none;"></div>

	      	<label for="descricao">Descrição:</label>
	      	<textarea id="descricao" name="descricao" class="form-control" maxlength="255"></textarea>

	      </div>
	      <div class="modal-footer" style="padding: 5px;">
	        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Fechar</button>
	        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-upload"></i> Upload</button>
	      </div>
	    </div>
    </form>
  </div>
</div>