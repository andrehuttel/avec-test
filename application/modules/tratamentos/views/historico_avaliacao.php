<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#historico_avaliacao">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <i class='glyphicon glyphicon-time'></i> Histórico de Avaliações do Paciente
                </a>
            </h4>
        </div>
        <div id="historico_avaliacao" class="panel-collapse collapse">
        	<table class='table table-bordered'>
				<thead>
					<tr>
						<th class="text-center">Data Cad. Tratamento</th>
						<th>Especialidade</th>
						<th>Guia</th>
						<th class="text-center">Profissional</th>
						<th class="text-center">Avaliação</th>
					</tr>
				</thead>
				<tbody>
				<?php if($historico_avaliacao): ?>
					<?php foreach($historico_avaliacao as $h_avaliacao): ?>
						<tr>
							<td width="50"><?php echo bd2data($h_avaliacao->data); ?></td>
							<td><?php echo $h_avaliacao->especialidade; ?></td>
							<td><?php echo $h_avaliacao->num_guia; ?></td>
							<td width="200"><?php echo $h_avaliacao->profissional; ?></td>
							<?php if (isset($h_avaliacao->visualiza)): ?>
							<td width="100">
								<a href="<?php echo base_url() . 'tratamentos/avaliacao_visualizar/'.$h_avaliacao->tratamento_id; ?>" class="btn btn-info" target='_blank'>
									<i class="glyphicon glyphicon-search" style='color: #fff'></i> Visualizar
								</a>
							<?php else: ?>
							<td width="300">
								<div class='alert alert-warning fundo-alert-atendimento-paciente'>
									<i class='glyphicon glyphicon-exclamation-sign'></i> 
									Você não possui permissão para visualizar essa especialidade.
								</div>
							<?php endif ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="4">Não houveram avaliações anteriores.</td>
					</tr>
				<?php endif ?>
				</tbody>
			</table>
        </div>
    </div>
</div>