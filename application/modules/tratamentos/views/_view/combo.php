<tr>
	<td>
		<small>Sessões:</small><br>
		<?php echo $tratamento->sessao_ativa;?>/<?php echo $tratamento->sessoes_totais;?>
	</td>
	<?php if(!$this->auth_library->check_permission('tratamentos', 'tratamentos', 'nao_visualizar_valores', 'Não visualizar valores dos tratamentos') ): ?>
	<td>
		<small>Valor do Combo:</small><br>
		R$ <?php echo number_format(($tratamento->valor),2,',','.');?>
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
		R$ <?php echo number_format(($tratamento->valor - $tratamento->desconto) + $tratamento->acrescimo,2,',','.');?>
	</td>
	<?php else: ?>
	<td colspan="4"></td>
	<?php endif; ?>
</tr>
<tr>
	<th colspan="5"><center>Procedimentos do Combo</center></th>
</tr>
<tr>
	<th colspan="3">Procedimento</th>
	<th width="250">Sessões</th>
	<th width="250"><?php if($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários')): ?>Valor<?php endif; ?></th>
</tr>
<?php foreach($procedimentos as $procedimento): ?>
	<tr>
		<td colspan="3"><?php echo $procedimento->codigo . '-' . $procedimento->procedimento; ?></td>
		<td><?php echo $procedimento->sessoes_utilizadas.'/'.$procedimento->sessoes_totais; ?></td>
		<td><?php if($this->auth_library->check_permission('procedimentos', 'procedimentos', 'exibir_valor', 'Exibir Valor do Procedimento nos Formulários')): ?>R$ <?php echo number_format($procedimento->valor,2,',','.'); ?><?php endif; ?></td>
	</tr>
<?php endforeach; ?>
