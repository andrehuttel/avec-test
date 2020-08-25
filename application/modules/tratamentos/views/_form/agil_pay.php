<div class="checkout" id='agil-pay-content' style='display: none'>
	<?= form_input($agilpay_vendedor_id); ?>
	<?= form_input($agilpay_valor_total); ?>
	<?= form_input($agilpay_parcelas); ?>

	<div class='cartao-valores'>
		<span style='font-size: 11px;color: #999;'>Confirme a condição de pagamento abaixo:</span><br>
		<strong>Valor Total:</strong> R$ <span class='zp-cartao-valor-total'>0,00</span><br>
		<strong>Parcelas:</strong> <span class='zp-cartao-parcelas'>0</span>x
	</div>
	
	<div class="credit-card-box">
		<div class="flip">
			<div class="front">
				<div class="chip"></div>

				<div class="logo">
					<img src='<?= base_url(); ?>assets/img/agil_pay_logo.png'>
				</div>

				<div class="number"></div>

				<div class="card-holder">
					<label>Nome do Titular</label>
					<div></div>
				</div>

				<div class="card-expiration-date">
					<label>Validade</label>
					<div></div>
				</div>
			</div>

			<div class="back">
				<div class="strip"></div>

				<div class="logo">
					<img src='<?= base_url(); ?>assets/img/agil_pay_logo.png'>
				</div>

				<div class="ccv">
					<label>CCV</label>
					<div></div>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="col-md-4">
			<label for="card-number">Número do Cartão</label>
			<br style='clear: both'>

			<?= form_input($cartao_num1); ?>
			<?= form_input($cartao_num2); ?>
			<?= form_input($cartao_num3); ?>
			<?= form_input($cartao_num4); ?>
		</div>

		<div class="col-md-4">
			<label for="card-holder">Nome do Titular</label>
			<br style='clear: both'>

			<?= form_input($cartao_nome_titular); ?>
		</div>

		<div class="col-md-2">
			<label for="card-expiration-month">Validade</label>
			<br style='clear: both'>

			<div class="select">
				<?= form_dropdown('cartao_validade_mes', $cartao_validade_mes, set_value('cartao_validade_mes'), 'class="select-vencimento" id="card-expiration-month"'); ?>
			</div>

			<div class="select">
				<?= form_dropdown('cartao_validade_ano', $cartao_validade_ano, set_value('cartao_validade_ano'), 'class="select-vencimento" id="card-expiration-year"'); ?>
			</div>
		</div>

		<div class="col-md-2">
			<label for="card-ccv">CCV</label>
			<br style='clear: both'>
			<?= form_input($cartao_ccv); ?>
		</div>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/font-inconsolata.css">
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/font-open-sans.css">
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/agil_pay.css">

<?php if($this->router->class == 'tratamentos' && $this->router->method == 'viewForm'): ?>
<script src="<?= base_url(); ?>assets/js/financeiro/agil_pay.js"></script>
<?php endif; ?>