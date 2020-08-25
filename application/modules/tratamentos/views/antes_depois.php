<table class="table table-bordered">
	<thead>
		<tr>
			<th colspan="2">Upload de Imagens</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="text-center" style="padding: 0px;" width="50%">

				<div id="preview-antes" class="preview-imagens" style="display: none;">
					<img src="<?php echo base_url("uploads/antes_depois/sem-imagem.jpeg"); ?>" class="thumb-image">
				</div>

				<label class="btn btn-primary btn-foto" for='foto-antes'><i class="glyphicon glyphicon-camera"></i> Foto Antes</label>
				<input id='foto-antes' type='file' style="display: none;" name="foto_antes">

			</td>
			<td class="text-center" style="padding: 0px;" width="50%">
				<div id="preview-depois" class="preview-imagens" style="display: none;">
					<img src="<?php echo base_url("uploads/antes_depois/sem-imagem.jpeg"); ?>" class="thumb-image">
				</div>

				<label class="btn btn-primary btn-foto" for='foto-depois'><i class="glyphicon glyphicon-camera"></i> Foto Depois</label>
				<input id='foto-depois' type='file' style="display: none;" name="foto_depois">

			</td>
		</tr>
		<tr id="linha-salvar-foto" style="display: none;">
			<td class="text-right" colspan="2" style="padding: 8px; background: #f7f7f7; border: 2px solid #dddddd;">
				<button type="submit" class="btn btn-success">
					<i class="glyphicon glyphicon-ok"></i> Salvar
				</button>
			</td>
		</tr>
	</tbody> 
</table>