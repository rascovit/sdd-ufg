<?php $this->assign('title', 'Notificações'); ?>
<?php $this->start('breadcrumb'); ?>
    <li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
    <li class="active">Minhas notificações</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Minhas notificações</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-valign-middle">
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id', __('#ID')) ?></th>
                        <th><?= $this->Paginator->sort('description', __('Descrição')) ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($notifications->isEmpty()): ?>
                        <tr>
                            <td colspan="3" class="text-center">Não existe nenhuma notificação para você</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($notifications as $notification): ?>
                        <tr>
                            <td><?= $this->Number->format($notification->id) ?></td>
                            <td><?= h($notification->description) ?></td>
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
