<?php $this->assign('title', 'Processos de distribuição'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li class="active">Processos de distribuição</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Lista de processos</h3>
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
                            <th><?= $this->Paginator->sort('initial_date', __('Data inicial')) ?></th>
                            <th><?= $this->Paginator->sort('final_date', __('Data final')) ?></th>
                            <th><?= $this->Paginator->sort('status', __('Situação')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($processes->isEmpty()): ?>
                            <tr>
                                <td colspan="5" class="text-center">Não existe nenhum processo cadastrado</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($processes as $process): ?>
                            <tr>
                                <td><?= $this->Number->format($process->id) ?></td>
                                <td><?= h($process->name) ?></td>
                                <td><?= h($process->initial_date->i18nFormat('dd/MM/yyyy')) ?></td>
                                <td><?= h($process->final_date->i18nFormat('dd/MM/yyyy')) ?></td>
                                <td><?= $process->display_status ?></td>
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
                                    <?php if($process->status == 'OPEN' ): ?>
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
                                            ['action' => 'cancel', $process->id],
                                            [
                                                'confirm' => __('Você tem certeza de que deseja cancelar o processo "{0}"?', $process->name),
                                                'title' => __('Cancelar'),
                                                'class' => 'btn btn-sm btn-danger glyphicon glyphicon-trash',
                                                'data-toggle' => 'tooltip',
                                                'data-original-title' => __('Cancelar'),
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            __('Fechar'),
                                            ['action' => 'close', $process->id],
                                            [
                                                'confirm' => __('Você tem certeza de que deseja fechar o processo "{0}"?', $process->name),
                                                'title' => __('Fechar Processo'),
                                                'data-toggle' => 'tooltip',
                                                'data-original-title' => __('Fechar'),
                                                'class' => 'btn btn-sm btn-primary'
                                            ]
                                        );
                                        ?>
                                    <?php endif; ?>
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
