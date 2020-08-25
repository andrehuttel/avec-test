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

    <script type="text/javascript">
        window.print();
    </script>

   
  </head>
   
  <body>


<div class="info-paciente print">

    <div style="width:15%;float:left;margin:10px;height:85px">
        <img src="./uploads/visual/<?= $this->session->userdata('logo') ?>" width="100px">
    </div>

    <div style="width:80%;float:left;">
        <center>
            <h4>CADASTRO DE ALUNOS E PACIENTES</h4>
        </center>
    </div>

    <br style="clear:both;">

    <table class="table table-bordered" border="1">
        <tr>
            <td colspan="2">
                <small>Data da Avaliação:</small><br>
                <strong><?php echo isset($avaliacao->data) ? bd2data($avaliacao->data) : br();?></strong>
            </td>
            <td colspan="">
                <small>Data do Inicio do Plano:</small><br>
                <strong><?php echo $tratamento->data ? bd2data($tratamento->data) : br();?></strong>
            </td>
        </tr>

        <?php if($tratamento->categoria_tratamento_id != 4):?>
        <tr>
            <td>
                <small>Valor:</small><br>
                <strong>R$ <?php echo number_format($tratamento->valor,2,',','.');?></strong>
            </td>
            <td>
                <small>Tipo Plano:</small><br>
                <?php 
                    switch ($tratamento->tipo_plano) {
                        case 1:
                            echo "Mensal";
                            break;
                        case 3:
                            echo "Trimestral";
                            break;
                        case 6:
                            echo "Semestral";
                            break;
                        case 12:
                            echo "Anual";
                            break;
                    }
                ?>
            </td>
            <td>
                <small>Pagamento:</small><br>
                <?php echo $tratamento->tipo_pagamento;?>
            </td>
        </tr>
        <?php endif; ?>

        <tr>
            <td colspan="">
                <small>Atividade Realizada:</small><br>
                <?php //echo $tratamento->especialidade; ?>
            </td>
            <td colspan="2">
                <small>Instrutor:</small><br>
                <?php echo $tratamento->profissional; ?>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <small>Horário:</small><br>    
                <br>
                Segunda (&nbsp;&nbsp;&nbsp;)  Terça (&nbsp;&nbsp;&nbsp;)  Quarta (&nbsp;&nbsp;&nbsp;)  Quinta (&nbsp;&nbsp;&nbsp;)  Sexta (&nbsp;&nbsp;&nbsp;)
            </td>
        </tr>
        <tr>
           <td colspan="3">
               <small><strong>(OBS: primeiro pagamento deve ser realizado na clínica em dinheiro ou em cheque). </strong></small>
           </td> 
        </tr>
        
    </table>

    <table class="table table-bordered" border="1">
        <tr>
            <td colspan="3">
                <small>Nome:</small><br>
                <?php echo $tratamento->paciente; ?>
            </td>
        </tr>
        <tr>
            <td colspan="">
                <small>Endereço:</small><br>
                <?php echo $tratamento->endereco; ?>
            </td>
            <td colspan="">
                <small>Bairro:</small><br>
                <?php echo $tratamento->bairro; ?>
            </td>
            <td>
                <small>CEP:</small><br>
                <?php echo $tratamento->cep; ?>
            </td>
        </tr>
        <tr>
            <td>
                <small>Telefone Residencial:</small><br>
                <?php echo $tratamento->telefone1; ?>
            </td>
            <td>
                <small>Telefone Comercial:</small><br>
                <?php echo $tratamento->telefone2; ?>
            </td>
            <td>
                <small>Telefone Celular:</small><br>
                <?php echo $tratamento->telefone3; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <small>Data de Nascimento:</small><br>
                <?php echo bd2data($tratamento->data_nascimento); ?>
            </td>
        </tr>
        <tr>
            <td colspan="">
                <small>Profissão:</small><br>
                <?php echo $tratamento->profissao; ?>
            </td>
            <td colspan="2">
                <small>Indicação:</small><br>
                <?php echo $tratamento->particularidades; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <small>E-mail:</small><br>
                <?php echo $tratamento->email; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <small>CPF:</small><br>
                <?php echo $tratamento->cpf; ?>
            </td>
            <td colspan="">
                <small>RG:</small><br>
                <?php echo $tratamento->identidade; ?>
            </td>
        </tr>
    </table>

    <br>

    <center><h4>REGULAMENTO</h4></center>

    <p><strong>Seja bem-vindo (a)</strong></p>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Você está prestes a realizar atividades físicas ou de reabilitação. Para isso seguem algumas orientações importantes para o bom desempenho das aulas/sessões e o funcionamento do estabelecimento.</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Para realizar qualquer atividade o aluno/paciente passará previamente por uma avaliação física e postural para elaboração do plano de aula/tratamento e para explicação do regulamento interno.</p>

    <p>
        <ol>1. <strong>VESTUÁRIO:</strong> roupas leves e maleáveis/ meias antiderrapantes (opcional) para evitar pisar de calçados nos tatames/ toalha (opcional). Evitar roupas com detalhes em metal ou plástico.</ol>
        <ol>2. <strong>ALIMENTAÇÃO:</strong> não fazer qualquer atividade se estiver em jejum/ comer alguma coisa (leve) pelo menos 1 (uma) hora antes de se exercitar.</ol>
        <ol>3. <strong>AVALIAÇÃO:</strong> Será cobrado uma taxa no valor de R$20,00 para a avaliação e reavaliação de cada paciente.</ol>
        <ol>4. <strong>PONTUALIDADE:</strong> as aulas acontecerão pontualmente no horário estabelecido na avaliação e terão duração de 50 (cinquenta minutos) a 55 (cinquenta e cinco minutos). O aluno/paciente poderá entrar com até 15 (quinze) minutos de atraso, não sendo compensados.</ol>
        <ol>5. <strong>FALTAS:</strong> Avisa com até 1 (uma) horas de antecedência:
            <ol>5.a. As faltas não avisadas NÃO SERÃO REPOSTAS </ol>
            <ol>5.b. Não será descontada as faltas no valor do pacote</ol>
            <ol>5.c. Feriado não será reposto.</ol>
            <ol>5.d. Para aviso de faltas e remarcações SOMENTE no telefone 3028-3705 / 99470091</ol>
        </ol>
    </p>

    <p> 
        <ol>
            6. <strong>RECUPERAÇÕES:</strong> As recuperações poderão ser <strong>agendadas somente uma vez.</strong>

            <ol>5.e. O aluno/paciente poderá recupera-las no decorrer do plano vigente e de acordo com a disponibilidade do professor. </ol>

            <ol>5.f. Caso haja o não comparecimento ou o cancelamento da aula, a mesma, não poderá ser agendada novamente.</ol>
        </ol>
    </p>

    <p>
        <ol>
            7. <strong>DESISTÊNCIA:</strong> avisar para liberação do horário, caso contrário a mensalidade continua sendo gerado. 

            <ol>5.g. Na desistência do plano escolhido, o cliente pagará uma multa no valor do mês cheio mais encargos bancários</ol>
            <ol>5.h. Deverá ser assinada a declaração de desistência na clínica. Caso contrário as mensalidades continuam sendo geradas.</ol>
            <ol>5.i. Somente haverá cancelamento sem a cobrança da multa em caso de atestado médico impossibilitando a pratica de exercícios físicos apresentado na clínica. Mas será cobrado os encargos bancário.</ol>
            <ol>5.j. Não será devolvido as mensalidades já cobradas.</ol>
            <ol>5.k. Após o vencimento do plano e a não renovação do mesmo, será  liberada automaticamente o horário.</ol>
        </ol>
    </p>

    <p>
        <ol>
            8. <strong>PAGAMENTOS:</strong> todas as <strong>mensalidades</strong> vencem no dia 10 (dez) de cada mês.

            <ol>
                5.l. <strong>BOLETO: </strong> Será concedido o desconto do plano para pagamento até o dia do vencimento, caso contrário será cobrado o valor mensal vigente mais 2% ao mês.
                    <ol>8.1. Após 10 dias corrido do vencimento, o boleto estará sujeito a protesto em cartório.</ol>
                    <ol>8.2. Será cobrado os encargos do banco para emissão e cancelamento dos boletos.</ol>
            </ol>
            <ol>
                5.m. <strong>CHEQUE: </strong> os cheques serão pré-datados para dia 10 (dez) dos respectivos meses. 
                    <ol>8.3. Em caso de retirada dos cheques em custódia, por qualquer motivo, será cobrado os encargos bancários. </ol>
            </ol>
        </ol>
    </p>

    <p>
        <ol>
            9. <strong>REAJUSTES:</strong> os valores dos reajustes somente serão aplicados na próxima renovação do plano, não ocorrendo nenhuma cobrança a mais no decorrer do plano.
        </ol>
    </p>
    <p>
        <ol>
            10. <strong>IMPORTANTE!!</strong> Sempre avisar a fisioterapeuta se estiver com alguma dor ou indisposição para que atividades possam ser “ adaptadas” para a atual situação.
        </ol>
    </p>

    <p>
        <br>
        <i>
            Eu, <?php echo $tratamento->paciente; ?> portadora do CPF <?php echo $tratamento->cpf;?> declaro para os devidos fins que li e recebi o regulamento da Clínica Life Pilates e Reabilitação portadora do CNPJ 14.116.402/0001-60 situado na rua Benjamin Constant 2598, bairro Costa e Silva, Joinville – Santa Catarina.
        </i>
    </p>

    <div style="border-top: 2px solid #000;width:270px;text-align:center;float:right;margin-top:100px;margin-bottom:20px;">
        <strong>ASSINATURA DO ALUNO(A)/PACIENTE</strong>
    </div>

    <table cellpadding="5" cellspacing="5">
        <tr>
            <td><strong>Renovação do Plano:</strong></td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
            <td>_____/_____/_____</td>
        </tr>
    </table>

    <br>

    <table class="table table-bordered">
        <tr>
            <td width="50%">
                <small>Usuário: <?php echo $this->session->userdata('username'); ?></small><br>
                <small><?php echo $this->config->item('nome_empresa'); ?></small>
            </td>
            <td width="50%" align="right">
                <small>Data de emissão: <?php echo date('d/m/Y H:i:s'); ?></small><br>
                <small><?php echo $this->config->item('nome_empresa'); ?></small>
            </td>
        </tr>
    </table>
</div>


    <script type="text/javascript">
        // window.close();
    </script>


  </body>
</html>
