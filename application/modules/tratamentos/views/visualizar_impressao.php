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
    <div class='titulo'>
        <h3>Informações do Tratamento</h3>
    </div>

    <br style="clear:both;">
    <small><strong>Cadastrado em:</strong></small>
    <small><?php echo $tratamento->data_cadastro ? bd2data($tratamento->data_cadastro) . ' ' . $tratamento->hora : br();?></small>

    <table class="tabela">
        <?php if($tipo == 0): ?>
        <tr>
            <td colspan="5">
                <small>Carteirinha (matricula):</small><br>
                <strong><?php echo $tratamento->matricula ? $tratamento->matricula : br();?></strong>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <small>Tipo:</small><br>
                <?php echo $tratamento->tipo; ?>
            </td>
        </tr>
        <?php endif; ?>
        <?php if($tipo == 0): ?>
        <tr>
            <td colspan="2">
                <small>Paciente:</small><br>
                <strong><?php echo $tratamento->nome;?></strong>
            </td>
            <td>
                <small>Sexo:</small><br>
                <?php if($tratamento->sexo=='M'): ?>
            Masculino 
        <?php elseif($tratamento->sexo=='F'): ?>
            Feminino
        <?php endif; ?>
            </td>
            <td>
                <small>Data Nascimento:</small><br>
                <?php echo bd2data($tratamento->data_nascimento);?>
            </td>
            <td>
                <small>Idade:</small><br>
                <?php echo calcular_idade($tratamento->data_nascimento);?>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <?php if($tratamento->categoria_tratamento_id != 3): ?>
                    <small>Procedimento:</small><br>
                    <?php echo $tratamento->procedimento; ?>

                <?php else: ?>
                    <small>Mensalidade:</small><br>
                    <?php echo $tratamento->tratamento_mensalidade; ?>
                <?php endif; ?>
            </td>

            <td colspan="2">
                <small>Especialidade:</small><br>
                <?php echo $tratamento->especialidade; ?>
            </td>
        </tr>

        <?php $this->load->view($view); ?>

        <tr>
            <td colspan="5">
                <small>Observações do Tratamento:</small><br>
                <?php echo $tratamento->observacao_tratamento; ?>
            </td>
        </tr>

        <tr>
            <td colspan='5'>

            <table class='table table-bordered' style='margin-bottom: 0px; margin-top: 6px;'>
                <thead>
                    <tr>
                        <th class='text-center' colspan='5'>Alta no Tratamento</th>
                    </tr>
                </thead>

                <thead>
                    <tr>
                        <th width='150' class='text-center'>Data e Hora</th>
                        <th width='150' class='text-center'>Usuário</th>
                        <th class="text-center" colspan="2">Descrição</th>
                        <th width='150' class='text-center'>Sessões Totais</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='text-center'><?php echo bd2datahora($historico_alta_tratamento->modificacao);  ?></td>
                        <td class='text-center'><?php echo $historico_alta_tratamento->usuario; ?></td>
                        <td colspan="2"><?php echo $historico_alta_tratamento->descricao; ?></td>
                        <td class='text-center'><?php echo $historico_alta_tratamento->sessoes_totais; ?></td>
                    </tr>
                </tbody>
            </table>

            </td>
        </tr>
        
    </table>

    <?php if($mensalidade): ?>
    <table class="tabela">
        <tr>
            <th colspan="5"><center>Mensalidade</center></th>
        </tr>
        <tr>
            <th>Mês de Vencimento</th>
            <th>Competência</th>
            <th>Sessões</th>
            <th>Valor</th>
        </tr>
        <?php foreach($mensalidade as $mensal): ?>
            <tr>
                <td><?php echo bd2data($mensal->vencimento); ?></td>
                <td><?php echo bd2data($mensal->competencia); ?></td>
                <td><?php echo $mensal->sessoes; ?></td>
                <td>R$ <?php echo number_format($mensal->valor,2,',','.'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <?php endif; ?>
    <?php if($tipo > 0): ?>
        <tr>
            <td colspan="5">
                <small>Paciente:</small><br>
                <strong><?php echo $tratamento->nome;?></strong>
            </td>
        </tr>

        <tr>
            <td colspan="5">
                <small>Procedimento:</small><br>
                <?php echo $tratamento->procedimento; ?>
            </td>
        </tr>

        <?php $this->load->view($view); ?>
        
    </table>
    <?php endif; ?>
    <?php if($tipo != 2): ?>
    <?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_avaliacao', 'Visualizar Avaliação')): ?>
    <h4>Avaliação</h4>

    <?php foreach ($avaliacao['titulo'] as $key => $titulo): ?>
            <table class="table">
            <?php if($avaliacao['formulario']): ?>
                <tr>
                    <td><h4><?php echo $titulo->titulo.' - '.bd2data($titulo->data_avaliacao); ?></h4></td>
                </tr>

                <?php foreach($avaliacao['formulario'][$titulo->registro] as $av): ?>
                <?php if (($av->status == 1) || ($av->status == 0 && $av->resposta != '')): ?>
                <?php if($av->alinhamento != 1): ?>
                </td>
                <tr style="border-bottom: 1px #ddd solid !important;" class="tabela2">
                <?php endif; ?>
                        <?php if($av->tipo_resposta == 'row'): ?>
                            <table class='table table-condensed' style='margin-bottom:-20px;'>
                            <tr>
                            <td>
                            <label><?php echo $av->pergunta; ?></label>
                            </td>

                            <?php foreach($av->parent as $parent): ?>
                                <td width='150'>
                                    <?php echo $parent->resposta; ?>
                                </td>
                            <?php endforeach; ?>

                            </tr>
                            </table>
                        <?php elseif($av->tipo_resposta != 'image'): ?>
                            <?php if(substr($av->tipo_resposta, 0,1) == 'h'): ?>
                                <?php echo "<".$av->tipo_resposta.">".$av->pergunta."<".$av->tipo_resposta.">"; ?>
                            <?php else: ?>

                                <?php if($av->tipo_resposta == 'span'):?>
                                    <span><?php echo "<span><strong>".str_replace(array("\n", " ", "\t"), array('<br>', '&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), $av->pergunta)."</strong></span>";?></span><br>
                                <?php else: ?>
                                    <td>
                                    <?php echo $av->pergunta; ?>
                                <?php endif ;?>

                                <?php if($av->tipo_resposta == 'eva'):?>
                                    <img src="<?php echo base_url();?>assets/img/avaliacoes/eva.jpg">
                                    <br>
                                    <?php for($i=0;$i<=10;$i++): ?>
                                        <div class='radio-eva'>
                                        <?php if($av->resposta == $i): ?>
                                            <input type='radio' disabled="disabled" checked='checked' value='<?=$i?>'>
                                        <?php else: ?>
                                            <input type='radio' disabled="disabled" value='<?=$i?>'>
                                        <?php endif; ?>
                                        </div>
                                    <?php endfor; ?>
                                <?php else: ?>
                                    <strong><?php echo $av->resposta;?></strong></td>
                                <?php endif; ?>

                            <?php endif; ?>
                                <?php else: ?>
                                    <td colspan="5">
                                    <?php if($av->alinhamento == 1): ?>
                                        <img src="<?php echo base_url() . 'uploads/avaliacao/' . $av->alternativas; ?>" style="max-width: 800px; float: right;">
                                    <?php else: ?>
                                        <img src="<?php echo base_url() . 'uploads/avaliacao/' . $av->alternativas; ?>" style="max-width: 800px;">
                                    <?php endif; ?>
                                </td>
                                <?php foreach($av->parent as $parent): ?>
                                    <td>
                                        <span><?php echo $parent->pergunta; ?>: </span>
                                        <strong><?php echo $parent->resposta;?></strong> <br>
                                    </td>
                                <?php endforeach; ?>
                        <?php endif; ?>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p><center>Avaliação do paciente não preenchida!</center></p>
            <?php endif; ?>
            </table>
            <br>
       <?php endforeach ?>
    <?php endif; ?>
    <?php endif; ?>

    <?php if($tipo != 1): ?>

    <?php if( $this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_evolucao', 'Visualizar evolução') ): ?>
    <h4>Evolução</h4>
    <table class="tabela">
        <tr bgcolor="#e4e4e4">
            <td widtd="150">Data</td>
            <td widtd="20">Sessão</td>

            <?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_descricao_evolucao', 'Visualizar Descrição da Evolução')): ?>
            <td>Descrição</td>
            <?php endif; ?>

            <td>Profissional</td>
            <td>Status</td>
        </tr>
        <?php if( $evolucao ): ?>
            <?php $i=0; ?>
            <?php foreach( $evolucao as $evol ): ?>
                <tr>
                    <td><?php echo bd2data($evol->data); ?> <?php if($evol->hora_inicio && $evol->hora_fim): ?>- <?php echo $evol->hora_inicio; ?>h até  <?php echo $evol->hora_fim; ?>h<?php endif; ?></td>
                    <td><?php echo $evol->sessao; ?></td>

                    <?php if($this->auth_library->check_permission('tratamentos', 'tratamentos', 'visualizar_descricao_evolucao', 'Visualizar Descrição da Evolução')): ?>
                    <td>
                        <?php echo $evol->descricao; ?> <br>
                        Obs.: <br>
                        <?php echo $evol->observacao; ?>
                    </td>
                    <?php endif; ?>

                    <td><?php echo $evol->profissional . ' - ' . $evol->tipo_registro . ': ' . $evol->crefito; ?></td>
                    <td class="text-center">
                        <?php if($evol->status == 'F'): ?>
                            Finalizado
                        <?php elseif($evol->status == 'NF'): ?>
                            Não Compareceu
                        <?php elseif($evol->status == 'NC'): ?>
                            Não Compareceu Sem Descontar
                        <?php elseif($evol->status == 'FJ'): ?>
                            Falta Justificada
                        <?php elseif($evol->status == 'DP'): ?>
                            Desmarcado p/ Profissional
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">O paciente não iniciou o tratamento!</td>
            </tr>
        <?php endif; ?>
    </table>
    <?php endif; ?>
    <?php endif; ?>
  </body>
</html>
