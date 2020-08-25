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

        <div class="titulo">
            <h3><?php echo $contrato->titulo; ?></h3>
        </div>

        <br>

        <table class="tabela">
            <tr>
                <td colspan="3">Tratamento nº <?php echo $tratamento->tratamento_id; ?></td>
            </tr>
            <tr>
                <td colspan="2"><b> Paciente: </b> <br> <?php echo $tratamento->paciente; ?> </td>
                <td><b> CPF: </b> <br> <?php echo $tratamento->cpf; ?> </td>
            </tr>

            <tr>
                <td><b> Endereço: </b> <br> <?php echo $tratamento->endereco; ?> </td>
                <td><b> Bairro: </b> <br> <?php echo $tratamento->bairro; ?> </td>
                <td><b> CEP: </b> <br> <?php echo $tratamento->cep; ?> </td>
            </tr>

            <tr>
                <td><b> Cidade: </b> <br> <?php echo $tratamento->cidade; ?> </td>
                <td><b> Estado: </b> <br> <?php echo @$tratamento->estado; ?> </td>
                <td><b> Telefone: </b> <br> <?php echo $tratamento->telefone1; ?> </td>
            </tr>
        </table>

        <?php echo br(); ?>

        <table class="tabela">
            <tr>
                <td colspan="2" bgcolor="#e4e4e4"><center style="font-weight: bold;">Serviços</center></td>
            </tr>

            <tr bgcolor="#e4e4e4">
                <td width="100">Quantidade</td>
                <td><?php if($tratamento->categoria_tratamento_id == 3): ?>Mensalidade<?php else: ?>Procedimentos<?php endif; ?></td>
            </tr>

            <?php if($tratamento->categoria_tratamento_id == 5): ?>
                <?php foreach($tratamento->procedimentos as $procedimento): ?>
                    <tr>
                        <td><?php echo $procedimento->sessoes_totais; ?></td>
                        <td><?php echo $procedimento->procedimento; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td><?php echo $tratamento->sessoes_totais; ?></td>
                    <td><?php if($tratamento->categoria_tratamento_id == 3): ?><?php echo $tratamento->tratamento_mensalidade; ?><?php else: ?><?php echo $tratamento->procedimento; ?><?php endif; ?></td>
                </tr>
            <?php endif; ?>
        </table>

        <?php echo br(1); ?>

        <?php echo $contrato->conteudo; ?>

  </body>
</html>
