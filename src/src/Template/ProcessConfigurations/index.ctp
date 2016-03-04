<?php $this->assign('title', 'Config. Processos de distribuição'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li class="active">Config. Processos de distribuição</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Lista de Config.</h3>
                <div class="pull-right box-tools">
                    <?= $this->Html->link(
                        '<i class="fa fa-plus-circle"></i> ' . __('Adicionar'),
                        ['action' => 'add'],
                        [
                            'escape' => false,
                            'data-toggle' => 'tooltip',
                            'data-original-title' => __('Adicionar'),
                            'class' => 'btn btn-sm btn-primary'
                        ]
                    );
                    ?>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                            <th><?= $this->Paginator->sort('name', __('Descrição')) ?></th>
                            <th><?= $this->Paginator->sort('value', __('Valor')) ?></th>
                            <th><?= $this->Paginator->sort('data_type', __('Tipo')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($processConfigurations->isEmpty()): ?>
                            <tr>
                                <td colspan="5" class="text-center">Não existe nenhum processo cadastrado</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($processConfigurations as $process): ?>
                            <tr>
                                <td><?= $this->Number->format($process->id) ?></td>
                                <td><?= h($process->name) ?></td>
                                <td><?= h($process->value) ?></td>
                                <td><?= h($process->data_type) ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        '',
                                        ['action' => 'view', $process->id],
                                        [
                                            'title' => __('Visualizar'),
                                            'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Visualizar'),
                                        ]
                                    ) ?>
                                    <?= $this->Html->link(
                                        '',
                                        ['action' => 'edit', $process->id],
                                        [
                                            'title' => __('Editar'),
                                            'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Editar'),
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '',
                                        ['action' => 'delete', $process->id],
                                        [
                                            'confirm' => __('Você tem certeza de que deseja apagar o processo "{0}"?', $process->name),
                                            'title' => __('Apagar'),
                                            'class' => 'btn btn-sm btn-danger glyphicon glyphicon-trash',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Apagar'),
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="box-footer clearfix">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?= $this->Paginator->prev('«') ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next('»') ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
