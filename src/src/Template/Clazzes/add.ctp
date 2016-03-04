<?php $this->assign('title', 'Turmas'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Turmas'), ['action' => 'index']) ?></li>
<li class="active">Adicionar</li>
<?php $this->end(); ?>

<?= $this->Form->create($clazz) ?>
<div class="row">
    <div class="col-sm-12 col-xs-12 col-lg-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Adicionar turma</h3>
            </div>
            <div class="box-body">
                <?php
                echo $this->Form->input('process_id', ['label' => 'Processo de distribuição', 'options' => $processes]);
                echo $this->Form->input('subject_id', ['label' => 'Disciplina', 'options' => $subjects]);
                echo $this->Form->input('name', ['label' => 'Nome da turma', 'placeholder' => 'Nome da turma']);
                echo $this->Form->input('vacancies', ['label' => 'Número de vagas', 'placeholder' => 'Número de vagas', 'min' => 0]);
                echo $this->Form->input('schedules', ['type' => 'hidden']);
                ?>

                <label for="ClazzesSchedulesLocals">Locais/Horários</label>
                <div class="content week-box">
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
                                <a href="javascript:void(0)" class="add-schedule-local"><i class="fa fa-fw fa-calendar-plus-o"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="box-footer clearfix">
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<!-- Modal -->
<div id="add-schedule-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Adicionar horário de aula</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger"></div>
                <?php
                    echo $this->Form->input('week_day', ['label' => 'Dia da semana', 'options' => $this->Utils->daysOfWeek(), 'disabled']);
                    echo $this->Form->input('schedule_id', ['label' => 'Horário da aula', 'options' => $schedules]);
                    echo $this->Form->input('local_id', ['label' => 'Local da aula', 'options' => $locals]);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="pull-left btn btn-success" id="save-schedule"><?= __('Salvar') ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancelar') ?></button>
            </div>
        </div>

    </div>
</div>

<script>
<?php $this->Html->scriptStart(['block' => true]); ?>
    $(document).ready(function() {
        $('i.remove-schedule').click(removeSchedule);

        var modal = $('#add-schedule-modal');
        var alertModal = modal.find('.alert');
        var formSchedulesVal = $('input[name=schedules]').val();
        var schedules = formSchedulesVal != "" ? JSON.parse(formSchedulesVal) : [];
        var schedulesIndex = 0;
        if(schedules.length > 0) {
            schedules.forEach(function(element, index) {
                if(element === null) {
                    element = [];
                    schedules[index] = [];
                }

                element.forEach(function(elem, i) {
                    if(elem.id > schedulesIndex) {
                        schedulesIndex = elem.id;
                    }

                    var scheduleId = modal.find('select[name=schedule_id] option[value='+elem.schedule_id+']');
                    var localId = modal.find('select[name=local_id] option[value='+elem.local_id+']');

                    var scheduleLabel = createScheduleLabel(elem.id, scheduleId.text(), localId.text());
                    $('.week-day[data-week-day='+elem.week_day+']').append(scheduleLabel);
                });
            });

            updateFormData();
        }

        $('.add-schedule-local').click(function() {
            var weekDay = $(this).parent().data('week-day');
            modal.find('select[name=week_day]').val(weekDay);
            modal.find('select[name=schedule_id]').val(0);
            modal.find('select[name=local_id]').val(0);
            alertModal.hide();
            modal.modal('show');
        });

        $('#save-schedule').click(function() {
            var weekDay = modal.find('select[name=week_day] :selected');
            var scheduleId = modal.find('select[name=schedule_id] :selected');
            var localId = modal.find('select[name=local_id] :selected');

            if(weekDay.val() < 1 || scheduleId.val() < 1 || localId.val() < 1) {
                alertModal.html('Selecione o local e o horário.').hide().fadeIn('800');
                return;
            }

            if(!Array.isArray(schedules[weekDay.val()])) {
                schedules[weekDay.val()] = [];
            }

            schedulesIndex++;
            schedules[weekDay.val()].push(
                {
                    id : schedulesIndex,
                    week_day : weekDay.val(),
                    schedule_id : scheduleId.val(),
                    local_id : localId.val()
                }
            );
            updateFormData();

            var scheduleLabel = createScheduleLabel(schedulesIndex, scheduleId.text(), localId.text());
            $('.week-day[data-week-day='+weekDay.val()+']').append(scheduleLabel);

            modal.modal('hide');
        });

        function createScheduleLabel(id, schedule, local) {
            var scheduleLocal = '<div data-schedule-id="'+id+'" class="label label-primary schedule-data-div">' +
                '<span class="text-ellipsis">' + schedule +': '+ local +'</span></div>';

            var removal = $('<i class="remove-schedule">×</i>').click(removeSchedule);
            return $(scheduleLocal).append(removal);
        }

        function removeSchedule() {
            var scheduleDiv = $(this).parent();
            var scheduleId = scheduleDiv.data('schedule-id');

            schedules.forEach(function(element, index) {
                var remove = false;
                element.forEach(function(elem, i) {
                    if(elem.id == scheduleId) {
                        remove = i;
                    }
                });

                if(remove !== false) {
                    element.splice(remove, 1);
                }
            });
            updateFormData();

            scheduleDiv.remove();
        }

        function updateFormData() {
            $('input[name=schedules]').val(JSON.stringify(schedules));
        }
    });
<?php $this->Html->scriptEnd(); ?>
</script>
