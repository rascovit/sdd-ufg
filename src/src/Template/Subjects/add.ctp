<?php $this->assign('title', 'Disciplinas'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link(__('Disciplinas'), ['action' => 'index']) ?></li>
    <li class="active">Adicionar</li>
<?php $this->end(); ?>

<?= $this->Form->create($subject) ?>
<div class="row">
    <div class="col-xs-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Adicionar disciplina</h3>
            </div>
            <div class="box-body">
                <?php
                    echo $this->Form->input('name', ['label' => 'Nome da disciplina', 'placeholder' => 'Nome da disciplina']);
                    echo $this->Form->input('theoretical_workload', ['label' => 'Carga horária teórica', 'placeholder' => 'Carga horária teórica', 'min' => 0]);
                    echo $this->Form->input('practical_workload', ['label' => 'Carga horária prática', 'placeholder' => 'Carga horária prática', 'min' => 0]);
                    echo $this->Form->input('course_id', ['label' => 'Curso', 'options' => $courses]);
                    echo $this->Form->input('knowledge_id', ['label' => 'Núcleo de conhecimento', 'options' => $knowledges]);
                ?>
            </div>
            <div class="box-footer clearfix">
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
