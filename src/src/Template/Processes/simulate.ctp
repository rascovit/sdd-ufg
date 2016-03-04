<?php $this->assign('title', 'Distribuição automática - Simulação'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link('<i class="fa fa-magic"></i>' . __('Distribuição automática'), ['controller' => 'processes', 'action' => 'distribute'], ['escape' => false]) ?></li>
    <li class="active">Simulação</li>
<?php $this->end(); ?>

<!--<h1>Candidate teachers:</h1>
<?= debug($candidateTeachers); ?>
<h1>Selected teacherId:</h1>
<?= debug($selectedTeacherId); ?>
<h1>Priority:</h1>
<?= debug($priority); ?>
<h1>Workload:</h1>
<?= debug($workload); ?>
<h1>Level:</h1>
<?= debug($level); ?>
<h1>Oldest entry date:</h1>
<?= debug($oldestEntryDate); ?>
<h1>Recovered Clazzes:</h1>
<?= debug($recoveredClazzes); ?>
<h1>Teachers Current Workload:</h1>
<?= debug($teachersCurrentWorkload); ?>
<h1>Distributed clazzes:</h1>
<?= debug($distributedClazzes); ?>-->

<!-- FIRST TABLE -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Alocados manualmente [não sofreram distribuição automática]</h3>
            </div>
            <?php echo $this->element('allocated-and-non-conflicting-clazzes') ?>
        </div>
    </div>
</div>

<!-- SECOND TABLE -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Alocados dinamicamente [sofreram distribuição automática]</h3>
            </div>
            <div class="box-body table-responsive no-padding">
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

<!-- BUTTONS -->
<div class="row">
	<div class="col-xs-12 text-center">
		<?= $this->Html->link(
            '<i class="fa fa-times-circle"></i> ' . __('Cancelar'),
            ['action' => 'effectivateRevert', $processId],
            [
                'escape' => false,
                'class' => 'btn btn-sm btn-danger'
            ]
        );
        ?>
		<?= $this->Html->link(
            '<i class="fa  fa-check-circle"></i> ' . __('Efetivar Distribuição Automática'),
            ['action' => 'effectivateDistribution'],
            [
                'escape' => false,
                'class' => 'btn btn-sm btn-success'
            ]
        );
        ?>
	</div>
</div>
