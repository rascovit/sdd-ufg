<?php $this->assign('title', 'Horário de aula: '.$schedule->id); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Horários de aula'), ['action' => 'index']) ?></li>
<li class="active"><?= $schedule->id ?></li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Informações do horário de aula</h3>
                <div class="pull-right box-tools">
                    <?= $this->Html->link(
                        __('Editar'),
                        ['action' => 'edit', $schedule->id],
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
                        <td><?= $this->Number->format($schedule->id) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Horário de início') ?></th>
                        <td><?= $schedule->start_time_formated ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Horário de término') ?></th>
                        <td><?= $schedule->end_time_formated ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
