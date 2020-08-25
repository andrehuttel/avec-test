<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="VEG Tecnologia">

        <title><?php echo $this->config->item('nome_empresa'); ?> :: Impressão</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css">

        <style type="text/css">
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
            padding: 2px 5px!important;
        }
        .table{
            margin-bottom: 10px!important;
        }
        h5{
            padding: 0;
            margin: 0;
        }
        </style>
    </head>
   
    <body>
        <div class="info-paciente print">
            <?php if($_config_layout[4]->valor == 'Sim'): ?>
                <div style="width:100%;float:left;margin:10px;height:45px;text-align:center;">
                    <img src="./uploads/visual/<?= $this->session->userdata('logo') ?>" width="120">
                </div>
            <?php endif ?>

            <h4 style='text-align:center'>CONTROLE DE PRESENÇA</h4>

            <h1 style='text-align:center'><?php echo $tratamento->especialidade; ?></h1>

            <br>

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="70%">Paciente: <?php echo $tratamento->nome; ?></td>
                    <td>Telefone: <?php echo $tratamento->telefone1; ?></td>
                </tr>
            </table>

            <br style="clear:both;">
            <p><strong><?php echo $_config_dados[22]->valor; ?></strong></p>

            <table width="100%" border="1">
                <tr>
                    <th>Sessão</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th width="200">Assinatura</th>
                    <th width="200">Assinatura Profissional</th>
                </tr>

                <?php for($i=0; $i < $tratamento->sessoes_totais; $i++): ?>
                    <tr>
                        <td height="20px"></td>
                        <td height="20px"></td>
                        <td height="20px"></td>
                        <td height="20px"></td>
                        <td height="20px"></td>
                    </tr>
                <?php endfor; ?>
            </table>
        </div>
    </body>
</html>
