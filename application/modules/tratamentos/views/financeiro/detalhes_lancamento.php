<?php if($this->auth_library->check_permission('financeiro', 'caixa', 'gerar_recibo', 'Gerar Recibo')): ?>
    <?php if($historico_pagamento): ?>
        <a href="<?php echo base_url() . 'financeiro/caixa/gerar_recibo/0/' . $registro->id_lancamento; ?>" target="_blank" class="btn btn-info" style='margin-left: 10px; margin-top: -10px'>
            <i class="glyphicon glyphicon-list-alt"></i> Recibo
        </a>

        <?php echo br(2) ?>
    <?php endif ?>
<?php endif; ?>

<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#lancamento-1">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <i class='glyphicon glyphicon-folder-open'></i> Informações do Lançamento
                </a>
            </h4>
        </div>

        <?php if(isset($permissao['98'])): ?>
        <a href="<?php echo base_url(); ?>financeiro/caixa/editar_lancamento/<?php echo $registro->id_lancamento ?>" title="Editar" style="float:right; margin-top:-28px; margin-right:10px;">
            <i data-toggle="popover" data-placement="top" data-content="Editar Dados do Lançamento" class="spanpop glyphicon glyphicon-pencil"></i>
        </a>
        <?php endif; ?>

        <div id="lancamento-1" class="panel-collapse collapse in">
            <table class="table">
                <tr class='hover2'>
                    <td><strong>Competência</strong></td>

                    <td><?php echo format_data($registro->competencia); ?></td>
                </tr>

                <tr class='hover2'>
                    <td><strong>Parcelas</strong></td>

                    <td><?php echo $registro->parcelas; ?>x</td>
                </tr>

                <tr class='hover2'>
                    <td><strong>Categoria</strong></td>

                    <td><?php echo $registro->tipo_plano_conta; ?></td>
                </tr>

                <tr class='hover2'>
                    <td><strong>Subcategoria</strong></td>

                    <td><?php echo $registro->plano_conta; ?></td>
                </tr>

                <tr class='hover2'>
                    <td><strong>Conta</strong></td>

                    <td><?php echo $registro->conta; ?></td>
                </tr>

                <tr class='hover2'>
                    <td><strong>Tipo de Pagamento</strong></td>

                    <td><?php echo $registro->tipo_pagamento; ?></td>
                </tr>
                
                <tr class='hover2'>
                    <td><strong>Pagar o Lançamento</strong></td>

                    <td><a href='<?php echo base_url(); ?>financeiro/caixa/fluxo/<?php echo $registro->id_lancamento; ?>'>Visualizar no Fluxo de Caixa</a></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#lancamento-2">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    <i class='glyphicon glyphicon-align-justify'></i> Histórico de Pagamentos
                </a>
            </h4>
        </div>

        <div id="lancamento-2" class="panel-collapse collapse">
            <div class="panel-body">
              <input type='text' id='txtBusca' placeholder='Pesquisar' class='form-control' style='width:200px !important;'><br><br>

                <table class="table table-bordered">
                    <thead>
                        <th>Pt.</th>
                        <th>Venc.</th>
                        <th>Data Pgto.</th>
                        <th>Valor Pt.</th>
                        <th>Multa</th>
                        <th>Juros</th>
                        <th>Descontos</th>
                        <th>Valor Pago</th>
                        <th>Situação</th> 
                        <th>Conta</th>
                    </thead>

                    <tbody id="tableItens">
                        <?php if($historico_pagamento): ?>
                            <?php foreach ($historico_pagamento as $dados_historico): ?>
                                <tr class='hover2'>
                                    <td><?php echo $dados_historico->num_parcela; ?></td>
                                    <td><?php echo format_data($dados_historico->data_vencimento); ?></td>
                                    <td><?php echo format_data($dados_historico->data)." - ".mb_substr($dados_historico->hora,0,5); ?></td>
                                    <td><?php echo "R$ ".number_format($dados_historico->valor_parcela, 2,",","."); ?></td>
                                    <td><?php echo "R$ ".number_format($dados_historico->multa, 2,",","."); ?></td>
                                    <td><?php echo "R$ ".number_format($dados_historico->juros, 2,",","."); ?></td>
                                    <td><?php echo "R$ ".number_format($dados_historico->descontos, 2,",","."); ?></td>
                                    <td><?php echo "R$ ".number_format($dados_historico->valor, 2,",","."); ?></td>
                                    <td><?php echo ($dados_historico->status == 0 ? "Pago" : "Pago Parc."); ?></td>
                                    <td><?php echo "<span title='".$dados_historico->conta."'>".substr($dados_historico->conta, 0, 7)."...</span>"; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan='10'>Nenhum registro encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading head" data-toggle="collapse" data-parent="#accordion" href="#lancamento-3">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                    <i class='glyphicon glyphicon-align-justify'></i> Pagamentos Pendentes
                </a>
            </h4>
        </div>

        <div id="lancamento-3" class="panel-collapse collapse">
            <div class="panel-body">
              <input type='text' id='txtBusca2' placeholder='Pesquisar' class='form-control' style='width:200px !important;'>

              <?php $this->load->view('legenda_parcela'); ?>

              <?php echo br(2); ?>

                <table class="table table-bordered">
                    <thead>
                        <td colspan="9" style='text-align:right'><strong>Total:</strong> R$ <?php echo number_format($total_pagar->pagar, 2,",","."); ?></td>
                    </thead>
                
                    <thead>
                        <th>Parcela</th>
                        <th>Data de Vencimento</th>
                        <th>Valor da Parcela</th>
                        <th>Valor Restante</th>
                        <th>Documento</th>
                        <th>Tipo de Pagamento</th>
                    </thead>

                    <tbody id="tableItens2">
                        <?php if($pagamento_caixa): ?>
                            <?php foreach ($pagamento_caixa as $dados_pagamento): ?>
                                <tr id='liha-parcela-<?php echo $dados_pagamento->id; ?>' id-parcela='<?php echo $dados_pagamento->id; ?>' class='<?php echo $this->parcela->status($dados_pagamento->valor_pagar, $dados_pagamento->data_vencimento); ?>'>

                                    <td class='col-prestacao'><?php echo $dados_pagamento->num_parcela; ?></td>
                                    <td class='col-vencimento'><?php echo format_data($dados_pagamento->data_vencimento); ?></td>
                                    <td class='col-parcela'><?php echo "R$ ".number_format($dados_pagamento->valor_parcela, 2,",","."); ?></td>
                                    <td class='col-restante'><?php echo "R$ ".number_format($dados_pagamento->valor_pagar, 2,",","."); ?></td>
                                    <td class='col-documento'><?php echo $dados_pagamento->documento; ?></td>
                                    <td class='col-tipo-pagamento-pc'>
                                        <?php echo $dados_pagamento->nome_tipo_pagamento; ?>

                                        <?php if($dados_pagamento->cartao): ?>
                                            - 
                                            <?php echo ($dados_pagamento->imagem_cartao ? '<img src="'.base_url().'assets/img/cartoes/'.$dados_pagamento->imagem_cartao.'" style="width: 40px; box-shadow: 1px 1px 4px #999">' : '<i class="glyphicon glyphicon-credit-card" style="font-size:18px"></i>'); ?>

                                            <span style='position:relative; <?php echo ($dados_pagamento->imagem_cartao ? 'top:2px;' : 'top:-4px;') ?> left:5px'><?php echo $dados_pagamento->cartao; ?></span>
                                        <?php endif; ?>
                                    </td>
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

    <?php $this->load->view('agil_pay_transacoes'); ?>
</div>
