<div class="content">
	<div class="modal-header">
	    <button type="button" class="close" data-target=".modal-tratamento-imagem"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	    <h4 class="modal-title" id="myModalLabel">Região</h4>
  	</div>

	<div class="modal-body">
	  	<div class="row-fluid">
			<div class="span4">
	  			<?= br(3); ?>

				<h3><?php echo mb_convert_case(str_replace('-',' ',$marcacoes->imagem), MB_CASE_TITLE, 'UTF-8'); ?></h3>

				<br>

				<label>Observação:</label><br>
				<?php echo $marcacoes->observacao; ?>
				<?php $posicao = unserialize($marcacoes->posicao); ?>
			</div>

			<div class="span8">
				<div class="main-corpo <?php echo $marcacoes->imagem?>">
					<div class="corpo-selecao">
						<div class="selecao-imagem">
							<img src="<?php echo base_url()?>assets/img/marcacao/<?php echo $marcacoes->imagem?>.png">
						</div>

						<div class="selecao-atras">
							<?php for($x=0;$x<40;$x++):?>
								<?php for($y=0;$y<19;$y++):?>
									<input id="<?='x'.$x.'y'.$y?>" <?php echo isset($posicao[$x][$y]) && $posicao[$x][$y] == 'x'.$x.'y'.$y ? 'checked="checked"' : ''?> name="" value="<?='x'.$x.'y'.$y?>" type="checkbox">
									<label class="sup"></label>
								<?php endfor;?>
							<?php endfor;?>
						</div>
					</div>

					<br style="clear:both;">
				</div>
			</div>
	  	</div>

		<button type="button" data-target=".modal-tratamento-imagem" class="btn btn-default">Fechar</button>
	<div class="modal-body">
</div>

<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/marcacao.css">
<style>.main-corpo.rosto{height: 306px!important;}.main-corpo.inteiro-frente, .main-corpo.inteiro-costas{height: 525px!important;}</style>