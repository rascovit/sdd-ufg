<?php $this->assign('title', 'Núcleos de conhecimento'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li class="active">Lista de núcleos de conhecimento</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <?= $this->Form->create(null, ['type' => 'get']) ?>
            <div class="box-header">
                <h3 class="box-title">Lista de núcleos de conhecimento</h3>
                <div class="box-tools">
                    <div class="input-group" style="width: 270px">
                        <?= $this->Html->link(
                            '<i class="fa fa-plus-circle"></i> ' . __('Adicionar'),
                            ['action' => 'add'],
                            [
                                'escape' => false,
                                'data-toggle' => 'tooltip',
                                'data-original-title' => __('Adicionar'),
                                'class' => 'btn btn-sm btn-primary',
                                'style' => 'display: inline-block'
                            ]
                        );
                        ?>

                        <?= $this->Form->input('name', [
                            'label' => false,
                            'class' => 'form-control input-sm pull-right',
                            'style' => 'width: 150px',
                            'placeholder' => __('Buscar por nome'),
                            'templates' => [
                                'inputContainer' => '{{content}}'
                            ]
                        ]) ?>

                        <div class="input-group-btn">
                            <?= $this->Form->button('<i class="fa fa-search"></i>', [
                                'class' => 'btn btn-sm btn-default',
                                'escape' => false
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->Form->end() ?>

            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                        <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
                        <th width="200px"><?= __('Ações') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($knowledges->isEmpty()): ?>
                        <tr>
                            <td colspan="3" class="text-center">Não existe nenhum núcleo de conhecimento cadastrado</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($knowledges as $knowledge): ?>
                        <tr>
                            <td><?= $this->Number->format($knowledge->id) ?></td>
                            <td><?= h($knowledge->name) ?></td>
                            <td>
                                <?= $this->Html->link(
                                    '',
                                    ['action' => 'view', $knowledge->id],
                                    [
                                        'title' => __('Visualizar'),
                                        'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
                                        'data-toggle' => 'tooltip',
                                        'data-original-title' => __('Visualizar'),
                                    ]
                                ) ?>
                                <?= $this->Html->link(
                                    '',
                                    ['action' => 'edit', $knowledge->id],
                                    [
                                        'title' => __('Editar'),
                                        'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
                                        'data-toggle' => 'tooltip',
                                        'data-original-title' => __('Editar'),
                                    ]
                                ) ?>
                                <?= $this->Form->postLink(
                                    '',
                                    ['action' => 'delete', $knowledge->id],
                                    [
                                        'confirm' => __('Você tem certeza de que deseja remover o núcleo de conhecimento "{0}"?', $knowledge->name),
                                        'title' => __('Remover'),
                                        'class' => 'btn btn-sm btn-danger glyphicon glyphicon-trash',
                                        'data-toggle' => 'tooltip',
                                        'data-original-title' => __('Remover'),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
