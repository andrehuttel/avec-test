<tr>

	<?php if($tratamento->convenio_id == 1): ?>
		<td colspan="3">
			<small>Convênio:</small><br>
			<?php echo $tratamento->convenio;?>
		</td>
	<?php else: ?>
		<td colspan="2">
			<small>Convênio:</small><br>
			<?php echo $tratamento->convenio;?>
		</td>
		<td>
			<small>Plano:</small><br>
			<?php echo $tratamento->plano;?>
		</td>
	<?php endif; ?>

	<td colspan="">
		<small>No Guia:</small><br>
		<strong><?php echo $tratamento->num_guia;?></strong>
	</td>
	<td>
		<small>Sessões:</small><br>
		<?php echo $tratamento->sessao_ativa;?>/<?php echo $tratamento->sessoes_totais;?>

		<?php if($tratamento->sessoes_retorno): ?>
		- <?= $tratamento->sessoes_retorno; ?> Retorno(s)
		<?php endif; ?>
	</td>
</tr>
<tr>
	<td colspan="2">
		<small>Medico:</small><br>
		<?php echo $tratamento->medico;?>
	</td>
	<td colspan="1">
		<small>CID:</small><br>
		<?php echo $tratamento->cid;?>
	</td>
	<td colspan="2">
		<small>Profissional:</small><br>
		<?php echo $tratamento->profissional;?> - <?php echo $tratamento->tipo_registro;?>: <?php echo $tratamento->crefito;?>
	</td>
</tr>

<tr>
	<td colspan="5">
		<small>Diagnóstico:</small><br>
		<?php echo $tratamento->diagnostico;?>
	</td>
</tr>

<?php if($tratamento->categoria_tratamento_id == 4): ?>
<tr>
	<td>
		<small>Autorização:</small><br>
		<?php echo ($tratamento->autorizacao=='sim') ? 'Autorizado' : 'Não Autorizado';?>
	</td>
	<td>
		<small>Data Autorização:</small><br>
		<?php echo bd2data($tratamento->data_autorizacao);?>
	</td>
	<td colspan="2">
		<small>Senha:</small><br>
		<?php echo $tratamento->senha;?>
	</td>

	<td>
		<small>Vencimento da Autorização:</small><br>
		<?php echo bd2data($tratamento->vencimento_autorizacao);?>
	</td>
</tr>
<?php endif; ?>
