<?php $this->assign('title', 'Docentes'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li class="active">Lista de docentes</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <?= $this->Form->create(null, ['type' => 'get']) ?>
            <div class="box-header">
                <h3 class="box-title">Lista de docentes</h3>
                <div class="box-tools">
                    <div class="input-group" style="width: 270px">
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
                            <th><?= $this->Paginator->sort('registry', __('Matrícula')) ?></th>
                            <th><?= $this->Paginator->sort('formation', __('Formação')) ?></th>
							<th><?= $this->Paginator->sort('workload', __('Carga Horária')) ?></th>
							<th><?= $this->Paginator->sort('url_lattes', __('Currículo Lattes')) ?></th>
							<th><?= $this->Paginator->sort('situation', __('Situação')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($teachers->isEmpty()): ?>
                            <tr>
                                <td colspan="8" class="text-center">Não existe nenhum docente cadastrado</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($teachers as $teacher): ?>
                            <tr>
                                <td><?= $this->Number->format($teacher->id) ?></td>
                                <td><?= h($teacher->user->name) ?></td>
                                <td><?= h($teacher->registry) ?></td>
								<td><?= h($teacher->formation) ?></td>
								<td><?= h($teacher->workload) ?></td>
								<td><?= h($teacher->url_lattes) ?></td>
								<td><?= h($teacher->situation) ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        '',
                                        ['action' => 'view', $teacher->id],
                                        [
                                            'title' => __('Visualizar'),
                                            'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Visualizar'),
                                        ]
                                    ) ?>
                                    <?= $this->Html->link(
                                        '',
                                        ['action' => 'edit', $teacher->id],
                                        [
                                            'title' => __('Editar'),
                                            'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Editar'),
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '',
                                        ['action' => 'delete', $teacher->id],
                                        [
                                            'confirm' => __('Você tem certeza de que deseja remover a docente "{0}"?', $teacher->user->name),
                                            'title' => __('Remover'),
                                            'class' => 'btn btn-sm btn-danger glyphicon glyphicon-trash',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Remover'),
                                        ]
                                    ) ?>
									<?= $this->Html->link(
                                        '',
                                        ['action' => 'allocateClazzes', $teacher->id],
                                        [
                                            'title' => __('Turmas de Interesse'),
                                            'class' => 'btn btn-sm btn-default glyphicon glyphicon-education',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Escolher turmas de interesse para concorrer em Processo de Distribuição'),
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
