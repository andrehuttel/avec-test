<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo 'Avec - Teste :: '; echo $titulo ?></title>

    <link rel="icon" href="<?= base_url(); ?>assets/img/logo324.png" type="image/png" sizes="16x16">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker3.min.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css?v=10.8">

    <link href='<?php echo base_url(); ?>assets/css/font-lato.min.css' rel='stylesheet' type='text/css'>
    <link href='<?php echo base_url(); ?>assets/css/font-montserrat.min.css' rel='stylesheet' type='text/css'>  
  </head>
   
  <body>
    <?php $this->load->view('msg_modal'); ?> 

    <?php if($this->session->userdata('username')): ?>
        <?php $this->load->view('partes/menu_superior'); ?> 
	<?php endif;?>

    <div class="container">
        <div class="">
            <?php $this->load->view('partes/alerta'); ?> 

            <?php if($pagina != 'contacts/index'): ?>
            <h2 class=""><?php echo $titulo; ?></h2>
            <?php endif; ?>

            <div class="alerts"></div>
            <br style="clear:both;">

            <div class="pagina"><?php $this->load->view($pagina); ?></div>
        </div>

        <div class='rodape-copy' <?php if($pagina == 'contacts/index'): ?> style='position:relative; top:-30px;' <?php endif ?>>
            <img src='<?= base_url() ?>assets/img/logo.png' class='desaturate'><br>
            <span class='logo-rodape-text'>&copy <?= date('Y'); ?></span>
        </div>
    </div>

    <!--javascript -->
    <script type="text/javascript"> CI_ROOT = '<?php echo base_url(); ?>'; </script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/jquery-ui-1.10.0.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/bootstrap.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/vendor/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/jquery.mask.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/bootstrap-datepicker.pt-BR.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/vendor/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/intlTelInput.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/mask.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/funcoes.js"></script>
  </body>
</html>
