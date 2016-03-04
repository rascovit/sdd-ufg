<?php $this->Form->create('Produto', array('type' => 'file')); ?>
<?php $this->assign('title', 'Turmas'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li class="active">Finalizaçao de Turma</li>
<?php $this->end(); ?>

<?= $this->Form->create('Clazzes', ['action' => 'finishClazze/' . $clazze->id, 'type' => 'file'
	, 'onsubmit' => "return confirm(\"Ao confirmar todos os arquivos atualmente na pasta (se houverem) serão apagados. Deseja prosseguir com a operação? \");"]) ?>
<div class="row">
	<div class="col-xs-8">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Finalizaçao de Turma - Turma <?php echo $clazze->id . ' - ' . $clazze->name ?></h3>
			</div>
			 <div class="box-body">
				<div class="panel box box-info">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
						<i class="fa fa-question-circle" style="color:lightblue"></i>&nbsp;Informações Importantes!
					</a>
					<div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
						<div class="box-body">
							<p>*Os nomes dos arquivos não poderão conter mais de 225 caracteres e nem possuir os seguintes 
							caracteres: ^,~,´,`,/,\,',". Além disso, todos os campos deverão ser preenchidos com um arquivo 
							para upload. </p> 
							<p>*Se já existirem arquivos na pasta da turma, estes serão excluídos para dar lugar aos novos em 
							caso de nova confirmação da finalização de turma. </p> 
							<p>*Não insira arquivos de mesmo nome e extensão. Nesse caso, somente será salvo um dos arquivos, 
							e os documentos da turma estarão incompletos. </p> 
							<p>*O upload dos três arquivos deve ser feito no mesmo momento, pois arquivos remanescentes na pasta 
							serão sempre excluídos em caso de novos uploads, para economia de espaço.</p> 
							<p>*Todos os arquivos deverão estar em formato PDF.</p> 
						</div>
					</div>
				</div>
				
				<?php

				echo $this->Form->input('planoDeEnsino', array(
						'type' => 'file'
					));
					
				echo $this->Form->input('diarioDaTurma', array(
						'type' => 'file'
					));

				echo $this->Form->input('listaDeNotas', array(
						'type' => 'file'
					));
                ?>
			</div>
			
			<div class="box-footer clearfix">
                <?= $this->Form->button(__('Finalizar Turma'), ['class' => 'btn btn-primary']) ?>
            </div>
		</div>
	</div>
	<?= $this->Form->end() ?>
	<div class="col-xs-4">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Relação de Arquivos da Turma</h3>
			</div>
			<div class="box-body">
				<?php if (count($files) >= 1) { ?>
					<p class="box-title">*Clique nos links para abrir os arquivos</p>
					<?php
						foreach($files as $f) {
							echo '- ' . $this->Html->link($f, '/finishedClazzes/clazz-' . $clazze->id . DS . $f,
								['target' => '_blank']);
							?>
							<br> 
							<?php
						}
					?>
				<?php } else { ?>
				<p class="box-title">A pasta da turma está vazia.</p>
				<?php }?>
			</div>
		</div>
	</div>
</div>
