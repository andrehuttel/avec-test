<tr>
	<td>
		<small>Sessões:</small><br>
		<?php echo $tratamento->sessao_ativa;?>/<?php echo $tratamento->sessoes_totais;?>

		<?php if($tratamento->sessoes_retorno): ?>
		- <?= $tratamento->sessoes_retorno; ?> Retorno(s)
		<?php endif; ?>
	</td>

	<?php if(!$this->auth_library->check_permission('tratamentos', 'tratamentos', 'nao_visualizar_valores', 'Não visualizar valores dos tratamentos') ): ?>
	<td>
		<small>Valor do Pacote:</small><br>
		R$ <?php echo number_format(($tratamento->valor*$tratamento->sessoes_totais),2,',','.');?>
	</td>
	<td>
		<small>Desconto:</small><br>
		R$ <?php echo number_format($tratamento->desconto,2,',','.');?>
	</td>
	<td>
		<small>Acréscimo:</small><br>
		R$ <?php echo number_format($tratamento->acrescimo,2,',','.');?>
	</td>
	<td>
		<small>Total:</small><br>
		R$ <?php echo number_format((($tratamento->valor*$tratamento->sessoes_totais) - $tratamento->desconto) + $tratamento->acrescimo,2,',','.');?>
	</td>
	<?php else: ?>
	<td colspan="4"></td>
	<?php endif; ?>
</tr>

<tr>
	<td colspan="2">
		<small>Limite de FJ no Período:</small><br>
		<?php echo $tratamento->limite_fj_periodo;?>
	</td>

	<td colspan="3">
		<small>Período de Bloqueio:</small><br>
		<?php if($tratamento->periodo_bloqueio == 1): ?>
			Mês Vigente

		<?php elseif($tratamento->periodo_bloqueio == 2): ?>
			Dias Corridos
		<?php endif; ?>
	</td>
</tr>

