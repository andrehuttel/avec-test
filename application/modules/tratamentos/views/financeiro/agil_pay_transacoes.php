<?php if($config_agilpay_vendedor_id->valor): ?>
<div class="panel panel-default">
    <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#agilpay-transacoes">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#agilpay-transacoes">
                <i class='glyphicon glyphicon-credit-card'></i> Transações Clínica Ágil Pay 
            </a>
        </h4>
    </div>

    <div id="agilpay-transacoes" class="panel-collapse collapse">
        <div class="panel-body">
          <?php echo br(); ?>

            <table class="table table-bordered">                
                <thead>
                    <th>Data/Hora de Emissão</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Valor Total</th>
                    <th>Vezes</th>
                    <th>ID Transação</th>
                </thead>

                <tbody>
                    <?php if($transacoes_agilpay): ?>
                        <?php foreach ($transacoes_agilpay as $dados_agilpay): ?>
                            <tr class='hover2'>
                                <td><?= format_data($dados_agilpay->data_emissao); ?> - <?= substr($dados_agilpay->hora_emissao, 0, 5); ?>h</td>
                                <td><?= $dados_agilpay->usuario; ?></td>
                                <td>
                                    <!--Pendente :: Status-->
                                    <?php if($dados_agilpay->status == 1): ?>
                                    <span data-toggle="popover" data-placement="top" title="Situação" data-content="<?= ($dados_agilpay->zoop_message ? $dados_agilpay->zoop_message : 'Pendente') ?>" class="spanpop label label-primary" style='margin-left: 0 !important; color: #fff !important; font-size: 12px !important; font-weight: normal;'><i class="glyphicon glyphicon-time"></i> Pendente</span>

                                    <!--Sucesso :: Status-->
                                    <?php elseif($dados_agilpay->status == 2): ?>
                                    <span data-toggle="popover" data-placement="top" title="Situação" data-content="<?= ($dados_agilpay->zoop_message ? $dados_agilpay->zoop_message : 'Sucesso') ?>" class="spanpop label label-success" style='margin-left: 0 !important; color: #fff !important; font-size: 12px !important; font-weight: normal;'><i class="glyphicon glyphicon-ok-circle"></i> Sucesso</span>

                                    <!--Falha :: Status-->
                                    <?php elseif($dados_agilpay->status == 3): ?>
                                    <span data-toggle="popover" data-placement="top" title="Situação" data-content="<?= ($dados_agilpay->zoop_message ? $dados_agilpay->zoop_message : 'Falha') ?>" class="spanpop label label-danger" style='margin-left: 0 !important; color: #fff !important; font-size: 12px !important; font-weight: normal;'><i class="glyphicon glyphicon-remove-circle"></i> Falha</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= "R$ ".number_format($dados_agilpay->valor_total, 2,",","."); ?></td>
                                <td><?= $dados_agilpay->vezes; ?>x</td>
                                <td><?= $dados_agilpay->zoop_transaction_id; ?></td>
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
<?php endif; ?>