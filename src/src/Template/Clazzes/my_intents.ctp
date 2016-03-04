<?php $this->assign('title', 'Turmas'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Turmas de Interesse'), ['action' => 'index']) ?></li>
<li class="active">Minhas Inscrições em Turmas</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Minhas Inscrições em Turmas</h3>
            </div>
            <div class="box-body">
				<table class="table table-striped table-valign-middle">
					<thead>
						<tr>												
							<th><?= $this->Paginator->sort('clazzesTeachers.clazze.id',__('#ID')) ?></th>
							<th><?= $this->Paginator->sort('clazzesTeachers.clazze.name', __('Name')) ?></th>
							<th><?= $this->Paginator->sort('clazzesTeachers.clazze.subject.name',__('Disciplina')) ?></th>
							<th><?= $this->Paginator->sort('clazzesTeachers.status', __('Status')) ?></th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($clazzes) < 1): ?>
						<tr>
							<td colspan="4" class="text-center">Você não possui nenhuma inscrição.</td>
						</tr>
					<?php endif; ?>
					<?php foreach ($clazzes as $clazz): ?>
						<tr>
							<td><?= h($clazz->clazz_id) ?></td>
							<td><?= h($clazz['clazze']['name']) ?></td>
							<td><?= h($clazz['clazze']['subject']['name']) ?></td>
							<td><?= $clazz->displayStatus ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<div class="box-footer clearfix">
					<?= $this->Html->link(
							'Registrar/Cancelar interesses em disciplinas/turmas',
							['controller' => 'Teachers', 'action' => 'allocateClazzes', $teacherId],
							[
								'title' => __('Ir para registro de interesses em disciplinas.'),
								'class' => 'btn btn-primary',
								'data-toggle' => 'tooltip',
								'data-original-title' => __('Ir para registro de interesses em disciplinas.'),
							]
						) ?>
				</div>
			</div>
        </div>
    </div> 
</div>
<?= $this->Form->end() ?>