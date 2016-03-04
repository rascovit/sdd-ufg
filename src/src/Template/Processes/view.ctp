<?php $this->assign('title', 'Processo: '.$process->name); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link(__('Processos'), ['action' => 'index']) ?></li>
    <li class="active"><?= $process->name ?></li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Informações da processo</h3>
                <div class="pull-right box-tools">
					<?php if($loggedUser !== false && $loggedUser->canAdmin()): ?>
                    <?php if($process->status != 'CANCELLED'): ?>
                        <?= $this->Html->link(
                            __('Editar'),
                            ['action' => 'edit', $process->id],
                            [
                                'data-toggle' => 'tooltip',
                                'data-original-title' => __('Editar'),
                                'class' => 'btn btn-sm btn-primary'
                            ]
                        );
                        ?>
                    <?php endif; ?>
					<?php if($process->status == 'CLOSED'): ?>
                    <?= $this->Html->link(
                        __('Clonar'),
                        ['action' => 'reuseProcess', $process->id],
                        [
                            'data-toggle' => 'tooltip',
                            'data-original-title' => __('Clonar'),
                            'class' => 'btn btn-sm btn-primary'
                        ]
                    );
                    ?>
					<?php endif; ?>
					<?php endif; ?>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <tr>
                        <th><?= __('#ID') ?></th>
                        <td><?= $this->Number->format($process->id) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Descrição') ?></th>
                        <td><?= h($process->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Distribuição docentes efetivos') ?></th>
                        <td><?= h($process->initial_date->i18nFormat('dd/MM/yyyy')) ?><?= __('  até  ') ?><?= h($process->teacher_intent_date->i18nFormat('dd/MM/yyyy')) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Distribuição docentes substitutos') ?></th>
                        <td><?= h($process->primary_distribution_date->i18nFormat('dd/MM/yyyy')) ?><?= __('  até  ') ?><?= h($process->substitute_intent_date->i18nFormat('dd/MM/yyyy')) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Resolução de conflitos') ?></th>
                        <td><?= h($process->secondary_distribution_date->i18nFormat('dd/MM/yyyy')) ?><?= __('  até  ') ?><?= h($process->final_date->i18nFormat('dd/MM/yyyy')) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Situação') ?></th>
                        <td><?= $process->display_status ?></td>
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
                <h3 class="box-title">Turmas do processo</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th><?= __('#ID') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Vagas') ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($process->clazzes) < 1): ?>
                            <tr>
                                <td colspan="6" class="text-center">Esse processo não possui nenhuma turma associada</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($process->clazzes as $clazz): ?>
                            <tr>
                                <td><?= h($clazz->id) ?></td>
                                <td><?= h($clazz->name) ?></td>
                                <td><?= h($clazz->vacancies) ?></td>
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
