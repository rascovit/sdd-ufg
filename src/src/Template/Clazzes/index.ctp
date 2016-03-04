<?php $this->assign('title', 'Turmas'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li class="active">Lista de turmas</li>
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
                            <div class="col-sm-3">
                                <?= $this->Form->input('status', ['label' => __("Status"), 'options' => $status]) ?>
                            </div>
                            <div class="col-sm-3">
                                <?=	$this->Form->input('process', ['label' => __('Processo de distribuição'), 'options' => $processes]) ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $this->Form->input('knowledge', ['label' => __('Núcleo de conhecimento'), 'options' => $knowledges]) ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $this->Form->input('subject', ['label' => __('Disciplina'), 'options' => $subjects]) ?>
                            </div>
                        </div>
                        <div class="row" style="display: flex; align-items: center;">
                            <div class="col-sm-5">
                                <?= $this->Form->input('schedules[]', ['label' => __('Locais/Horários de aula'), 'options' => $schedules,
                                    'multiple', 'data-placeholder' => 'Selecione o horário da aula', 'class' => 'select2']) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $this->Form->input('teachers[]', ['label' => __('Docentes alocados'), 'options' => $teachers,
                                    'multiple', 'data-placeholder' => 'Selecione o docente', 'class' => 'select2']) ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $this->Form->checkbox('only-selected', ['value' => true, 'hiddenField' => false]) ?>
                                <b><?= __('Apenas turmas ao qual foi selecionado?') ?></b>
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
                <h3 class="box-title">Lista de turmas</h3>
                <?php if($loggedUser !== false && $loggedUser->canAdmin()): ?>
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
                <?php endif; ?>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped table-second-hidden table-valign-middle">
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                        <th><?= $this->Paginator->sort('process_id', __('Processo de distribuição')) ?></th>
                        <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
                        <th><?= $this->Paginator->sort('vacancies', __('N° de vagas')) ?></th>
                        <th><?= $this->Paginator->sort('subject_id', __('Disciplina')) ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Docente(s) alocado(s)') ?></th>
                        <th width="125px"><?= __('Ações') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($clazzes->isEmpty()): ?>
                        <tr>
                            <td colspan="8" class="text-center">Nenhuma turma encontrada</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clazzes as $clazz): ?>
                            <tr>
                                <td><?= $this->Number->format($clazz->id) ?></td>
                                <td><?= $this->Html->link($clazz->process->name, ['controller' => 'Processes', 'action' => 'view', $clazz->process->id]) ?></td>
                                <td><?= h($clazz->name) ?></td>
                                <td><?= h($clazz->vacancies) ?></td>
                                <td><?= ($loggedUser !== false && $loggedUser->canAdmin()) ? $this->Html->link($clazz->subject->name, ['controller' => 'Subjects', 'action' => 'view', $clazz->subject->id]) : $clazz->subject->name ?></td>
                                <td><?= $clazz->displayStatus ?></td>
                                <td><?= $clazz->selectedTeachersNames ?></td>
                                <td>
                                    <?= $this->Html->link(
                                        '',
                                        ['action' => 'view', $clazz->id],
                                        [
                                            'title' => __('Visualizar'),
                                            'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => __('Visualizar'),
                                        ]
                                    ) ?>
                                    <button data-clazz-id="<?= $clazz->id ?>" class="btn btn-sm btn-info fa fa-glyph fa-calendar-plus-o"
                                            title ="<?= __('Locais/horários de aula') ?>"
                                            data-toggle="tooltip" data-original-title="<?= __('Locais/horários de aula') ?>"></button>

                                    <div class="btn-group" data-toggle="tooltip" title="<?= __('Mais ações') ?>" data-original-title="<?= __('Mais ações') ?>">
                                        <i class="fa fa-ellipsis-v dropdown-toggle ellipsis-menu" data-toggle="dropdown"></i>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <?php if(count($clazz->selectedTeachers) > 0 && in_array($loggedUser->teacher->id, $clazz->selectedTeachersIds)): ?>
                                                <li>
                                                    <?= $this->Html->link(
                                                        '<i class="glyphicon glyphicon-folder-open"></i> Finalizar turma',
                                                        ['action' => 'finishClazze', $clazz->id],
                                                        ['escape' => false]
                                                    ) ?>
                                                </li>
                                            <?php endif; ?>

                                            <?php if($loggedUser !== false && $loggedUser->canAdmin()): ?>
                                                <li>
                                                    <?= $this->Html->link(
                                                        '<i class="glyphicon glyphicon-pencil"></i> Editar',
                                                        ['action' => 'edit', $clazz->id],
                                                        ['escape' => false]
                                                    ) ?>
                                                </li>
                                                <li>
                                                    <?= $this->Form->postLink(
                                                        '<i class="glyphicon glyphicon-trash"></i> Remover',
                                                        ['action' => 'delete', $clazz->id],
                                                        [
                                                            'escape' => false,
                                                            'confirm' => __('Você tem certeza de que deseja remover a turma "{0}/{1}"?', $clazz->name, $clazz->process->name),
                                                        ]
                                                    ) ?>
                                                </li>
                                            <?php endif; ?>

                                            <?php if($loggedUser !== false && ($loggedUser->canAdmin() || $loggedUser->isFacilitatorOf($clazz->subject->knowledge_id))): ?>
                                                <li>
                                                    <?= $this->Html->link(
                                                        '<i class="glyphicon glyphicon-education"></i> Alocar docente',
                                                        ['controller' => 'Clazzes', 'action' => 'allocateTeacher', $clazz->id],
                                                        ['escape' => false]
                                                    ) ?>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <tr data-clazz-schedule="<?= $clazz->id ?>" style="display: none;">
                                <td colspan="8">
                                    <div class="content week-box" style="padding-bottom: 0;">
                                        <?php
                                        $index = 0;
                                        foreach($this->Utils->daysOfWeek() as $week_day => $name):
                                            if(!is_numeric($week_day) || $week_day < 2) {
                                                continue;
                                            }

                                            if(($index % 3) == 0) {
                                                if($index != 0) {
                                                    echo '</div>';
                                                }
                                                echo '<div class="row">';
                                            }
                                            $index++;
                                            ?>
                                            <div class="col-sm-4 week-day-box">
                                                <div class="week-title"><?= h($name) ?></div>
                                                <div class="week-day" data-week-day="<?= $week_day ?>">
                                                    <?php foreach ($clazz->scheduleLocals as $scheduledLocal): ?>
                                                        <?php if($scheduledLocal->week_day == $week_day): ?>
                                                            <div class="label label-primary schedule-data-div">
                                            <span class="text-ellipsis" style="max-width: calc(100% - 15px);">
                                                <?= $scheduledLocal->name ?>
                                            </span>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
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
            $(".select2").select2({
                width: '100%'
            });

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

            $("button[data-clazz-id]").click(function() {
                var clazzId = $(this).data('clazz-id');
                var scheduleTr = $('tr[data-clazz-schedule='+clazzId+']');

                var opened = $(this).data('opened');
                if(!opened) {
                    $(this).removeClass('fa-calendar-plus-o').addClass('fa-calendar-minus-o')
                        .removeClass('btn-info').addClass('btn-warning');
                    scheduleTr.show();
                } else {
                    $(this).addClass('fa-calendar-plus-o').removeClass('fa-calendar-minus-o')
                        .addClass('btn-info').removeClass('btn-warning');
                    scheduleTr.hide();
                }

                $(this).data('opened', !opened);
            });

            var dropdown = $('.ellipsis-menu').parent().find('.dropdown-menu');
            dropdown.each(function(index, element) {
                if($(element).html().trim() == '') {
                    $(element).parent().hide();
                }
            });

            $('.select2-search__field').css('width', '100%');
        });
    <?php $this->Html->scriptEnd(); ?>
</script>
