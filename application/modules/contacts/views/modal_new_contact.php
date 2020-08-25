<div class="modal fade" id="modal_new_contact" tabindex="-1" role="dialog" aria-labelledby="">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">New Contact</h4>
      </div>
      <div class="modal-body">
      	<form autocomplete="off" id="form-new-contact" class="sem-borda-conteudo">
	      	<div class="row-fluid">
	      		<div class="col-md-4 cod4">
	      			<label for="nome">Name</label>
	        		<input type="text" name="name" id="nome" class="form-control" required="required">
	      		</div>

	      		<div class="col-md-4 cod4">
	      			<label for="cpf">CPF</label>
	        		<input type="text" name="cpf" id="cpf" class="form-control cpf" required="required">
	      		</div>

	      		<div class="col-md-4 cod4">
	      			<label for="identidade">RG</label>
	        		<input type="text" name="identidade" id="identidade" class="form-control">
	      		</div>
	      	<div>
	      	<div class="row-fluid">
	      		<div class="col-md-4 cod4">
	      			<label for="data_nascimento">Birth Date</label>
	        		<input type="text" name="data_nascimento" id="data_nascimento" class="form-control data">
	      		</div>

	      		<div class="col-md-4 cod4">
	      			<label for="telefone">Telephone</label>
	        		<input type="tel" name="telefone" id="telefone" class="form-control telefone">
	      		</div>

	      		<div class="col-md-4 cod4">
	      			<label for="celular">Cell phone</label>
	        		<input type="tel" name="celular" id="celular" class="form-control telefone">
	      		</div>
	      	</div>
	      	<div class="row-fluid">
	      		<div class="col-md-4 cod4">
	      			<label for="endereco">Address</label>
	        		<input type="text" name="endereco" id="endereco" class="form-control">
	      		</div>
	      	</div>
	    </form>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-new-contact">Save</button>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>