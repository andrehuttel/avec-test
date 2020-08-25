<div class="content">
	<div class="modal-header">
	    <button type="button" class="close" data-target=".modal-tratamento-imagem"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	    <h4 class="modal-title" id="myModalLabel">Selecionar Região do Corpo</h4>
  	</div>

  	<div class="modal-body">
	  	<form action="<?= base_url(); ?>tratamentos/marcacao_salvar/<?= $id_tratamento; ?>" class="form-salvar-imagem sem-borda-conteudo">
		  	<div class="row-fluid">
				<div class="span4">
		  			<?= br(3); ?>

		  			<input type="hidden" name="id_tratamento" class="id_tratamento" value="<?= $id_tratamento; ?>">

					<select class="form-control selecao-corpo" name="selecao_corpo">
						<option value="corpo-frente">Corpo Frente</option>
						<option value="corpo-costas">Corpo Costas</option>
						<option value="rosto">Rosto</option>
						<option value="inteiro-frente">Corpo Inteiro Frente</option>
						<option value="inteiro-costas">Corpo Inteiro Costas</option>
					</select>

					<br>

					<label>Observação</label>
					<textarea name="observacao" rows="6" class="form-control observacao"></textarea>
				</div>

				<div class="span8">
					<div class="main-corpo">
						<div class="corpo-selecao">
							<div class="selecao-frente">
								<?php for($x=0;$x<40;$x++):?>
									<?php for($y=0;$y<19;$y++):?>
										<label class="inf" for="<?='x'.$x.'y'.$y?>"></label>
									<?php endfor;?>
								<?php endfor;?>	
							</div>

							<div class="selecao-imagem">
								<img src="<?= base_url()?>assets/img/marcacao/corpo-frente.png">
							</div>

							<div class="selecao-atras">
								<?php for($x=0;$x<40;$x++):?>
									<?php for($y=0;$y<19;$y++):?>
										<input id="<?='x'.$x.'y'.$y?>" name="selecao[<?=$x?>][<?=$y?>]" value="<?='x'.$x.'y'.$y?>" type="checkbox">
										<label class="sup"></label>
									<?php endfor;?>
								<?php endfor;?>
							</div>
						</div>

						<br style="clear:both;">
					</div>
				</div>

		  	</div>

		  	<button type="button" class="btn btn-primary pull-right salvar-imagem">Salvar</button>
			<button type="button" data-target=".modal-tratamento-imagem" class="btn btn-default">Fechar</button>
	  	</form>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/marcacao.css">