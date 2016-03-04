<?php $this->assign('title', 'Docentes'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Docentes'), ['action' => 'index']) ?></li>
<li class="active">Alocar Turmas para #<?= $teacher->id ?></li>
<?php $this->end(); ?>


<?php 
	use Cake\Routing\Router;
	$this->loadHelper('Utils');
	echo $this->Html->script('/plugins/jQuery/jQuery-2.1.4.min');	
?>

<div id="message" role="alert" class="alert alert-dismissible fade in alert-success" style="display: none;">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>.
</div>

<?= $this->Form->create('Filtros', array('action' => 'allocateClazzes/' . $teacher->id)) ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Alocar Turmas para docente #<?= $teacher->name ?></h3>
				<div class="pull-right box-tools">
					<?= $this->Html->link(
						'',
						['action' => 'edit', $teacher->id],
						[
							'title' => __('Ir para informações do docente'),
							'class' => 'btn btn-sm btn-default glyphicon glyphicon-education',
							'data-toggle' => 'tooltip',
							'data-original-title' => __('Cadastro do Docente'),
						]
					) ?>
				</div>
            </div>
            <div class="box-body">
				<?php if (count($processes) >= 1) { ?>
				<div class="row">
					<div class="col-xs-12" id="filters">
						<fieldset>
							<legend>Filtros</legend>
							<div class="row">
								<div class="col-xs-3">
									<?php 
										echo $this->Form->input('process', ['label' => 'Processo de Distr.', 'placeholder' => 'Processo', 'class' => 'col-xs-3', 'options' => $processes]);
									?>
								</div>
								<div class="col-xs-3">
									<?php 
										echo $this->Form->input('course_name', ['label' => 'Nome do Curso', 'placeholder' => 'Nome do Curso', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('knowledge_name', ['label' => 'Nome do Núcleo', 'placeholder' => 'Nome do Núcleo', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('subject_name', ['label' => 'Nome da Disciplina', 'placeholder' => 'Nome da Disciplina', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('local', ['label' => 'Local/Endereço', 'placeholder' => 'Local/Endereço', 'class' => 'col-xs-3']);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->input('week_day', ['label' => 'Dia da Semana', 'placeholder' => 'Dia da Semana', 'class' => 'col-xs-3', 'options' => $this->Utils->daysOfWeek()]);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->label('start_time');
										echo $this->Form->time('start_time'
										, ['format' => '24'
										, 'empty' => false
										, 'default' => '00:00',
										'hour' => [
											'class' => 'form-control',
										],
										'minute' => [
											'class' => 'form-control',
										]]);
									?>
								</div>
								<div class="col-xs-3">
									<?php
										echo $this->Form->label('end_time');
										echo $this->Form->time('end_time'
										, ['format' => '24'
										, 'empty' => false
										, 'default' => '23:59',
										'hour' => [
											'class' => 'form-control',
										],
										'minute' => [
											'class' => 'form-control',
										]]);
									?>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-xs-12 table-responsive">
						<fieldset>
							<legend>Turmas em Aberto</legend>
							<table class="table table-striped table-valign-middle">
								<thead>
								<tr>												
									<th><?= $this->Paginator->sort('clazz_id',__('#ID')) ?></th>
									<th><?= $this->Paginator->sort('clazze.subject.name',__('Disciplina')) ?></th>
									<th><?= $this->Paginator->sort('schedule.week_day', __('Dia da Semana')) ?></th>
									<th><?= $this->Paginator->sort('schedule.start_time', __('Horário Início')) ?></th>
									<th><?= $this->Paginator->sort('schedule.end_time', __('Horário Término')) ?></th>
									<th><?= $this->Paginator->sort('local.address', __('Local')) ?></th>
									<th><?= $this->Paginator->sort('clazze.subject.knowledge.name', __('Núcleo')) ?></th>
									<th><?= $this->Paginator->sort('clazze.subject.course.name', __('Curso')) ?></th>
									<th><?= $this->Paginator->sort('name', __('Name')) ?></th>
									<th width="200px"><?= __('Ações') ?></th>
								</tr>
								</thead>
								<tbody>
								<?php if(count($clazzes) < 1): ?>
									<tr>
										<td colspan="10" class="text-center">Ainda não existem turmas cadastradas no Processo</td>
									</tr>
								<?php endif; ?>
								<?php foreach ($clazzes as $clazz): ?>
									<tr>
										<td><?= h($clazz->id) ?></td>
										<td><?= h($clazz->subject->name) ?></td>
										<td>
										<?php 
											foreach ($clazz->scheduleLocals as $csl) {
												echo ($this->Utils->daysOfWeek()[$csl['week_day']]); ?>
												<br>
											<?php
											}
										?>
										</td>
										<td>
                                            <?php foreach ($clazz->scheduleLocals as $s): ?>
                                                <?= $s->schedule->start_time->format('H:i:s') ?>
                                                <br>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <?php foreach ($clazz->scheduleLocals as $s): ?>
                                                <?= $s->schedule->end_time->format('H:i:s') ?>
                                                <br>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <?php foreach ($clazz->scheduleLocals as $s): ?>
                                                <?= $s->local->fullPath ?>
                                                <br>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?= h($clazz->subject->knowledge->name) ?></td>
                                        <td><?= h($clazz->subject->course->name) ?></td>
                                        <td><?= h($clazz->name) ?></td>
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
											<?php 	$has_clazz = false;
													foreach ($clazz->intents as $c) : 
														if ($c->teacher_id == $teacher->id && $c->status == 'PENDING') { ?>
														<?= $this->Form->button('<i id="icon-' . $clazz->id . '" class="fa fa-remove"></i><i id="icon-loading-' . $clazz->id . '" class="fa fa-spinner fa-spin" style="display:none;"></i>'
															, array(
																'type' => 'button',
																'id' => 'button-' . $clazz->id,
																'class' => 'btn btn-sm btn-danger',
																'data-toggle' => 'tooltip',
																'title' => 'Cancelar interesse na disciplina',
																'onclick' => 'allocateClazz(' . $teacher->id . ', ' . $clazz->id . ', ' . '\'deallocate\'' . ')',
																)
														) ?>
														<div id="situation">
															Inscrito
														</div>
														<?php 
																$has_clazz = true;
																break; 
																
															} else if ($c->teacher_id == $teacher->id && $c->status == 'REJECTED') { ?>
																Rejeitado
														<?php 
																$has_clazz = true;
																break;
															} else if ($c->teacher_id == $teacher->id && $c->status == 'SELECTED') { ?>
																Selecionado
														<?php 
																$has_clazz = true;
																break;
															} ?>
														<?php endforeach; ?>
														<?php
														if (!$has_clazz) {
														?>
															<?= $this->Form->button('<i id="icon-' . $clazz->id . '" class="fa fa-check"></i><i id="icon-loading-' . $clazz->id . '" class="fa fa-spinner fa-spin" style="display:none;"></i>'
															, array(
																'type' => 'button',
																'id' => 'button-' . $clazz->id,
																'class' => 'btn btn-sm btn-success',
																'data-toggle' => 'tooltip',
																'title' => 'Registrar interesse na disciplina',
																'onclick' => 'allocateClazz(' . $teacher->id . ', ' . $clazz->id . ', ' . '\'allocate\'' . ')',
																)
														) ?>
															<div id="situation">
															Não Inscrito
														</div>
														<?php
														}
											?>
											
										</td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</fieldset>
					</div>
				</div>
				<?php } else { ?>
					Não existem Processos de Distribuição em Aberto.
				<?php } ?>
			</div>
        </div>
    </div> 
</div>
<?= $this->Form->end() ?>
<script>

$(document).ready(function() {
	
	var daysOfWeek = {'': 'Selecione o dia',
			2: 'Segunda-Feira',
			3: 'Terça-Feira',
			4: 'Quarta-Feira',
			5: 'Quinta-Feira',
			6: 'Sexta-Feira',
			7: 'Sábado',
			1: 'Domingo'};
	
	$('select[name="start_time[hour]"]').on('change', function() {
		if ($(this).val() > $('select[name="end_time[hour]"]').val()) {
			$('select[name="end_time[hour]"]').val($(this).val());
			
			if ($('select[name="end_time[minute]"]').val() < $('select[name="start_time[minute]"]').val()) {
				$('select[name="end_time[minute]"]').val($('select[name="start_time[minute]"]').val());
			}
		}
	});
	
	$('select[name="end_time[hour]"]').on('change', function() {
		if ($(this).val() < $('select[name="start_time[hour]"]').val()) {
			$(this).val($('select[name="end_time[hour]"]').val());
			
			if ($('select[name="end_time[minute]"]').val() < $('select[name="start_time[minute]"]').val()) {
				$('select[name="end_time[minute]"]').val($('select[name="start_time[minute]"]').val());
			}
		}
	});

	$('select[name="end_time[minute]"]').on('change', function() {
		if ($('select[name="end_time[hour]"]').val() <= $('select[name="start_time[hour]"]').val()) {
			if ($(this).val() < $('select[name="start_time[minute]"]').val()) {
				$(this).val($('select[name="start_time[minute]"]').val());
			}
		}
	});
	
	$('select[name="start_time[minute]"]').on('change', function() {
		if ($('select[name="end_time[hour]"]').val() <= $('select[name="start_time[hour]"]').val()) {
			if ($(this).val() > $('select[name="end_time[minute]"]').val()) {
				$('select[name="end_time[minute]"]').val($(this).val());
			}
		}
	});
	
	
	$('#filters input, #filters select').on('keyup keydown change', function() {
		$.ajax({
			type:"POST",
			url:"<?php echo Router::url(array('controller'=>'Teachers','action'=>'allocateClazzes'));?>/"+<?php echo $teacher->id; ?>,
			dataType: 'html',
			data: $('#filters input, #filters select'),
			success: function(tab){
				var data = JSON.parse(tab);
				
				data = $.map(data, function(value, index) {
					return [value];
				});

				
				var html = '';
				
				$("tbody").empty();
				
				if (data.length == 0) {
					$('tbody').append('<tr>'+
						'<td colspan="10" class="text-center">Não existem turmas cadastradas no Processo com os critérios informados</td>' +
					'</tr>');
				}
				
				for (var i = 0; i < data.length; i++) {					
					html += '<tr>' +
						'<td>' + data[i].id + '</td>' +
						'<td>' + data[i].subject.name + '</td>';
					html+= '<td>';
					for(var k = 0; k < data[i].scheduleLocals.length; k++) {
						html += daysOfWeek[data[i].scheduleLocals[k].week_day] + '<br>'; 
					}
					html+= '</td>';
					html+= '<td>';
					for(var k = 0; k < data[i].scheduleLocals.length; k++) {
						var date = new Date(data[i].scheduleLocals[k].schedule.start_time);
                        date.setTime(date.getTime() + date.getTimezoneOffset()*60*1000);
						html += date.toLocaleTimeString('pt-BR') + '<br>';
					}
					html+= '</td>';
					html+= '<td>';
					for(var k = 0; k < data[i].scheduleLocals.length; k++) {
						var date = new Date(data[i].scheduleLocals[k].schedule.end_time);
                        date.setTime(date.getTime() + date.getTimezoneOffset()*60*1000);
                        html += date.toLocaleTimeString('pt-BR') + '<br>';
					}
					html+= '</td>';
					html+= '<td>';
					for(var k = 0; k < data[i].scheduleLocals.length; k++) {
						html += data[i].scheduleLocals[k].local.name + ' - ' + data[i].scheduleLocals[k].local.address + '<br>';
					}
					html+= '</td>' +
						'<td>' + data[i].subject.knowledge.name + '</td>' +
						'<td>' + data[i].subject.course.name + '</td>' +
						'<td>' + data[i].name + '</td>';
					
					var has_clazz = false;
						
					for (var j = 0; j < data[i].intents.length; j++) {
						if (data[i].intents[j].teacher_id == <?php echo $teacher->id; ?> && data[i].intents[j].status == 'PENDING') {
			
							html += '<td><a href="/clazzes/view/' + data[i].id + '" title="" class="btn btn-sm btn-default glyphicon glyphicon-search" data-toggle="tooltip" data-original-title="Visualizar"></a>' +
							'<button type="button" id="button-' + data[i].id + '" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" onclick="allocateClazz(<?php echo $teacher->id; ?>, ' + data[i].id + ', \'deallocate\')" data-original-title="Cancelar interesse na disciplina"><i id="icon-' + data[i].id + '" class="fa fa-remove"></i><i id="icon-loading-' + data[i].id + '" class="fa fa-spinner fa-spin" style="display:none;"></i></button>' +
							'<div id="situation">Inscrito</div>';
							
							has_clazz = true;
							break;
						} else if (data[i].intents[j].teacher_id == <?php echo $teacher->id; ?> && data[i].intents[j].status == 'REJECTED') {
							html += '<td><a href="/clazzes/view/' + data[i].id + '" title="" class="btn btn-sm btn-default glyphicon glyphicon-search" data-toggle="tooltip" data-original-title="Visualizar"></a>Rejeitado';
							
							has_clazz = true;
							break;
						} else if (data[i].intents[j].teacher_id == <?php echo $teacher->id; ?> && data[i].intents[j].status == 'SELECTED') {
							html += '<td><a href="/clazzes/view/' + data[i].id + '" title="" class="btn btn-sm btn-default glyphicon glyphicon-search" data-toggle="tooltip" data-original-title="Visualizar"></a>Selecionado';
							
							has_clazz = true;
							break;
						}
					}
			
					if (!has_clazz) {
	
						html += '<td><a href="/clazzes/view/' + data[i].id + '" title="" class="btn btn-sm btn-default glyphicon glyphicon-search" data-toggle="tooltip" data-original-title="Visualizar"></a>' +
						'<button type="button" id="button-' + data[i].id + '" class="btn btn-sm btn-success" data-toggle="tooltip" title="Registrar interesse na disciplina" onclick="allocateClazz(<?php echo $teacher->id; ?>, ' + data[i].id + ', \'allocate\')" data-original-title="Registrar interesse na disciplina"><i id="icon-' + data[i].id + '" class="fa fa-check"></i><i id="icon-loading-' + data[i].id + '" class="fa fa-spinner fa-spin" style="display: none;"></i></button>' +
						'<div id="situation">Não Inscrito</div>';

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

function allocateClazz(teacher, clazz, allocate) {
	
	$('#icon-' + clazz).toggle();
	$('#icon-loading-' + clazz).toggle();
	
	$.ajax({
		type:"GET",
		url:"<?php echo Router::url(array('controller'=>'Teachers','action'=>'allocateClazzes'));?>/"+teacher+"/"+clazz+"/"+allocate,
		dataType: 'html',
		success: function(tab){
			if ($.trim(tab) == 'success') {
				$('#message').empty();
				$('#situation').empty();
				if (allocate == 'allocate') {
					$('#message').removeClass('alert-warning').removeClass('alert-error');
					$('#message').addClass('alert-success');
					$('#message').append('Interesse na disciplina registrado com sucesso!');
					$('#button-' + clazz).removeClass('btn-success').addClass('btn-danger');
					$('#button-' + clazz).attr('onclick', 'allocateClazz(' + teacher + ', ' + clazz + ', ' + '\'deallocate\'' + ')');
					$('#button-' + clazz).attr('title', 'Cancelar interesse na disciplina');
					$('#button-' + clazz).attr('data-original-title', 'Cancelar interesse na disciplina');
					$('#icon-' + clazz).removeClass('fa-check').addClass('fa-remove');
					$('#situation').append('Inscrito');
				} else {
					$('#message').removeClass('alert-success').removeClass('alert-error');
					$('#message').addClass('alert-warning');
					$('#message').append('Interesse na disciplina cancelado com sucesso!');
					$('#button-' + clazz).removeClass('btn-danger').addClass('btn-success');
					$('#button-' + clazz).attr('onclick', 'allocateClazz(' + teacher + ', ' + clazz + ', ' + '\'allocate\'' + ')');
					$('#button-' + clazz).attr('title', 'Registrar interesse na disciplina');
					$('#button-' + clazz).attr('data-original-title', 'Registrar interesse na disciplina');
					$('#icon-' + clazz).removeClass('fa-remove').addClass('fa-check');
					$('#situation').append('Não Inscrito');
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
			$('#icon-loading-' + clazz).hide();
			$('#icon-' + clazz).show();
	
		},
		error: function (tab) {
			alert('error');
		}
	});
}
</script>
