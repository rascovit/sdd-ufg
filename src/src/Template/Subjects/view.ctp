<?php $this->assign('title', 'Disciplina: '.$subject->name); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link(__('Disciplinas'), ['action' => 'index']) ?></li>
    <li class="active"><?= $subject->name ?></li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= __('Informações da disciplina') ?></h3>
                <div class="pull-right box-tools">
                    <?= $this->Html->link(
                        __('Editar'),
                        ['action' => 'edit', $subject->id],
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
                        <td><?= $this->Number->format($subject->id) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Nome') ?></th>
                        <td><?= h($subject->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Curso') ?></th>
                        <td><?= $subject->has('course') ? $this->Html->link($subject->course->name, ['controller' => 'Courses', 'action' => 'view', $subject->course->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Núcleo de conhecimento') ?></th>
                        <td><?= $subject->has('knowledge') ? $this->Html->link($subject->knowledge->name, ['controller' => 'Knowledges', 'action' => 'view', $subject->knowledge->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Carga horária teórica') ?></th>
                        <td><?= $this->Number->format($subject->theoretical_workload) ?> hora(s)</td>
                    </tr>
                    <tr>
                        <th><?= __('Carga horária prática') ?></th>
                        <td><?= $this->Number->format($subject->practical_workload) ?> hora(s)</td>
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
                <h3 class="box-title"><?= __('Turmas da disciplina') ?></h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                    <tr>
                        <th><?= __('#ID') ?></th>
                        <th><?= __('Nome') ?></th>
                        <th><?= __('Vagas') ?></th>
                        <th><?= __('Processo de distribuição') ?></th>
                        <th width="200px"><?= __('Ações') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(count($subject->clazzes) < 1): ?>
                        <tr>
                            <td colspan="5" class="text-center"><?= __('Essa disciplina não possui nenhuma turma associada') ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($subject->clazzes as $clazz): ?>
                        <tr>
                            <td><?= h($clazz->id) ?></td>
                            <td><?= h($clazz->name) ?></td>
                            <td><?= h($clazz->vacancies) ?></td>
                            <td><?= h($clazz->process->name) ?></td>
                            <td>
                                <?= $this->Html->link(
                                    '',
                                    ['controller' => 'Clazzes', 'action' => 'view', $clazz->id],
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><?= __('Docentes que já ministraram a disciplina') ?></h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                    <tr>
                        <th><?= __('#ID') ?></th>
                        <th><?= __('Nome') ?></th>
                        <th><?= __('Matrícula') ?></th>
                        <th><?= __('Formação') ?></th>
                        <th><?= __('Carga horária') ?></th>
                        <th><?= __('Currículo Lattes') ?></th>
                        <th width="200px"><?= __('Ações') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($experiencedTeachers->count() < 1): ?>
                        <tr>
                            <td colspan="7" class="text-center"><?= __('Nenhum docente ministrou essa disciplina anteriormente') ?></td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($experiencedTeachers as $teacher): ?>
                        <tr>
                            <td><?= h($teacher->teacher->id) ?></td>
                            <td><?= h($teacher->teacher->user->name) ?></td>
                            <td><?= h($teacher->teacher->registry) ?></td>
                            <td><?= h($teacher->teacher->formation) ?></td>
                            <td><?= h($teacher->teacher->workload) ?></td>
                            <td><?= h($teacher->teacher->url_lattes) ?></td>
                            <td>
                                <?= $this->Html->link(
                                    '',
                                    ['controller' => 'Teachers', 'action' => 'view', $teacher->teacher->id],
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
