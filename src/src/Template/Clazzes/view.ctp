<?php $this->assign('title', 'Turma: '.$clazz->displayName); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Turmas'), ['action' => 'index']) ?></li>
<li class="active"><?= $clazz->displayName ?></li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-sm-5 col-md-4 col-lg-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <div class="profile-images-multiple">
                    <?php if(!empty($clazz->selectedTeachers)): ?>
                        <?php
                            foreach($clazz->selectedTeachers as $teacher) {
                                echo $this->Html->link(
                                    $this->Gravatar->generate(
                                        $teacher->user->email,
                                        [
                                            'image-options' => [
                                                'class' => 'profile-user-img img-responsive img-circle',
                                                'title' => $teacher->user->name,
                                                'data-toggle' => 'tooltip',
                                                'data-original-title' => $teacher->user->name
                                            ],
                                            'size' => 160,
                                            'default' => 'mm'
                                        ]
                                    ),
                                    ['controller' => 'Teachers', 'action' => 'view', $teacher->id],
                                    [
                                        'escape' => false
                                    ]
                                );
                            }
                        ?>
                    <?php else: ?>
                        <?= $this->Html->image('no-chosen-clazz.png', [
                            'class' => 'profile-user-img img-responsive img-circle'
                        ]) ?>
                    <?php endif; ?>
                </div>

                <div class="profile-username-box">
                    <?php if(!empty($clazz->selectedTeachers)): ?>
                        <?php foreach($clazz->selectedTeachers as $teacher): ?>
                            <a class="profile-username"><?= $teacher->user->name ?></a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <a class="profile-username"><?= __('Sem docente atribuído') ?></a>
                    <?php endif; ?>
                </div>

                <div class="table-responsive no-padding">
                    <table class="table-profile">
                        <tr>
                            <td width="170px"><b><?= __('ID') ?></b></td>
                            <td><a><?= $this->Number->format($clazz->id) ?></a></td>
                        </tr>
                        <tr>
                            <td><b><?= __('Status') ?></b></td>
                            <td><a><?= $clazz->displayStatus ?></a></td>
                        </tr>
                        <tr>
                            <td><b><?= __('Nome da turma') ?></b></td>
                            <td><a><?= h($clazz->name) ?></a></td>
                        </tr>
                        <tr>
                            <td><b><?= __('Processo de distribuição') ?></b></td>
                            <td><?= $clazz->has('process') ? $this->Html->link($clazz->process->name, ['controller' => 'Processes', 'action' => 'view', $clazz->process->id]) : '' ?></td>
                        </tr>
                        <tr>
                            <td><b><?= __('Disciplina') ?></b></td>
                            <td><?= $clazz->has('subject') ? $this->Html->link($clazz->subject->name, ['controller' => 'Subjects', 'action' => 'view', $clazz->subject->id]) : '' ?></td>
                        </tr>
                        <tr>
                            <td><b><?= __('Núcleo de conhecimento') ?></b></td>
                            <td>
                                <?php
                                switch($loggedUser->levelOf($clazz->subject->knowledge_id)) {
                                    case 1:
                                        $labelClass = 'label-success';
                                        break;
                                    case 2:
                                        $labelClass = 'label-primary';
                                        break;
                                    case 3:
                                    default:
                                        $labelClass = 'label-danger';
                                        break;
                                }
                                ?>
                                <a><span class="label <?= $labelClass ?>"><?= $clazz->subject->knowledge->name ?></span></a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-7 col-md-8 col-lg-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#subscribes" data-toggle="tab">Inscrições</a></li>
                <li><a href="#schedules" data-toggle="tab">Locais/Horários de aula</a></li>
            </ul>
            <div class="tab-content no-padding">
                <div class="tab-pane table-responsive active" id="subscribes">
                    <?php if(!$clazz->is_closed && $loggedUser->isClazzAdmin($clazz)): ?>
                        <?= $this->Form->create() ?>
                    <?php endif; ?>
                    <table id="datatable" class="table table-striped table-valign-middle" style="margin-bottom: 5px;">
                        <thead>
                        <tr>
                            <?php if(!$clazz->is_closed && $loggedUser->isClazzAdmin($clazz)): ?>
                                <th width="20px"></th>
                            <?php endif; ?>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Índice de prioridade') ?></th>
                            <th><?= __('Status') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($clazz->intents)): ?>
                            <?php foreach ($clazz->intents as $intent): ?>
                                <tr>
                                    <?php if(!$clazz->is_closed && $loggedUser->isClazzAdmin($clazz)): ?>
                                        <td style="text-align: center;">
                                            <?php
                                                $selected = false;
                                                if(isset($this->request->data['selected_teachers']) && is_array($this->request->data['selected_teachers'])) {
                                                    foreach($this->request->data['selected_teachers'] as $select) {
                                                        if($select == $intent->teacher_id) {
                                                            $selected = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            ?>
                                            <input type="checkbox" name="selected_teachers[]" value="<?= $intent->teacher_id ?>" <?= $selected ? 'checked' : '' ?>>
                                        </td>
                                    <?php endif; ?>
                                    <td><?= $this->Html->link($intent->teacher->user->name, ['controller' => 'Teachers', 'action' => 'view', $intent->teacher_id]) ?></td>
                                    <td><?= $intent->priority ?></td>
                                    <td><?= $intent->displayStatus ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="tab-inside-box">
                        <?php if(!$clazz->is_closed && $loggedUser->isClazzAdmin($clazz)): ?>
                            <?= $this->Form->button(__('Alocar (0) docentes a turma'), [
                                'id' => 'alocate',
                                'class' => 'btn btn-sm btn-primary',
                                'disabled'
                            ]) ?>
                            <?= $this->Form->end() ?>
                        <?php endif; ?>

                        <?php if(!$clazz->is_closed && isset($loggedUser->teacher) && $loggedUser->teacher != null): ?>
                            <?php if($loggedUser->isSubscribed($clazz->id)): ?>
                                <?= $this->Form->postLink(
                                    '<i class="fa fa-close"></i> Cancelar inscrição',
                                    ['action' => 'unsubscribe', $clazz->id],
                                    [
                                        'title' => __('Cancelar inscrição'),
                                        'class' => 'pull-right btn btn-sm btn-danger',
                                        'data-toggle' => 'tooltip',
                                        'data-original-title' => __('Cancelar inscrição'),
                                        'escape' => false
                                    ]
                                ) ?>
                            <?php else: ?>
                                <?= $this->Form->postLink(
                                    '<i class="fa fa-hand-o-up"></i> Increver-se',
                                    ['action' => 'subscribe', $clazz->id],
                                    [
                                        'title' => __('Inscrever-se'),
                                        'class' => 'pull-right btn btn-sm btn-success',
                                        'data-toggle' => 'tooltip',
                                        'data-original-title' => __('Inscrever-se'),
                                        'escape' => false
                                    ]
                                ) ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="tab-pane" id="schedules" style="padding: 10px;">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    <?php $this->Html->scriptStart(['block' => true]); ?>
        var actionsDiv = $('.tab-inside-box');
        if(actionsDiv.html().trim() == '') {
            actionsDiv.hide();
        }

        $('input[name="selected_teachers[]"]').click(function(e) {
            var quantity = $('input[name="selected_teachers[]"]:checked').length;
            if (e.originalEvent.isTrusted == false) {
                e.preventDefault();
                quantity = $('input[name="selected_teachers[]"][checked]').length;
            }

            var isDisabled = !(quantity > 0);
            $('#alocate').prop("disabled", isDisabled).html("Alocar ("+quantity+") docentes a turma");
        }).first().trigger('click');

        $(document).ready(function() {
            $('#datatable').DataTable({
                <?php if(!$clazz->is_closed && $loggedUser->isClazzAdmin($clazz)): ?>
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
                <?php endif; ?>
                "order": [[ 1, "desc" ]],
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 10,
                "language": {
                    "zeroRecords": "<center>Essa turma não possui nenhuma inscrição</center>",
                    "info": "Página _PAGE_ de _PAGES_, total de _MAX_ incrições",
                    "infoEmpty": "",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Próxima"
                    }
                }
            });
        });
    <?php $this->Html->scriptEnd(); ?>
</script>
