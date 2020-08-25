<div id='container-formas-pagamento' style='display:none'>
<div class="col-md-12">
	<?php echo form_label('Opção de mais uma forma de pagamento?', 'entradas'); ?>
	<?php echo br(); ?>
	<?php echo form_dropdown('entradas', $entradas, '', 'class="form-control entradas"'); ?>
</div>

<?php
echo br();

echo '<div class="cont-form grid-entradas" style="display:none">'; 
	echo '<div class="grid-entradas" style="display:none">'; 
		echo '<div class="row-fluid">';
			echo '<div class="col-md-12">';
				echo br(2);
				echo form_button($adicionar_entrada);
			echo '</div>';
		echo '</div>';
	echo '</div>';

	echo "<div id='entradas' class='grid-entradas' style='display:none;'>";
		echo "<div id='entrada' style='height:80px;'>"; 
			echo "<br style='clear:both'>";
			
			echo '<div class="row-fluid">';
				echo '<div class="col-md-3">';
					echo form_label('Tipo de Pagamento: <span class="required">*</span>', 'tipo_pagamento_entrada');
					echo br();
					echo form_dropdown('tipo_pagamento_entrada[]', $tipos_pagamento, '', 'class="form-control tipo_pagamento_entradas"' );
				echo '</div>';

				echo '<div class="col-md-3">';
					echo form_label('Valor: <span class="required">*</span>', 'valor_entrada');
					echo br();
					echo form_input($valor_entrada);
				echo '</div>';

				echo '<div class="col-md-2">';
					echo form_label('Vezes: <span class="required">*</span>', 'venc_entrada');
					echo br();
					echo form_input($parcelas_entrada);
				echo '</div>';

				echo '<div class="col-md-3">';
					echo form_label('Vencimento: <span class="required">*</span>', 'venc_entrada');
					echo br();
					echo form_input($venc_entrada);
				echo '</div>';

				echo '<div class="col-md-1">';
					echo br();
					echo form_button($remover_entrada);
				echo '</div>';
			echo '</div>';
			?>

			<!--Cartão-->
			<div class="row-fluid cont-cartao-entrada" style="display: none;">
				<div class="col-md-12">
					<h4><i class="glyphicon glyphicon-credit-card" style="position:relative; top:2px;"></i> Selecione um cartão abaixo</h4> 
						
					<?php echo form_input($cartao_padrao_entrada); ?>

					<div class='list-cartoes-entrada'></div>

					<br style='clear:both'>

					<div class='dados-cartao-entrada'></div>

					<br style='clear:both'>
				</div>
			</div>

			<?php
			echo br();
		echo "</div>";
	echo "</div>"; 

	echo '<div class="row-fluid">';
		echo '<div class="col-md-12">';
			echo '<hr style="border: 2px #333 solid;">';
		echo "</div>"; 
	echo "</div>"; 
echo '</div>';
?>
</div>