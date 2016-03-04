<?php $this->assign('title', 'Processos'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li><?= $this->Html->link(__('Processos'), ['action' => 'index']) ?></li>
    <li class="active">Adicionar</li>
<?php $this->end(); ?>

<?= $this->Form->create($process) ?>
<div class="row">
    <div class="col-xs-8">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#processos" data-toggle="tab">Processos</a></li>
                <li><a href="#criterios" data-toggle="tab">Criterios</a></li>
            </ul>
            <div class="tab-content no-padding">
                <div class="tab-pane active" id="processos">
                    <div class="content">
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
                    </div>
                </div>
                <div class="tab-pane" id="criterios">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h4 class="box-title">Selecione e priorize</h4>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Opção</th>
                                            <th>Prioridade</th>
                                            <th>Descrição</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $this->Form->input('ativa_criterio_1', ['label' => false, 'type' => 'checkbox',
                                                'style' => 'padding-left: 20px; margin-left: 20px;']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $this->Form->input('prioridaade_criterio_1', ['label' => false, 'type' => 'number',
                                                    'size' => 2, 'maxlenght' => 2, 'min' => 1, 'max' => 7, 'value' => 1, 'style' => 'width: 70px;']);
                                                ?>
                                            </td>
                                            <td>Ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $this->Form->input('ativa_criterio_2', ['label' => false, 'type' => 'checkbox',
                                                    'style' => 'padding-left: 20px; margin-left: 20px;']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $this->Form->input('prioridaade_criterio_2', ['label' => false, 'type' => 'number',
                                                    'size' => 2, 'maxlenght' => 2, 'min' => 1, 'max' => 7, 'value' => 2, 'style' => 'width: 70px;']);
                                                ?>
                                            </td>
                                            <td>Ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $this->Form->input('ativa_criterio_3', ['label' => false, 'type' => 'checkbox',
                                                    'style' => 'padding-left: 20px; margin-left: 20px;']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $this->Form->input('prioridaade_criterio_3', ['label' => false, 'type' => 'number',
                                                    'size' => 2, 'maxlenght' => 2, 'min' => 1, 'max' => 7, 'value' => 3, 'style' => 'width: 70px;']);
                                                ?>
                                            </td>
                                            <td>Ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $this->Form->input('ativa_criterio_4', ['label' => false, 'type' => 'checkbox',
                                                    'style' => 'padding-left: 20px; margin-left: 20px;']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $this->Form->input('prioridaade_criterio_4', ['label' => false, 'type' => 'number',
                                                    'size' => 2, 'maxlenght' => 2, 'min' => 1, 'max' => 7, 'value' => 4, 'style' => 'width: 70px;']);
                                                ?>
                                            </td>
                                            <td>Ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $this->Form->input('ativa_criterio_5', ['label' => false, 'type' => 'checkbox',
                                                    'style' => 'padding-left: 20px; margin-left: 20px;']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $this->Form->input('prioridaade_criterio_5', ['label' => false, 'type' => 'number',
                                                    'size' => 2, 'maxlenght' => 2, 'min' => 1, 'max' => 7, 'value' => 5, 'style' => 'width: 70px;']);
                                                ?>
                                            </td>
                                            <td>Ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $this->Form->input('ativa_criterio_6', ['label' => false, 'type' => 'checkbox',
                                                    'style' => 'padding-left: 20px; margin-left: 20px;']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $this->Form->input('prioridaade_criterio_6', ['label' => false, 'type' => 'number',
                                                    'size' => 2, 'maxlenght' => 2, 'min' => 1, 'max' => 7, 'value' => 6, 'style' => 'width: 70px;']);
                                                ?>
                                            </td>
                                            <td>Ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $this->Form->input('ativa_criterio_7', ['label' => false, 'type' => 'checkbox',
                                                    'style' => 'padding-left: 20px; margin-left: 20px;']);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $this->Form->input('prioridaade_criterio_7', ['label' => false, 'type' => 'number',
                                                    'size' => 2, 'maxlenght' => 2, 'min' => 1, 'max' => 7, 'value' => 7, 'style' => 'width: 70px;']);
                                                ?>
                                            </td>
                                            <td>Ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
            <div class="box-footer clearfix">
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>

    </div>
</div>
<?= $this->Form->end() ?>
