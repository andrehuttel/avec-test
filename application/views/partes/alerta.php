<?php
if($this->session->flashdata('alert')){
    $alert = $this->session->flashdata('alert');
}

//Verifica a existencia da variável de alerta, seja ela advinda de uma sessão ou passada por parâmetros no carregamento da view
if(isset($alert) && !empty($alert)): ?>
    <!-- Alerta exibido no topo da tela, contendo o alerta e seu respectivo retorno -->
    <div class='alert <?php echo $alert['return']; ?>'>
        <button type='button' class='close' data-dismiss='alert'>×</button>
        <?php echo $alert['message']; ?>
    </div>
<?php endif; ?>

<?php if(validation_errors()): ?>
  <div class='alert alert-danger'>
        <button type='button' class='close' data-dismiss='alert'>×</button>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>