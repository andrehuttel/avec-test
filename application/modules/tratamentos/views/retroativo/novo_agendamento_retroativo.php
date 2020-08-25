<input type="hidden" name="tipo" id="tipo" value="1">
<input type="hidden" name="pacientes" id="pacientes" class="pacientes" value="<?php echo $tratamento->paciente_id; ?>">
<input type="hidden" name="tratamentos" id="tratamentos" value="<?php echo $tratamento->id; ?>">
<input type="hidden" name="profissional" id="profissional" value="<?php echo $tratamento->profissional_id; ?>">


<br><br>

<div id='calendar'></div>

<br>

<a href="<?php echo base_url() . 'tratamentos/visualizar/' . $tratamento->id; ?>" class="btn btn-primary">Concluir</a>

<div class="modal fade" id="modal-agenda">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Agendamento</h4>
      </div>
      <div class="modal-body">

      	<div class="info"></div>

      	<form autocomplete="off" id="form-agenda">

	      	<div class="row-fluid">
	      		<div class="col-md-6">
	      			<label for="data_inicio">Data Inicio</label>
	        		<input type="text" name="data_inicio" id="data_inicio" class="form-control data" required="required">
	      		</div>
	      		<div class="col-md-6">
	      			<label for="hora_inicio">Hora Inicio</label>
	        		<input type="text" name="hora_inicio" id="hora_inicio" class="form-control hora" required="required">
	      		</div>
	      	</div>

	      	<div class="row-fluid">
	      		<div class="col-md-6">
	      			<label for="data_fim">Data Fim</label>
	        		<input type="text" name="data_fim" id="data_fim" class="form-control data" required="required">
	      		</div>
	      		<div class="col-md-6">
	      			<label for="hora_fim">Hora Fim</label>
	        		<input type="text" name="hora_fim" id="hora_fim" class="form-control hora" required="required">
	      		</div>
	      	</div>

	      	<div class="row-fluid" id="txt-diagnostico" style="display: none;">
	      		<div class="col-md-12">
	      			<label for="diagnostico">Diagnóstico</label>
	      			<textarea value="diagnostico" id="diagnostico" class="form-control"></textarea>
	      		</div>
	      	</div>

	      	<div class="row-fluid">
	      		<div class="col-md-12">
	      			<label for="observacao">Observação</label>
	      			<textarea value="observacao" id="observacao" class="form-control"></textarea>
	      		</div>
	      	</div>

	      	<div class="repetir">
		      	<input type="checkbox" name="repetir" id="repetir" value="1">
		      	<label for="repetir">Repetir agendamento...</label>
		    </div>

	      	<div class="repetir-agendamento" id="repetir-agendamento" style="display:none;">
		      	<div class="row-fluid">
		      		<h5>Dias da semana</h5>
		      		<div class="col-md-4">
			      		<input type="checkbox" name="dia[]" id="domingo" value="0">
			      		<label for="domingo">Domingo</label> <br>
			      		<input type="checkbox" name="dia[]" id="segunda" value="1">
			      		<label for="segunda">Segunda-Feira</label> <br>
			      		<input type="checkbox" name="dia[]" id="terca" value="2">
			      		<label for="terca">Terça-Feira</label> <br>
		      		</div>
		      		<div class="col-md-4">
			      		<input type="checkbox" name="dia[]" id="quarta" value="3">
			      		<label for="quarta">Quarta-Feira</label><br>
			      		<input type="checkbox" name="dia[]" id="quinta" value="4">
			      		<label for="quinta">Quinta-Feira</label> <br>
		      		</div>
		      		<div class="col-md-4">
			      		<input type="checkbox" name="dia[]" id="sexta" value="5">
			      		<label for="sexta">Sexta-Feira</label> <br>
			      		<input type="checkbox" name="dia[]" id="sabado" value="6">
			      		<label for="sabado">Sábado</label>
		      		</div>
		      	</div>

		      	<div class="row-fluid">
		      		<h5>Mês</h5>
		      		<div class="col-md-4">
			      		<input type="checkbox" name="mes[]" id="janeiro" value="1">
			      		<label for="janeiro">Janeiro</label> <br>
			      		<input type="checkbox" name="mes[]" id="fevereiro" value="2">
			      		<label for="fevereiro">Fevereiro</label> <br>
			      		<input type="checkbox" name="mes[]" id="marco" value="3">
			      		<label for="marco">Março</label> <br>
			      		<input type="checkbox" name="mes[]" id="abril" value="4">
			      		<label for="abril">Abril</label> <br>
		      		</div>
		      		<div class="col-md-4">
			      		<input type="checkbox" name="mes[]" id="maio" value="5">
			      		<label for="maio">Maio</label><br>
			      		<input type="checkbox" name="mes[]" id="junho" value="6">
			      		<label for="junho">Junho</label> <br>
			      		<input type="checkbox" name="mes[]" id="julho" value="7">
			      		<label for="julho">Julho</label> <br>
			      		<input type="checkbox" name="mes[]" id="agosto" value="8">
			      		<label for="agosto">Agosto</label> <br>
		      		</div>
		      		<div class="col-md-4">
			      		<input type="checkbox" name="mes[]" id="setembro" value="9">
			      		<label for="setembro">Setembro</label> <br>
			      		<input type="checkbox" name="mes[]" id="outubro" value="10">
			      		<label for="outubro">Outrubro</label> <br>
			      		<input type="checkbox" name="mes[]" id="novembro" value="11">
			      		<label for="novembro">Novembro</label> <br>
			      		<input type="checkbox" name="mes[]" id="dezembro" value="12">
			      		<label for="dezembro">Dezembro</label>
		      		</div>

		      	</div>

		      	<div class="row-fluid">
		      		<h5>Data Limite</h5>
		      		<div class="col-md-5">
		      			<input type="text" name="data_limite" id="data_limite" class="data form-control">
		      		</div>
		      	</div>

	      	</div>

	    </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="salvar-agenda">Salvar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal de cadastro de paciente -->
<div class="modal fade bs-example-modal-sm" id="modal-paciente" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Novo Paciente</h4>
      </div>
      <div class="modal-body">

      	<form autocomplete="off" id="form-paciente">

	      	<div class="row-fluid">
	      		<div class="col-md-12">
	      			<label for="nome">Nome</label>
	        		<input type="text" name="nome" id="nome" class="form-control" required="required">
	      		</div>
	      	</div>

	      	<div class="row-fluid">
	      		<div class="col-md-4">
	      			<label for="telefone">Telefone</label>
	        		<input type="text" name="telefone" id="telefone" class="form-control telefone" required="required">
	      		</div>

	      		<div class="col-md-4">
	      			<label for="convenio">Convênio</label>
	      			<select name="convenio" class="form-control" id="convenio" required="required">
	      				<?php foreach( $convenios as $convenio ): ?>
	      				<option value="<?php echo $convenio->id; ?>"><?php echo $convenio->nome; ?></option>
	      				<?php endforeach; ?>
	      			</select>
	      		</div>

	      		<div class="col-md-4">
	      			<label for="plano">Plano</label>
	      			<select name="plano" class="form-control" id="plano">
	      				<?php foreach( $planos as $plano ): ?>
	      				<option value="<?php echo $plano->id; ?>"><?php echo $plano->descricao; ?></option>
	      				<?php endforeach; ?>
	      			</select>
	      		</div>
	      	</div>

	    </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="salvar-paciente">Salvar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


