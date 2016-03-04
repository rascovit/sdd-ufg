<?php $this->assign('title', 'Papéis do Docente'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
    <li class="active">Here</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Lista de papéis</h3>
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
                            <th><?= $this->Paginator->sort('type', __('Descrição')) ?></th>
                            <th><?= $this->Paginator->sort('teacher_id', __('Docente')) ?></th>
                            <th><?= $this->Paginator->sort('knowledge_id', __('Núcleo')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($roles->isEmpty()): ?>
                            <tr>
                                <td colspan="5" class="text-center">Não existe nenhum papel cadastrado</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($roles as $role): ?>
                            <tr>
                                <td><?= $this->Number->format($role->id) ?></td>
                                <td><?= h($role->display_type) ?></td>
                                <td><?= $role->has('teacher') ? $this->Html->link($role->teacher->user->name, ['controller' => 'Teachers', 'action' => 'view', $role->teacher->id]) : '' ?></td>
                                <td><?= $role->has('knowledge') ? $this->Html->link($role->knowledge->name, ['controller' => 'Knowledges', 'action' => 'view', $role->knowledge->id]) : '' ?></td>
                                <td>
                                    <?= $this->Form->postLink(
                                        '',
                                        ['action' => 'delete', $role->id],
                                        [
                                            'confirm' => __('Você tem certeza de que deseja excluir o papel "{0}"?', $role->id),
                                            'title' => __('Cancelar'),
                                            'class' => 'btn btn-sm btn-danger glyphicon glyphicon-trash',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Cancelar'),
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
