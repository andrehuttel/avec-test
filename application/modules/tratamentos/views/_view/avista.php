<tr>
	<td>
		<small>Sessões:</small><br>
		<?php echo $tratamento->sessao_ativa;?>/<?php echo $tratamento->sessoes_totais;?>
	</td>
	<?php if(!$this->auth_library->check_permission('tratamentos', 'tratamentos', 'nao_visualizar_valores', 'Não visualizar valores dos tratamentos') ): ?>
	<td>
		<small>Valor Total:</small><br>
		R$ <?php echo number_format($tratamento->valor,2,',','.');?>
	</td>
	<td>
		<small>Desconto Total:</small><br>
		R$ <?php echo number_format($tratamento->desconto,2,',','.');?>
	</td>
	<td>
		<small>Acréscimo:</small><br>
		R$ <?php echo number_format($tratamento->acrescimo,2,',','.');?>
	</td>
	<td>
		<small>Total:</small><br>
		R$ <?php echo number_format(($tratamento->valor - $tratamento->desconto) + $tratamento->acrescimo,2,',','.');?>
	</td>
	<?php else: ?>
	<td colspan="4"></td>
	<?php endif; ?>
</tr>
