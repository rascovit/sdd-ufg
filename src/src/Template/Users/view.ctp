<?php $this->assign('title', 'Usuário: '.$user->id); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Usuários'), ['action' => 'index']) ?></li>
<li class="active"><?= $user->id ?></li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Informações do usuário</h3>
                <div class="pull-right box-tools">
                    <?= $this->Html->link(
                    __('Editar'),
                    ['action' => 'edit', $user->id],
                    [
                        'data-toggle' => 'tooltip',
                        'data-original-title' => __('Editar'),
                        'class' => 'btn btn-sm btn-primary'
                    ]
                );
                ?>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-valign-middle">
                <tr>
                    <th><?= __('#ID') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Login') ?></th>
                    <td><?= $user->login ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= $user->email ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= $user->name ?></td>
                </tr>
                <tr>
                    <th><?= __('Administrador') ?></th>
                    <td><?= $user->is_admin ? __('Sim') : __('Não'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Docente do Usuário</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th><?= __('#ID') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Matrícula') ?></th>
                            <th><?= __('Formação') ?></th>
                            <th><?= __('Carga Horária') ?></th>
                            <th><?= __('Currículo Lattes') ?></th>
                            <th><?= __('Situação') ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($user->teachers)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Esse usuário não possui um docente associado</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($user->teachers as $teacher): ?>
                                <tr>
                                    <td><?= h($teacher->id) ?></td>
                                    <td><?= h($teacher->name) ?></td>
                                    <td><?= h($teacher->registry) ?></td>
                                    <td><?= h($teacher->formation) ?></td>
                                    <td><?= h($teacher->workload) ?></td>
                                    <td><?= h($teacher->url_lattes) ?></td>
                                    <td><?= h($teacher->situation) ?></td>
                                    <td>
                                        <?= $this->Html->link(
                                            '',
                                            ['controller' => 'Teachers', 'action' => 'view', $teacher->id],
                                            [
                                                'title' => __('Visualizar'),
                                                'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
                                                'data-toggle' => 'tooltip',
                                                'data-original-title' => __('Visualizar'),
                                            ]
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
