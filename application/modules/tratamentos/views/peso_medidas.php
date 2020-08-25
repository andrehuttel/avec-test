<!-- Pesos e Medidas -->
<div class="scroll">
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Adicionar - Peso e Medidas</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="padding: 0px;">
				<table class="table table-bordered" style="margin-bottom: 0px;">
					<thead>
						<tr style="background: #fbfbfb;">
							<th>Peso</th>
							<th>Cintura 01</th>
							<th>Cintura 02</th>
							<th>Abdômen 01</th>
							<th>Abdômen 02</th>
							<th>Quadril</th>
							<th>Coxa</th>
							<th>IMC</th>
						</tr>
					</thead>
					<tbody id="conteudoPesoMedidas"></tbody>
					<tfoot style="background: #fbfbfb;">
						<tr>
							<td colspan="8">
								<button type="button" class="btn btn-success pull-left" onClick="adicionarPesoMedidas(this);">
									<i class="glyphicon glyphicon-plus"></i> Adicionar Peso e Medidas
								</button>

								<button type="submit" class="btn btn-success pull-right">
									<i class="glyphicon glyphicon-ok"></i> Salvar
								</button>
							</td>
						</tr>
					</tfoot>
				</table>
			</td>
		</tr>
	</tbody>
</table>
</div>

<!-- Histórico de Medidas -->
<div class="panel-group" id="accordion" style="margin-top: 15px;">
    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#historico_peso_medidas">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <i class='glyphicon glyphicon-time'></i> Histórico de Peso e Medidas
                </a>
            </h4>
        </div>
        <div id="historico_peso_medidas" class="panel-collapse collapse">

			<div class="scroll">
			<table class="table table-bordered" style="margin: 0px;">
				<thead>
					<tr style="background: #fbfbfb;">
						<th style="text-align: center; padding: 0px;" width="20">#</th>
						<th style="text-align: center; padding: 0px;">Peso</th>
						<th style="text-align: center; padding: 0px;">Cintura 01</th>
						<th style="text-align: center; padding: 0px;">Cintura 02</th>
						<th style="text-align: center; padding: 0px;">Abdômen 01</th>
						<th style="text-align: center; padding: 0px;">Abdômen 02</th>
						<th style="text-align: center; padding: 0px;">Quadril</th>
						<th style="text-align: center; padding: 0px;">Coxa</th>
						<th style="text-align: center; padding: 0px;">IMC</th>
					</tr>
				</thead>
				<tbody>
					<?php 

					if($tratamento->medidas){
						foreach($tratamento->medidas as $medida){ 
							echo "
							<tr>
								<td style='padding: 0px; text-align: center;'>
									<i class='glyphicon glyphicon glyphicon-info-sign' data-toggle='popover' data-trigger='hover' data-content='Data de registro: ".bd2datahora($medida->data)." Usuário: $medida->usuario'>
								</td>
								<td style='padding: 0px; text-align: center;'>$medida->peso</td>
								<td style='padding: 0px; text-align: center;'>$medida->cintura01</td>
								<td style='padding: 0px; text-align: center;'>$medida->cintura02</td>
								<td style='padding: 0px; text-align: center;'>$medida->abdomen01</td>
								<td style='padding: 0px; text-align: center;'>$medida->abdomen02</td>
								<td style='padding: 0px; text-align: center;'>$medida->quadril</td>
								<td style='padding: 0px; text-align: center;'>$medida->coxa</td>
								<td style='padding: 0px; text-align: center;'>$medida->imc</td>
							</tr>";
						}
					}else{
						echo "
						<tr>
							<td colspan='9' style='text-align: center; padding: 3px;'>Nenhuma informação em histórico.</td>
						</tr>
						";
					}

					?>
				</tbody>
			</table>
			</div>

        </div>
    </div>
</div>