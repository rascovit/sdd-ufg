<?php $this->assign('title', 'Disciplinas'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li class="active">Lista de disciplinas</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary <?= !$isFiltered ? 'collapsed-box' : '' ?>">
            <div class="box-header with-border">
                <button class="btn btn-primary btn-xs pull-left" style="margin-right: 5px" data-widget="collapse" data-original-title="Collapse">
                    <i class="fa <?= !$isFiltered ? 'fa-plus' : 'fa-minus' ?>"></i>
                </button>

                <h3 class="box-title" style="vertical-align: middle"> <?= __('Filtros') ?></h3>
            </div>

            <?= $this->Form->create(null, ['type' => 'get']) ?>
            <div class="box-body" <?= !$isFiltered ? 'style="display: none;"' : '' ?>>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <?= $this->Form->input('name', ['label' => __('Nome da disciplina')]) ?>
                            </div>
                            <div class="col-sm-4">
                                <?=	$this->Form->input('course', ['label' => __('Curso'), 'options' => $courses]) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $this->Form->input('knowledge', ['label' => __('Núcleo de conhecimento'), 'options' => $knowledges]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer" <?= !$isFiltered ? 'style="display: none;"' : '' ?>>
                <?= $this->Form->button(__('Filtrar'), ['class' => 'btn btn-success']) ?>
                <a id="empty" class="btn btn-default"><?= __('Limpar') ?></a>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Lista de disciplinas</h3>
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
                            <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
                            <th><?= $this->Paginator->sort('course_id', __('Curso')) ?></th>
                            <th><?= $this->Paginator->sort('knowledge_id', __('Núcleo de conhecimento')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($subjects->isEmpty()): ?>
                            <tr>
                                <td colspan="5" class="text-center">Não existe nenhuma disciplina cadastrada</td>
                            </tr>
                        <?php else: ?>
                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?= $this->Number->format($subject->id) ?></td>
                                <td><?= h($subject->name) ?></td>
                                <td><?= $subject->has('course') ? $this->Html->link($subject->course->name, ['controller' => 'Courses', 'action' => 'view', $subject->course->id]) : '' ?></td>
                                <td><?= $subject->has('knowledge') ? $this->Html->link($subject->knowledge->name, ['controller' => 'Knowledges', 'action' => 'view', $subject->knowledge->id]) : '' ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        '',
                                        ['action' => 'view', $subject->id],
                                        [
                                            'title' => __('Visualizar'),
                                            'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Visualizar'),
                                        ]
                                    ) ?>
                                    <?= $this->Html->link(
                                        '',
                                        ['action' => 'edit', $subject->id],
                                        [
                                            'title' => __('Editar'),
                                            'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Editar'),
                                        ]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '',
                                        ['action' => 'delete', $subject->id],
                                        [
                                            'confirm' => __('Você tem certeza de que deseja remover a disciplina "{0}"?', $subject->name),
                                            'title' => __('Remover'),
                                            'class' => 'btn btn-sm btn-danger glyphicon glyphicon-trash',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Remover'),
                                        ]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
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

<script>
    <?php $this->Html->scriptStart(['block' => true]); ?>
    $(document).ready(function() {
        $("#empty").click(function() {
            var form = $(this).closest('form');
            form.find("input[type=text], textarea").val("");

            form.find("select").each(function(elem) {
                if($(this).prop('multiple') == true) {
                    $(this).find("option:selected").prop("selected", false);
                    $(this).select2('val', '');
                } else {
                    $(this).find('option').first().prop('selected', true);
                }
            });
        });
    });
    <?php $this->Html->scriptEnd(); ?>
</script>
