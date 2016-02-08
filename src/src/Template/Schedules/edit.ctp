<?php $this->assign('title', 'Horários de aula'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Horários de aula'), ['action' => 'index']) ?></li>
<li class="active">Editar #<?= $schedule->id ?></li>
<?php $this->end(); ?>

<?= $this->Form->create($schedule) ?>
<div class="row">
    <div class="col-xs-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar horário de aula #<?= $schedule->id ?></h3>
            </div>
            <div class="box-body">
              <?php
                  echo $this->Form->input('start_time', ['label' => 'Horário de início', 'placeholder' => 'Horário']);
                  echo $this->Form->input('end_time', ['label' => 'Horário de término', 'placeholder' => 'Horário']);
              ?>
            </div>
            <div class="box-footer clearfix">
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
