<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#historico-cadastro-unificado">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#historico-cadastro-unificado">
                    <i class='glyphicon glyphicon-align-justify'></i> Visualizar Histórico de Cadastro Unificado
                </a>
            </h4>
        </div>

        <div id="historico-cadastro-unificado" class="panel-collapse collapse">
            <div class="panel-body">
              <?php echo br(); ?>

                <table class="table table-bordered">
                    <thead>
                        <td colspan="4" style='text-align:right'><strong>Total de edições:</strong> <?php echo count($historico_unificar_paciente); ?></td>
                    </thead>
                
                    <thead>
                        <th>Data da Transferência</th>
                        <th>Antigo Paciente</th>
                        <th>Paciente Atual</th>
                        <th>Usuário Alteração</th>
                    </thead>

                    <tbody>
                        <?php if($historico_unificar_paciente): ?>
                            <?php foreach ($historico_unificar_paciente as $hist_unif): ?>
                                <tr>
                                    <td><?php echo bd2data($hist_unif->data_transferencia); ?></td>
                                    <td><?php echo $hist_unif->id_paciente_antigo." - ".$hist_unif->nome_antigo; ?></td>
                                    <td><?php echo $hist_unif->id_paciente_novo." - ".$hist_unif->nome_novo; ?></td>
                                    <td><?php echo $hist_unif->usuario; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        
                        <?php else: ?>
                            <tr>
                                <td colspan='3'>Nenhum registro encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
