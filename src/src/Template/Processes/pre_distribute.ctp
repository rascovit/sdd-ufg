<?php $this->assign('title', 'Simular Distribuição Automática'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link('<i class="fa fa-magic"></i>' . __('Distribuição automática'), ['controller' => 'processes', 'action' => 'distribute'], ['escape' => false]) ?></li>
    <li class="active">Distribuir</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Processos de distribuição "Em aberto":</h3>
                <h6>Abaixo estão listados somente os processos de distribuição em aberto no momento. Selecione um para simular a distribuição automática.</h6>
            </div>
            <div class="box-body">
	            <div class="form-group">
                    <table class="table table-striped table-second-hidden table-valign-middle">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                                <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
                                <th><?= $this->Paginator->sort('status', __('Status')) ?></th>
                                <th><?= __('Ação') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($openProcesses as $process): ?>
                                <tr>
                                    <td><?= $this->Number->format($process->id) ?></td>
                                    <td><?= h($process->name) ?></td>
                                    <td><?= h($process->status) ?></td>
                                    <td><?= $this->Html->link(
                                            '',
                                            ['action' => 'distribute', $process->id],
                                            [
                                                'title' => __('Ir para distribuição automática'),
                                                'class' => 'btn btn-sm btn-primary glyphicon glyphicon-play',
                                                'data-toggle' => 'tooltip',
                                                'data-original-title' => __('Ir para distribuição automática'),
                                            ]
                                        ) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
	            </div>
            </div>
        </div>
    </div>
</div>







