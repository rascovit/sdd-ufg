<?php $this->assign('title', 'Turmas'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Turmas'), ['action' => 'index']) ?></li>
<li class="active">Alocar Docente para ministrar turma #<?= $clazz->id ?></li>
<?php $this->end(); ?>


<?php
	use Cake\Routing\Router;
	$this->loadHelper('Utils');
	echo $this->Html->script('/plugins/jQuery/jQuery-2.1.4.min');
?>

<div id="message" role="alert" class="alert alert-dismissible fade in alert-success" style="display: none;">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>.
</div>

<?= $this->Form->create('Filtros', array('action' => 'allocateTeacher/' . $clazz->id)) ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Alocar Docente para ministrar turma #<?= $clazz->name ?></h3>
				<div class="pull-right box-tools">
					<?= $this->Html->link(
						'',
						['action' => 'listOpenedClazzes'],
						[
							'title' => __('Ir para turmas em aberto'),
							'class' => 'btn btn-sm btn-default glyphicon glyphicon-book',
							'data-toggle' => 'tooltip',
							'data-original-title' => __('Ir para turmas em aberto'),
						]
					) ?>
				</div>
            </div>
            <div class="box-body">
				<div class="row">
					<div class="col-xs-12" id="filters">
						<fieldset>
							<legend>Filtros</legend>
							<div class="row">
								<?php
									echo $this->Form->hidden('clazz_id', array('value' => $clazz->id));
								?>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('name', ['label' => 'Nome do Docente', 'placeholder' => 'Nome do Docente', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('registry', ['label' => 'Matrícula', 'placeholder' => 'Matrícula', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('workload', ['label' => 'Carga Horária', 'placeholder' => 'Carga Horária', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('formation', ['label' => 'Formação', 'placeholder' => 'Formação', 'class' => 'col-xs-3']);
									?>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('situation', ['label' => 'Situação', 'placeholder' => 'Situação', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('knowledge', ['label' => 'Núcleo de Conhecimento', 'placeholder' => 'Núcleo de Conhecimento', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-6">
									<br>
									<label for="only_knowledge">Somente docentes com interesse no NÚCLEO da turma/disciplina?&nbsp;
									<?php
										echo $this->Form->radio('only_knowledge', [
											['value' => '1', 'text' => 'Sim'],
											['value' => '0', 'text' => 'Não', 'checked' => true],

										], ['hiddenField' => false]);
									?>
									</label>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-xs-12">
									<label for="only_clazz">Somente docentes com interesse na turma/disciplina?&nbsp;
									<?php
										echo $this->Form->radio('only_clazz', [
											['value' => '1', 'text' => 'Sim'],
											['value' => '0', 'text' => 'Não', 'checked' => true],

										], ['hiddenField' => false]);
									?>
									</label>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-xs-12 table-responsive">
						<fieldset>
							<legend>Docentes</legend>
							<table class="table table-striped table-valign-middle">
								<thead>
								<tr>
									<th><?= $this->Paginator->sort('id',__('#ID')) ?></th>
									<th><?= $this->Paginator->sort('name',__('Nome')) ?></th>
									<th><?= $this->Paginator->sort('registry',__('Matrícula')) ?></th>
									<th><?= $this->Paginator->sort('workload',__('Carga Horária')) ?></th>
									<th><?= $this->Paginator->sort('formation',__('Formação')) ?></th>
									<th><?= $this->Paginator->sort('situation',__('Situação')) ?></th>
									<th><?= $this->Paginator->sort('knowledges',__('Núcleos de Conhecimento')) ?></th>
									<th width="200px"><?= __('Ações') ?></th>
								</tr>
								</thead>
								<tbody>
								<?php if(count($teachers) < 1): ?>
									<tr>
										<td colspan="10" class="text-center">Ainda não existem docentes cadastrados.</td>
									</tr>
								<?php endif; ?>
								<?php foreach ($teachers as $teacher): ?>
									<tr style="<?php echo ($teacher->id == $recomendedTeacher) ? 'background-color:lightblue;' : ''; ?>">
										<td><?= h($teacher->id) ?></td>
										<td><?= h(($teacher->id == $recomendedTeacher) ? $teacher->user->name . ' (Recomendado)' : $teacher->user->name) ?></td>
										<td><?= h($teacher->registry) ?></td>
										<td><?= h($teacher->workload) ?></td>
										<td><?= h($teacher->formation) ?></td>
										<td><?= h($teacher->situation) ?></td>
										<td>
										<?php
											foreach ($teacher->knowledges as $k) {
												echo $k->name; ?>
												<br>
											<?php
											}
										?>
										</td>
										<td>
											<?= $this->Html->link(
												'',
												['controller' => 'Teachers', 'action' => 'view', $teacher->id],
												[
													'title' => __('Visualizar'),
													'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
													'data-toggle' => 'tooltip',
													'data-original-title' => __('Visualizar'),
												]
											) ?>
											<?php 	$has_clazz = false;
													foreach ($clazzesTeachers as $c) :
														if ($teacher->id == $c->teacher_id && $c->status == 'SELECTED') { ?>

														<?= $this->Form->button('<i id="icon-' . $teacher->id . '" class="fa fa-remove"></i><i id="icon-loading-' . $teacher->id . '" class="fa fa-spinner fa-spin" style="display:none;"></i>'
															, array(
																'type' => 'button',
																'id' => 'button-' . $teacher->id,
																'class' => 'btn btn-sm btn-danger',
																'data-toggle' => 'tooltip',
																'title' => 'Cancelar inscricao do docente para ministrar Turma',
																'onclick' => 'allocateTeacher(' . $clazz->id . ', ' . $teacher->id . ', ' . '\'deallocate\'' .  ')',
																)
														) ?>

														<?php
																$has_clazz = true;
																break;
															} ?>
														<?php endforeach; ?>
														<?php
														if (!$has_clazz) {
														?>
															<?= $this->Form->button('<i id="icon-' . $teacher->id . '" class="fa fa-check"></i><i id="icon-loading-' . $teacher->id . '" class="fa fa-spinner fa-spin" style="display:none;"></i>'
															, array(
																'type' => 'button',
																'id' => 'button-' . $teacher->id,
																'class' => 'btn btn-sm btn-success',
																'data-toggle' => 'tooltip',
																'title' => 'Alocar docente para ministrar Turma',
																'onclick' => 'allocateTeacher(' . $clazz->id . ', ' . $teacher->id . ', ' . '\'allocate\'' . ')',
																)
														) ?>

														<?php
														}
											?>
											<div id="situation-<?php echo $teacher->id; ?>">
												<?php
													if ($has_clazz) {
														echo 'Ministrando';
													} else {
														echo '';
													}
												?>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</fieldset>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
<script>

$(document).ready(function() {
	$('#filters input, #filters select, #filters radio').on('keyup keydown change', function() {
		$.ajax({
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Clazzes','action'=>'allocateTeacher'));?>/"+<?php echo $clazz->id; ?>,
			dataType: 'html',
			data: $('#filters input:text, #filters input:hidden, #filters select, #filters input:radio:checked'),
			success: function(tab){
				var data = JSON.parse(tab);

				data = $.map(data, function(value, index) {
					return [value];
				});


				var html = '';

				$("tbody").empty();

				if (data.length == 0) {
					$('tbody').append('<tr>'+
						'<td colspan="10" class="text-center">Não existem docentes cadastrados com os critérios informados</td>' +
					'</tr>');
				}

				for (var i = 0; i < data.length; i++) {
					html += '<tr id="' + data[i].id + '">' +
						'<td>' + data[i].id + '</td>' +
						'<td>' + data[i].user.name + '</td>' +
						'<td>' + data[i].registry + '</td>' +
						'<td>' + data[i].workload + '</td>' +
						'<td>' + (data[i].formation == null ? '' : data[i].formation) + '</td>' +
						'<td>' + (data[i].situation == null ? '' : data[i].situation) + '</td>';

					html+= '<td>';
					for(var k = 0; k < data[i].knowledges.length; k++) {
						html += data[i].knowledges[k].name + '<br>';
					}

					var teacher_clazzes = <?php echo json_encode($clazzesTeachers); ?>;
					var has_clazz = false;

					for (var j = 0; j < teacher_clazzes.length; j++) {
						if (teacher_clazzes[j].teacher_id == data[i].id && teacher_clazzes[j].status == 'SELECTED') {

							html += '<td><a href="/teachers/view/' + data[i].id + '" title="" class="btn btn-sm btn-default glyphicon glyphicon-search" data-toggle="tooltip" data-original-title="Visualizar"></a>' +
							'<button type="button" id="button-' + data[i].id + '" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" onclick="allocateTeacher(<?php echo $clazz->id; ?>, ' + data[i].id + ', \'deallocate\')" data-original-title="Cancelar inscricao do docente para ministrar Turma"><i id="icon-' + data[i].id + '" class="fa fa-remove"></i><i id="icon-loading-' + data[i].id + '" class="fa fa-spinner fa-spin" style="display:none;"></i></button>' +
							'<div id="situation-' + data[i].id + '">Ministrando</div>';

							has_clazz = true;
							break;
						}
					}

					if (!has_clazz) {

						html += '<td><a href="/clazzes/view/' + data[i].id + '" title="" class="btn btn-sm btn-default glyphicon glyphicon-search" data-toggle="tooltip" data-original-title="Visualizar"></a>' +
						'<button type="button" id="button-' + data[i].id + '" class="btn btn-sm btn-success" data-toggle="tooltip" title="Alocar docente para ministrar Turma" onclick="allocateTeacher(<?php echo $clazz->id; ?>, ' + data[i].id + ', \'allocate\')" data-original-title="Alocar docente para ministrar a Turma"><i id="icon-' + data[i].id + '" class="fa fa-check" style="display: inline-block;"></i><i id="icon-loading-' + data[i].id + '" class="fa fa-spinner fa-spin" style="display: none;"></i></button>' +
						'<div id="situation-' + data[i].id + '"></div>';

					}
				}

					html += '</td></tr>';
					$('tbody').append(html);


			},
			error: function (tab) {
				alert('error');
			}
		});
	});

});

function allocateTeacher(clazz, teacher, allocate) {

	$('#icon-' + teacher).toggle();
	$('#icon-loading-' + teacher).toggle();

	$.ajax({
		type:"GET",
		url:"<?php echo Router::url(array('controller'=>'Clazzes','action'=>'allocateTeacher'));?>/"+clazz+"/"+teacher+"/"+allocate,
		dataType: 'html',
		success: function(tab){
			if ($.trim(tab) == 'success') {
				$('#message').empty();

				if (allocate == 'allocate') {

					$('#message').removeClass('alert-warning').removeClass('alert-error');
					$('#message').addClass('alert-success');
					$('#message').append('Docente alocado com sucesso!');
					$('#button-' + teacher).removeClass('btn-success').addClass('btn-danger');
					$('#button-' + teacher).attr('onclick', 'allocateTeacher(' + clazz + ', ' + teacher + ', ' + '\'deallocate\'' + ')');
					$('#button-' + teacher).attr('title', 'Cancelar inscricao do docente para ministrar Turma');
					$('#button-' + teacher).attr('data-original-title', 'Cancelar inscricao do docente para ministrar Turma');
					$('#icon-' + teacher).removeClass('fa-check').addClass('fa-remove');
					$('#situation-' + teacher).append('Ministrando');

				} else {

					$('#message').removeClass('alert-success').removeClass('alert-error');
					$('#message').addClass('alert-warning');
					$('#message').append('Docente desalocado com sucesso!');
					$('#button-' + teacher).removeClass('btn-danger').addClass('btn-success');
					$('#button-' + teacher).attr('onclick', 'allocateTeacher(' + clazz + ', ' + teacher + ', ' + '\'allocate\'' + ')');
					$('#button-' + teacher).attr('title', 'Alocar docente para ministrar a Turma');
					$('#button-' + teacher).attr('data-original-title', 'Alocar docente para ministrar a Turma');
					$('#icon-' + teacher).removeClass('fa-remove').addClass('fa-check');
					$('#situation-' + teacher).empty();
				}

			} else {

				$('#message').removeClass('alert-warning').removeClass('alert-success');
				$('#message').addClass('alert-error');
				$('#message').html('Ocorreu um erro ao tentar efetuar a operação. Tente novamente ou contate o administrador do sistema.');
			}

			if($('#message').css('display') == 'none')
			{
				$('#message').toggle();
			}
			$('#icon-loading-' + teacher).toggle();
			$('#icon-' + teacher).toggle();

		},
		error: function (tab) {
			alert('error');
		}
	});
}
</script>
