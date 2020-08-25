<!--Cartão-->
<div class="row-fluid" id="cont-cartao" style="display: none;">
	<div class="col-md-12">
		<h4><i class="glyphicon glyphicon-credit-card" style="position:relative; top:2px;"></i> Selecione um cartão abaixo</h4> 
			
		<?php echo form_input($cartao_padrao); ?>

		<div id='list-cartoes'></div>

		<br style='clear:both'>

		<div id='dados-cartao'></div>

		<br style='clear:both'>
	</div>

	<div class="col-md-12">
		<?php $this->load->view('_form/agil_pay'); ?>
	</div>
</div>