<!-- Modal de cadastro de paciente -->
<div class="modal fade bs-example-modal-sm" id="modal-paciente" tabindex="-2" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Novo Paciente</h4>
      </div>
      <div class="modal-body">

      	<form autocomplete="off" id="form-paciente-by-tratamento" class="sem-borda-conteudo">
	      	<div class="row-fluid">
	      		<div class="col-md-12">
	      			<label for="nome">Nome</label>
	        		<input type="text" name="nome" id="nome" class="form-control" required="required">
	      		</div>
	      	</div>

	      	<div class="row-fluid">
	      		<div class="col-md-12" style='max-height: 55px !important;'>
	      			<span class='aviso-telefone'></span>
	      		</div>
	      	</div>

	      	<div class="row-fluid">
	      		<div class="col-md-4 cod4">
	      			<input type="hidden" name="pais_codigo4" id="pais_codigo4" value="br">
      				<input type="hidden" name="ddi4" id="ddi4" value="55">
	      			<label for="telefone4">Telefone</label>
	        		<input type="tel" name="telefone" id="telefone4" class="form-control" required="required">
	      		</div>

	      		<div class="col-md-4">
	      			<label for="convenio">Convênio</label>
	      			<select name="convenio" class="form-control" id="convenio-rapido-tratamento" required="required">
	      				<?php foreach( $convenios_modal as $convenio ): ?>
	      				<option value="<?php echo $convenio->id; ?>"><?php echo $convenio->nome; ?></option>
	      				<?php endforeach; ?>
	      			</select>
	      		</div>

	      		<div class="col-md-4">
	      			<label for="plano">Plano</label>
	      			<select name="plano" class="form-control" id="plano">
	      				<?php foreach( $planos_modal as $plano ): ?>
	      				<option value="<?php echo $plano->id; ?>"><?php echo $plano->descricao; ?></option>
	      				<?php endforeach; ?>
	      			</select>
	      		</div>
	      	</div>
	    </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="salvar-paciente-by-tratamento">Salvar</button>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL PACIENTE -->
