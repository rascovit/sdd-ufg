<?php $this->assign('title', 'Papéis'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link(__('Lista de Papéis'), ['action' => 'index']) ?></li>
    <li class="active">Adicionar</li>
<?php $this->end(); ?>

<?= $this->Form->create($role) ?>
    <div class="row">
        <div class="col-xs-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Adicionar papel</h3>
                </div>
                <div class="box-body">
                    <?php
                        echo $this->Form->input('type', ['id' => 'roleType', 'label' => 'Tipo de papel', 'options' => $roleTypes]);
                        echo $this->Form->input('teacher_id', ['label' => 'Docente', 'class' => 'select2 search__field', 'options' => $teachers]);
                    ?>
                    <?php
                        echo $this->Form->input('knowledge_id', ['id' => 'knowledge', 'label' => 'Núcleo de conhecimento', 'options' => $knowledges]);
                    ?>
                </div>
                <div class="box-footer clearfix">
                    <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->Html->scriptStart(['block' => true]); ?>
        $(document).ready(function(){
            $("#roleType").change(function(){
                if ($("#roleType").val() == 'FACILITATOR') {
                    $("#knowledge").parent().show();
                } else {
                    $("#knowledge").parent().hide();
                }
            }).trigger("change");

        });
    <?php $this->Html->scriptEnd(); ?>
<?= $this->Form->end() ?>
