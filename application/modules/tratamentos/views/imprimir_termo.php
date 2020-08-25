<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="VEG Tecnologia">

    <title><?php echo $this->config->item('nome_empresa'); ?> :: Impressão</title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/impressao.css">
  </head>
   
  <body>

    <div class="info-paciente print">

        <br style="clear:both;">

        <div class="titulo">
            <h3><?php echo $termo->titulo; ?></h3>
        </div>
        <br>

        <?php echo $termo->conteudo; ?>

    </div>

  </body>
</html>
