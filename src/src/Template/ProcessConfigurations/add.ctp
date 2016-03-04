<?php $this->assign('title', 'Config. Processos de distribuição'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li class="active">Config. Processos de distribuição</li>
    <li class="active">Adicionar</li>
<?php $this->end(); ?>


<?= $this->Form->create($processConfiguration) ?>
<div class="row">
    <div class="col-xs-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Adicionar Config</h3>
            </div>
            <div class="box-body">
                <?php
                    echo $this->Form->input('name', ['label' => 'Nome da config.', 'placeholder' => 'Nome da config.']);
                    echo $this->Form->input('description', ['label' => 'Descrição', 'placeholder' => 'Descrição']);
                    echo $this->Form->input('value', ['label' => 'Valor', 'placeholder' => 'Valor']);
                    echo $this->Form->input('data_type', ['label' => 'Tipo de dado', 'placeholder' => 'Tipo de dado']);
                    echo $this->Form->input('type', ['label' => 'Tipo', 'placeholder' => 'Tipo']);
                ?>
            </div>
            <div class="box-footer clearfix">
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
