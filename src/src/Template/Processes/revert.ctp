<?php $this->assign('title', 'Reverter Distribuição Automática'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link('<i class="fa fa-magic"></i>' . __('Distribuição automática'), ['controller' => 'processes', 'action' => 'distribute'], ['escape' => false]) ?></li>
    <li class="active">Reverter</li>
<?php $this->end(); ?>

<!--<h1>Simulated Intents:</h1>
<?= debug($simulatedIntents); ?>-->

<!-- RADIO -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Turmas e professores distribuídos automaticamente:</h3>
                <h6>Obs: O Processo de distribuição será revertido ao estágio anterior ao da efetivação da distribuição automática.</h6>
            </div>
            <div class="box-body">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('codDisciplina', __('#CodDisciplina')) ?></th>
                            <th><?= $this->Paginator->sort('disciplina', __('Disciplina')) ?></th>
                            <th><?= $this->Paginator->sort('matricula', __('Matrícula')) ?></th>
                            <th><?= $this->Paginator->sort('docente', __('Docente')) ?></th>
                            <th><?= $this->Paginator->sort('local', __('Local')) ?></th>
                            <th><?= $this->Paginator->sort('codHorario', __('#CodHorario')) ?></th>
                            <th><?= $this->Paginator->sort('status', __('Status')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($distributedClazzes as $clazzId => $clazzInfo): ?>
                        <tr>
                            <td><?= $this->Number->format($clazzInfo['clazzeId']) ?></td>
                            <td><?= h($clazzInfo['subjectName']) ?></td>
                            <td><?= h($clazzInfo['teacherRegistry']) ?></td>
                            <td><?= h($clazzInfo['userName']) ?></td>
                            <td><?= h($clazzInfo['locals']) ?></td>
                            <td><?= h($clazzInfo['schedules']) ?></td>
                            <td><?= h($clazzInfo['status']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>

<!-- BUTTONS -->
<div class="row">
	<div class="col-xs-12 text-center">
		<?= $this->Html->link(
            '<i class="fa fa-mail-reply-all"></i> ' . __('Reverter Distribuição Automática'),
            ['action' => 'effectivateRevert', $processId],
            [
                'escape' => false,
                'class' => 'btn btn-sm btn-primary'
            ]
        );
        ?>
	</div>
</div>







