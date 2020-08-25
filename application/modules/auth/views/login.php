<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title><?php echo 'Avec :: '; echo $titulo ?></title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="VEG Tecnologia">
        <meta name="robots" content="noindex, nofollow, noarchive">
        <meta name="googlebot" content="noindex" />

        <link rel="icon" href="<?= base_url(); ?>assets/img/logo.png" type="image/png" sizes="16x16">
        <link type='text/css' href="<?php echo base_url();?>assets/css/login.css" rel='stylesheet' />
    </head>

    <body>
        <div class="container-fluid">
            <div id="conteudo_login" class="container">
                <div id="descricao_sistema" class="span6 box_texto visible-desktop">
                    <p class="titulo">Seja bem-vindo ao Avec - Contatos</p>
                    </br>
                    <p class="texto">Sistema CRUD de contatos <br>que consome uma API REST em Lumen</p>
                </div>

                <div class="span6"  id="box_login">
                    <div class="row-fluid">
                        </br>
                    </div>

                    <?php echo $this->load->view($pagina);?>

                </div>
            </div>
        </div>

        <script type="text/javascript"> CI_ROOT = '<?php echo base_url(); ?>'; </script>
        <script src="<?php echo base_url(); ?>assets/js/vendor/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/vendor/jquery.backstretch.min.js"></script>
        <script type="text/javascript">$.backstretch(CI_ROOT + 'assets/img/login/avec2.jpg');</script>
    </body>
</html>
