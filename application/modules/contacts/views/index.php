<br><br>
<a class="btn btn-success pull-left" id="btn-new-contact">
    <i class="glyphicon glyphicon-plus-sign"></i> New Contact
</a>
<br class='clear'><br>

<div class="panel panel-primary">
    <div class="panel-heading">Contacts</div>
	    <div class="panel-body">
			<table class="table table-bordered" id="table">
				<thead>
					<th>ID</th>
					<th>Name</th>
					<th>CPF</th>
					<th>RG</th>
					<th>Birth date</th>
					<th>Telephone</th>
					<th>Cell phone</th>
					<th>Address</th>
					<th width="20">Edit</th>
					<th width="20">Delete</th>
				</thead>

				<?php if( $contacts ): ?>
					<?php foreach( $contacts as $contact ): ?>
						<tr class='hover2'>
							<td> <?php echo $contact->id; ?> </td>
							<td> <?php echo "<span title='".$contact->nome."'>".mb_substr($contact->nome, 0,30); if(strlen($contact->nome) > 30){echo "...";} echo "</span>"; ?></td>
							<td> <?php echo $contact->cpf; ?> </td>
							<td> <?php echo $contact->identidade; ?> </td>
							<td> <?php echo bd2data($contact->data_nascimento); ?> </td>
							<td> <?php echo $contact->telefone; ?> </td>
							<td> <?php echo $contact->celular; ?> </td>
							<td> <?php echo $contact->endereco; ?> </td>
							<td><center><a href="<?php //echo base_url() . 'contacts/edit/' . $contact->id; ?>" data-toggle="popover" data-placement="top" data-content="Edit Contact" class="spanpop glyphicon glyphicon-edit"></a></center></td>
							<td><center><a href="<?php echo base_url() . 'contacts/delete/' . $contact->id; ?>" class="spanpop glyphicon glyphicon-remove deletar"></a></center></td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="10">No contacts found.</td>
					</tr>
				<?php endif ;?>
			</table>
	    </div>
    </div>
</div>
<div class="breadcrumb">
  <span class="active" style='font-size: 11px;'>
	Total Contacts: <?= $count_results; ?>
  </span>
</div>
<?php $this->load->view('modal_new_contact'); ?>
<?= br(3) ?>
