<?php $this->assign('title', 'Processos'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link(__('Processos'), ['action' => 'index']) ?></li>
    <li class="active">Adicionar</li>
<?php $this->end(); ?>

<?= $this->Form->create($process) ?>
<div class="row">
    <div class="col-xs-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Adicionar processo</h3>
            </div>
            <div class="box-body">
                <?php
                    echo $this->Form->input('name', ['label' => 'Descrição do processo', 'placeholder' => 'Descrição do processo']);
                ?>
                <div class="row">
                    <div class="col-xs-6">
                        <?php
                            echo $this->Form->input('initial_date', [ 'label' => 'Distribuição docentes efetivos', ]);
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <?php
                            echo $this->Form->input('teacher_intent_date', [ 'label' => 'até', ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?php
                            echo $this->Form->input('primary_distribution_date', ['label' => 'Distribuição docentes substitutos', ]);
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <?php
                            echo $this->Form->input('substitute_intent_date', ['label' => 'até', ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <?php
                            echo $this->Form->input('secondary_distribution_date', ['label' => 'Resolução de conflitos', ]);
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <?php
                            echo $this->Form->input('final_date', ['label' => 'até', ]);
                        ?>
                    </div>
                </div>
                <?php
                    echo $this->Form->input('processConfigurations', ['label' => 'Configs. do Processo', 'multiple' => true]);
                ?>
            </div>
            <div class="box-footer clearfix">
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
