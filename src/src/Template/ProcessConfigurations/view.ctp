<?php $this->assign('title', 'Config. Processos de distribuição'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li class="active">Config. Processos de distribuição</li>
    <li class="active"><?= $processConfiguration->name ?></li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <tr>
                        <th><?= __('#ID') ?></th>
                        <td><?= $this->Number->format($processConfiguration->id) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Nome') ?></th>
                        <td><?= h($processConfiguration->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Descrição') ?></th>
                        <td><?= h($processConfiguration->description) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Valor') ?></th>
                        <td><?= h($processConfiguration->value) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Tipo de Dado') ?></th>
                        <td><?= h($processConfiguration->data_type) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Tipo') ?></th>
                        <td><?= h($processConfiguration->type) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
