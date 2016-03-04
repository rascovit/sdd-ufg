<?php $this->assign('title', 'Usuários'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li class="active">Lista de usuários</li>
<?php $this->end(); ?>

<div class="row">
  <div class="col-xs-12">
      <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Lista de usuários</h3>
            <div class="pull-right box-tools">
                <?= $this->Html->link(
                    '<i class="fa fa-plus-circle"></i> ' . __('Adicionar'),
                    ['action' => 'add'],
                    [
                        'escape' => false,
                        'data-toggle' => 'tooltip',
                        'data-original-title' => __('Adicionar'),
                        'class' => 'btn btn-sm btn-primary'
                    ]
                );
                ?>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
              <table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
                      <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                      <th><?= $this->Paginator->sort('login', __('Login')) ?></th>
                      <th><?= $this->Paginator->sort('email', __('Email')) ?></th>
                      <th><?= $this->Paginator->sort('name', __('Nome')) ?></th>
                      <th><?= $this->Paginator->sort('is_admin', __('Administrador')) ?></th>
                      <th width="200px"><?= __('Ações') ?></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php if($users->isEmpty()): ?>
                      <tr>
                          <td colspan="6" class="text-center">Não existe nenhum usuário cadastrado</td>
                      </tr>
                  <?php endif; ?>

                  <?php foreach ($users as $user): ?>
                      <tr>
                          <td><?= $this->Number->format($user->id) ?></td>
                          <td><?= $user->login ?></td>
                          <td><?= $user->email ?></td>
                          <td><?= $user->name ?></td>
                          <td><?= $user->is_admin ? __('Sim') : __('Não'); ?></td>
                          <td>
                              <?= $this->Html->link(
                                  '',
                                  ['action' => 'view', $user->id],
                                  [
                                      'title' => __('Visualizar'),
                                      'class' => 'btn btn-sm btn-default glyphicon glyphicon-search',
                                      'data-toggle' => 'tooltip',
                                      'data-original-title' => __('Visualizar'),
                                  ]
                              ) ?>
                              <?= $this->Html->link(
                                  '',
                                  ['action' => 'edit', $user->id],
                                  [
                                      'title' => __('Editar'),
                                      'class' => 'btn btn-sm btn-primary glyphicon glyphicon-pencil',
                                      'data-toggle' => 'tooltip',
                                      'data-original-title' => __('Editar'),
                                  ]
                              ) ?>
                              <?= $this->Form->postLink(
                                  '',
                                  ['action' => 'delete', $user->id],
                                  [
                                      'confirm' => __('Você tem certeza de que deseja remover o usuário "{0} - {1}"?', $user->id, $user->name),
                                      'title' => __('Remover'),
                                      'class' => 'btn btn-sm btn-danger glyphicon glyphicon-trash',
                                      'data-toggle' => 'tooltip',
                                      'data-original-title' => __('Remover'),
                                  ]
                              ) ?>
                          </td>
                      </tr>
                  <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
          <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
                  <?= $this->Paginator->prev('«') ?>
                  <?= $this->Paginator->numbers() ?>
                  <?= $this->Paginator->next('»') ?>
              </ul>
          </div>
      </div>
  </div>
</div>
