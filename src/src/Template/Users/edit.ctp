<?php $this->assign('title', 'Usuários'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li><?= $this->Html->link(__('Usuários'), ['action' => 'index']) ?></li>
<li class="active">Editar #<?= $user->id ?></li>
<?php $this->end(); ?>

<?= $this->Form->create($user) ?>
<div class="row">
    <div class="col-xs-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar usuário #<?= $user->id ?></h3>
            </div>
            <div class="box-body">
              <?php
                  echo $this->Form->input('login', ['label' => 'Login', 'placeholder' => 'Login']);
                  echo $this->Form->input('email', ['label' => 'Email', 'placeholder' => 'Email']);
                  echo $this->Form->input('name', ['label' => 'Nome', 'placeholder' => 'Nome']);
                  echo $this->Form->input('password', ['label' => 'Senha', 'placeholder' => 'Senha']);
                  echo $this->Form->input('is_admin', ['label' => 'Administrador']);
              ?>
            </div>
            <div class="box-footer clearfix">
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
