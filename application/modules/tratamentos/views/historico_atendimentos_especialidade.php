<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#historico_atendimento_especialidade">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <i class='glyphicon glyphicon-time'></i> Histórico de Atendimento de 
                    <?php

                    	echo $tratamento->especialidade;

                    	if($historico_atendimentos){
                    		echo " <b>(" . count($historico_atendimentos) . ")</b>";
                    	}

                    ?>
                </a>
            </h4>
        </div>
        <div id="historico_atendimento_especialidade" class="panel-collapse collapse">
        	<table class='table table-bordered'>
				<thead>
					<tr>
						<th class="text-center">Data</th>
						<th>Descrição</th>
						<th class="text-center">Profissional</th>
						<th class="text-center">Status</th>
					</tr>
				</thead>
				<tbody>
				<?php if($historico_atendimentos): ?>
					<?php foreach($historico_atendimentos as $atendimento): ?>
						<tr>
							<td width="50" class="text-center"><?php echo bd2data($atendimento->data); ?></td>
							<td>
								<?php if (isset($atendimento->visualiza)): ?>
									<?php echo $atendimento->descricao; ?> <br>
									<b>Obs.:</b> <?php echo $atendimento->observacao; ?>
								<?php else: ?>
									<div class='alert alert-warning fundo-alert-atendimento-paciente'>
										<i class='glyphicon glyphicon-exclamation-sign'></i> 
										Você não possui permissão para visualizar essa especialidade.
									</div>
								<?php endif ?>
							</td>
							<td width="200" class="text-center"><?php echo $atendimento->profissional; ?></td>
							<td width="150" class="text-center">
								<?php if($atendimento->status == 'F'): ?>
									Finalizado
								<?php elseif($atendimento->status == 'NF'): ?>
									Não Compareceu
								<?php elseif($atendimento->status == 'NC'): ?>
									Não Compareceu Sem Descontar
								<?php elseif($atendimento->status == 'FJ'): ?>
									Falta Justificada
								<?php else: ?>
									-
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="4">Não houve atendimentos anteriores!</td>
					</tr>
				<?php endif ?>
				</tbody>
			</table>
        </div>
    </div>
</div>