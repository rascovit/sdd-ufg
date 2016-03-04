<?php $this->assign('title', 'Docentes'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Docentes'), ['action' => 'index']) ?></li>
<li class="active">Editar #<?= $teacher->id ?></li>
<?php $this->end(); ?>

<?= $this->Form->create($teacher) ?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar docente #<?= $teacher->id ?></h3>
            </div>
            <div class="box-body">
				<div class="row">
                    <div class="col-xs-6">
                        <fieldset>
                            <legend>Geral</legend>
                            <?php
                                echo $this->Form->input('registry', ['label' => 'Matrícula', 'placeholder' => 'Matrícula']);
                                echo $this->Form->input('rg', ['label' => 'RG', 'placeholder' => 'RG']);
                                echo $this->Form->input('cpf', ['label' => 'CPF', 'placeholder' => 'CPF']);
                                echo $this->Form->input('birth_date', [ 'label' => 'Data de Nascimento', 'placeholder' => 'Data de Nascimento',
                                    'type' => 'text', 'class' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy']);
                                echo $this->Form->input('url_lattes', ['label' => 'URL Lattes', 'placeholder' => 'URL Lattes']);
                                echo $this->Form->input('entry_date', [ 'label' => 'Data de Ingresso', 'placeholder' => 'Data de Ingresso',
                                    'type' => 'text', 'class' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy']);
                                echo $this->Form->input('formation', ['label' => 'Formação', 'placeholder' => 'Formação']);
                                echo $this->Form->input('workload', ['label' => 'Carga Horária', 'placeholder' => 'Carga Horária']);
                                echo $this->Form->input('about', ['label' => 'Sobre', 'placeholder' => 'Sobre']);
                                echo $this->Form->input('situation', ['label' => 'Situação', 'placeholder' => 'Situação']);
                            ?>
                        </fieldset>
                    </div>
                    <div class="col-xs-6 nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#user" data-toggle="tab">Usuário</a></li>
                            <li><a href="#knowledges" data-toggle="tab">Núcleos de Conhecimento</a></li>
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="tab-pane table-responsive active" id="user">
                                <fieldset>
                                    <?php
                                        echo $this->Form->input('user.name', ['label' => 'Nome do docente', 'placeholder' => 'Nome da docente']);
                                        echo $this->Form->input('user.email', ['label' => 'Email', 'placeholder' => 'Email']);
                                        echo $this->Form->input('user.login', ['label' => 'Login', 'placeholder' => 'Nome de Usuário']);
                                        echo $this->Form->input('user.password', ['label' => 'Senha', 'placeholder' => 'Senha']);
                                    ?>
                                    <label for="is_admin">É administrador?&nbsp;
                                    <?php
                                        echo $this->Form->radio('user.is_admin', [
                                            ['value' => '1', 'text' => 'Sim'],
                                            ['value' => '0', 'text' => 'Não'],

                                        ], ['hiddenField' => false, 'label' => 'É Administrador', 'disabled' => true]);
                                    ?>
                                    </label>
                                </fieldset>
                            </div>
                            <div class="tab-pane" id="knowledges">
                                <table class="table table-striped table-valign-middle">
                                    <thead>
                                        <tr>
                                            <th><?= $this->Paginator->sort('name', __('#ID')) ?></th>
                                            <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
                                            <th><?= $this->Paginator->sort('name', __('Level')) ?></th>
                                            <th width="200px"><?= __('Ações') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php if(count($teacher->knowledges_teachers) < 1): ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Esse docente não possui nenhum núcleo de conhecimento associado</td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php foreach ($teacher->knowledges_teachers as $knowledgeTeacher): ?>
                                        <tr>
                                            <td><?= $this->Number->format($knowledgeTeacher->knowledge->id) ?></td>
                                            <td><?= h($knowledgeTeacher->knowledge->name) ?></td>
                                            <td><?= $this->Form->input('knowledgeTeacher.level[]', ['default' => $knowledgeTeacher->level, 'label' => false, 'style' => 'width:60px']); ?></td>
                                            <td>
                                                <?= $this->Html->link(
                                                    '',
                                                    ['action' => 'view', $knowledgeTeacher->knowledge->id],
                                                    [
                                                        'title' => __('Visualizar'),
                                                        'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
                                                        'data-toggle' => 'tooltip',
                                                        'data-original-title' => __('Visualizar'),
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
            </div>
            <div class="box-footer clearfix">
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
