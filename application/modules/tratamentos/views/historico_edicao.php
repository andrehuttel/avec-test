<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#historico-edicao-historcio">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#historico-edicao-historcio">
                    <i class='glyphicon glyphicon-align-justify'></i> Visualizar Histórico de Edição
                </a>
            </h4>
        </div>

        <div id="historico-edicao-historcio" class="panel-collapse collapse">
            <div class="panel-body">
              <?php echo br(); ?>

                <table class="table table-bordered">
                    <thead>
                        <td colspan="3" style='text-align:right'><strong>Total de edições:</strong> <?php echo count($historico_edicao); ?></td>
                    </thead>
                
                    <thead>
                        <th>Data de Edição</th>
                        <th>Hora de Edição</th>
                        <th>Usuário</th>
                    </thead>

                    <tbody>
                        <?php if($historico_edicao): ?>
                            <?php foreach ($historico_edicao as $hist_edicao): ?>
                                <tr>
                                    <td><?php echo bd2data($hist_edicao->data); ?></td>
                                    <td><?php echo $hist_edicao->hora; ?></td>
                                    <td><?php echo $hist_edicao->usuario; ?></td>
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
