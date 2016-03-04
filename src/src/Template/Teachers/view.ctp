<?php $this->assign('title', 'Docentes'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li class="active">Lista de docentes</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12"> <!-- Tabela de informações do docente -->
        <div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Informações do Docente</h3>
				<div class="pull-right box-tools">
					<?= $this->Html->link(
						'<i class="fa "></i> ' . __('Editar'),
						['action' => 'edit', $teacher->id],
						[
							'escape' => false,
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
						<td><?= $this->Number->format($teacher->id) ?></td>
					</tr>
					<tr>
						<th><?= __('Registro') ?></th>
						<td><?= h($teacher->registry) ?></td>
					</tr>
					<tr>
						<th><?= __('Link do Lattes') ?></th>
						<td><?= h($teacher->url_lattes) ?></td>
					</tr>
					<tr>
						<th><?= __('Formação') ?></th>
						<td><?= h($teacher->formation) ?></td>
					</tr>
					<tr>
						<th><?= __('Sobre') ?></th>
						<td><?= h($teacher->about) ?></td>
					</tr>
					<tr>
						<th><?= __('RG') ?></th>
						<td><?= h($teacher->rg) ?></td>
					</tr>
					<tr>
						<th><?= __('CPF') ?></th>
						<td><?= h($teacher->cpf) ?></td>
					</tr>
					<tr>
						<th><?= __('Carga Horária') ?></th>
						<td><?= $this->Number->format($teacher->workload) ?></td>
					</tr>
					<tr>
						<th><?= __('Data de Ingresso') ?></th>
						<td><?= h($teacher->entry_date ?
                            $teacher->entry_date->i18nFormat('dd/MM/yyyy') : '') ?></td>
					</tr>
					<tr>
						<th><?= __('Data de Nascimento') ?></th>
						<td><?= h($teacher->birth_date ?
						    $teacher->birth_date->i18nFormat('dd/MM/yyyy') : '') ?></td>
					</tr>
				</table>
			</div>
		</div>
    </div>

	<div class="col-xs-12"> <!-- Tabela de usuário do docente -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Usuário do Docente</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                            <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
							<th><?= $this->Paginator->sort('email', __('Email')) ?></th>
                            <th><?= $this->Paginator->sort('login', __('Login')) ?></th>
							<th><?= $this->Paginator->sort('is_admin', __('Administrador?')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>

						<?php if(count($teacher->user) < 1): ?>
							<tr>
								<td colspan="7" class="text-center">Esse docente não possui nenhum usuário associado</td>
							</tr>
						<?php endif; ?>

						<tr>
							<td><?= $this->Number->format($teacher->user->id) ?></td>
							<td><?= h($teacher->user->name) ?></td>
							<td><?= h($teacher->user->email) ?></td>
							<td><?= h($teacher->user->login) ?></td>
							<td><?= h($teacher->user->is_admin ? 'Sim' : 'Não') ?></td>
							<td>
								<?= $this->Html->link(
									'',
									['action' => 'view', $teacher->user->id],
									[
										'title' => __('Visualizar'),
										'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
										'data-toggle' => 'tooltip',
										'data-original-title' => __('Visualizar'),
									]
								) ?>
								<?= $this->Html->link(
									'',
									['controller' => 'User','action' => 'edit', $teacher->user->id],
									[
										'title' => __('Editar'),
										'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
										'data-toggle' => 'tooltip',
										'data-original-title' => __('Editar'),
									]
								) ?>
							</td>
						</tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

	<div class="col-xs-12"> <!-- Tabela de papéis do docente -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Papéis do Docente</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                            <th><?= $this->Paginator->sort('type', __('Tipo')) ?></th>
							<th><?= $this->Paginator->sort('type', __('Núcleo de Conhecimento')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>

					<?php if(count($teacher->roles) < 1): ?>
						<tr>
							<td colspan="3" class="text-center">Esse docente não possui nenhum papel associado</td>
						</tr>
					<?php endif; ?>

					<?php foreach ($teacher->roles as $role): ?>
						<tr>
							<td><?= $this->Number->format($role->id) ?></td>
							<td><?= h($role->type == 'COORDINATOR'
                                ? 'Coordenador' : 'Facilitador') ?></td>
							<td><?= h($role->type == 'FACILITATOR'
								? $role->knowledge->name : '') ?></td>
							<td>
								<?= $this->Html->link(
									'',
									['action' => 'view', $role->id],
									[
										'title' => __('Visualizar'),
										'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
										'data-toggle' => 'tooltip',
										'data-original-title' => __('Visualizar'),
									]
								) ?>
								<?= $this->Html->link(
									'',
									['controller' => 'Roles','action' => 'edit', $role->id],
									[
										'title' => __('Editar'),
										'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
										'data-toggle' => 'tooltip',
										'data-original-title' => __('Editar'),
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

	<div class="col-xs-12"> <!-- Tabela de núcleos de conhecimento do docente -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Núcleos de Conhecimento do Docente</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                            <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>

					<?php if(count($teacher->knowledges) < 1): ?>
						<tr>
							<td colspan="3" class="text-center">Esse docente não possui nenhum núcleo de conhecimento associado</td>
						</tr>
					<?php endif; ?>

					<?php foreach ($teacher->knowledges as $knowledge): ?>
						<tr>
							<td><?= $this->Number->format($knowledge->id) ?></td>
							<td><?= h($knowledge->name) ?></td>
							<td>
								<?= $this->Html->link(
									'',
									['action' => 'view', $knowledge->id],
									[
										'title' => __('Visualizar'),
										'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
										'data-toggle' => 'tooltip',
										'data-original-title' => __('Visualizar'),
									]
								) ?>
								<?= $this->Html->link(
									'',
									['controller' => 'Knowledges','action' => 'edit', $knowledge->id],
									[
										'title' => __('Editar'),
										'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
										'data-toggle' => 'tooltip',
										'data-original-title' => __('Editar'),
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

	<div class="col-xs-12"> <!-- Tabela de turmas do docente -->
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Turmas do Docente</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                            <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
                            <th><?= $this->Paginator->sort('vacancies', __('Vagas')) ?></th>
                            <th><?= $this->Paginator->sort('formation', __('Local/Hora')) ?></th>
							<th><?= $this->Paginator->sort('process', __('Processo de distribuição')) ?></th>
                            <th width="200px"><?= __('Ações') ?></th>
                        </tr>
                    </thead>
                    <tbody>

					<?php if(count($teacher->clazzes) < 1): ?>
						<tr>
							<td colspan="6" class="text-center">Esse docente não possui nenhuma turma associada</td>
						</tr>
					<?php endif; ?>

					<?php foreach ($teacher->clazzes as $clazze): ?>
						<tr>
							<td><?= $this->Number->format($clazze->id) ?></td>
							<td><?= h($clazze->name) ?></td>
							<td><?= h($clazze->vacancies) ?></td>
							<td><?= h($clazze->local) ?></td>
							<td><?= h($clazze->process->name) ?></td>
							<td>
								<?= $this->Html->link(
									'',
									['action' => 'view', $clazze->id],
									[
										'title' => __('Visualizar'),
										'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
										'data-toggle' => 'tooltip',
										'data-original-title' => __('Visualizar'),
									]
								) ?>
								<?= $this->Html->link(
									'',
									['controller' => 'Clazzes','action' => 'edit', $clazze->id],
									[
										'title' => __('Editar'),
										'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
										'data-toggle' => 'tooltip',
										'data-original-title' => __('Editar'),
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
