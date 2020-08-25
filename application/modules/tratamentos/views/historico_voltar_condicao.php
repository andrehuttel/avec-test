<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#historico-voltar-condicao">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#historico-voltar-condicao">
                    <i class='glyphicon glyphicon-align-justify'></i> Visualizar Histórico de Voltar Condição
                </a>
            </h4>
        </div>

        <div id="historico-voltar-condicao" class="panel-collapse collapse">
            <div class="panel-body">
              <?php echo br(); ?>

                <table class="table table-bordered">
                    <thead>
                        <td colspan="6" style='text-align:right'><strong>Total:</strong> <?php echo count($historico_voltar_condicao); ?></td>
                    </thead>
                
                    <thead>
                        <th width="9%" class="text-center">Data da Ação</th>
                        <th width="1%" class="text-center">Sessão Manipulada</th>
                        <th width="45%">Descrição / Obs.</th>
                        <th width="10%" class="text-center">Status Anterior</th>
                        <th width="18%">Data da Evolução / Profissional</th>
                        <th width="10%" class="text-center">Usuário Alteração</th>
                    </thead>

                    <tbody>
                        <?php if($historico_voltar_condicao): ?>
                            <?php foreach ($historico_voltar_condicao as $hist_vc): 
                                $data_acao = new Datetime($hist_vc->data_acao);
                            ?>
                                <tr style="margin-bottom: 20px;">
                                    <td class="text-center"><?php echo $data_acao->format('d/m/Y H:i'); ?></td>
                                    <td class="text-center"><?php echo $hist_vc->sessao; ?></td>
                                    <td>
                                        <strong>Descrição:</strong> <?php echo $hist_vc->descricao ?> 
                                        <br>
                                        <strong>Obs.:</strong> <?php echo $hist_vc->observacao ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($hist_vc->status == 'F'): ?>
                                            Finalizado
                                        <?php elseif($hist_vc->status == 'NF'): ?>
                                            Não Compareceu
                                        <?php elseif($hist_vc->status == 'NC'): ?>
                                            Não Compareceu Sem Descontar
                                        <?php elseif($hist_vc->status == 'FJ'): ?>
                                            Falta Justificada
                                        <?php elseif($hist_vc->status == 'DP'): ?>
                                            Desmarcado p/ Profissional
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php echo bd2data($hist_vc->data)." - ".$hist_vc->hora_inicio.'h até '.$hist_vc->hora_fim.'h'; ?>
                                        <br>
                                        <?php echo $hist_vc->profissional ?>
                                    </td>
                                    <td class="text-center"><?php echo $hist_vc->usuario; ?></td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <tr>
                                <td colspan='6'>Nenhum registro encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
