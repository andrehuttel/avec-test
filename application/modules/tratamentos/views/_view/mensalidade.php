<tr>
	<td colspan="2">
		<small>Quantas sessões pode recuperar no mês:</small><br>
		<?php echo $tratamento->recuperar_mes; ?>
	</td>
	<td colspan="3">
		<small>Dias para recuperar uma sessão:</small><br>
		<?php echo $tratamento->dias_recuperar; ?>
	</td>
</tr>

<tr>
	<td colspan="2">
		<small>Plano:</small><br>
		<?php echo mensalidade_nome_plano($tratamento->plano_mensal); ?>
	</td>

	<?php if(!$this->auth_library->check_permission('tratamentos', 'tratamentos', 'nao_visualizar_valores', 'Não visualizar valores dos tratamentos') ): ?>
	<td colspan="">
		<small>Valor:</small><br>
		R$ <?php echo number_format($tratamento->valor,2,',','.');?>
	</td>
	<td>
		<small>Desconto:</small><br>
		R$ <?php echo number_format($tratamento->desconto,2,',','.');?>
	</td>
	<td colspan="2">
		<small>Acréscimo:</small><br>
		R$ <?php echo number_format($tratamento->acrescimo,2,',','.');?>
	</td>
	<?php else: ?>
	<td colspan="3"></td>
	<?php endif; ?>
</tr>
<tr>
	<?php if(!$this->auth_library->check_permission('tratamentos', 'tratamentos', 'nao_visualizar_valores', 'Não visualizar valores dos tratamentos') ): ?>
	<td colspan="2">
		<small>Valor da Mensalidade:</small><br>
		R$ <?php echo number_format((($tratamento->valor + $tratamento->acrescimo) - $tratamento->desconto),2,',','.');?>
	</td>
	<td colspan="2">
		<small>Total:</small><br>
		R$ <?php echo number_format(($tratamento->plano_mensal * (($tratamento->valor + $tratamento->acrescimo) - $tratamento->desconto)),2,',','.');?>
	</td>
	<?php else: ?>
	<td colspan="4"></td>
	<?php endif; ?>
	<td>
		<small>Sessões:</small><br>
		<?php echo $tratamento->sessao_ativa;?>/<?php echo $tratamento->sessoes_totais;?>

		<?php if($tratamento->sessoes_retorno): ?>
		- <?= $tratamento->sessoes_retorno; ?> Retorno(s)
		<?php endif; ?>
	</td>
</tr>
