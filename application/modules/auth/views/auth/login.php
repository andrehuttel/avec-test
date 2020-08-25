<?php

//Verifica se existe a sessão que contém as informações do Alerta
  if($this->session->flashdata('alert')) 
    $alert = $this->session->flashdata('alert');

  //Verifica a existencia da variável de alerta, seja ela advinda de uma sessão ou passada por parâmetros no carregamento da view
  if(isset($alert['message']) && !empty($alert['message'])){
  ?>
    <!-- Alerta exibido no topo da tela, contendo o alerta e seu respectivo retorno -->
    <div id="alerta" class='alert <?php echo $alert['return']; ?> '>
      <button type='button' class='close' data-dismiss='alert'>×</button>
      <?php echo $alert['message']; ?>
    </div>
  <?php
}?>

<?php echo form_open(base_url()."auth/login");?>
  <div style="text-align:center;">
    <?php echo form_input($identity);?>
    <?php echo form_input($password);?>
  </div>
  <br style="clear:both;"><br>
  <p>
    <?php 
      $form_submit = array('name' => 'submit', 'value' => 'ENTRAR', 'class' => 'btn btn-primary btn-large', 'id' => 'botao_entrar'); 
      echo form_submit($form_submit);
    ?>
  </p>
    
<?php echo form_close();?>