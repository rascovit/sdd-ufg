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
                <table class="table table-striped table-notification table-valign-middle">
                    <thead>
                    <tr>
                        <th width="30px"></th>
                        <th><?= __('Descrição') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($notifications->isEmpty()): ?>
                        <tr>
                            <td colspan="2" class="text-center">Não existe nenhuma notificação para você</td>
                        </tr>
                    <?php endif; ?>

                    <?php
                        foreach ($notifications as $notification):
                            switch($notification->type) {
                                case "ERROR":
                                    $classes = "fa-exclamation-circle text-red";
                                    break;
                                case "ALERT":
                                    $classes = "fa-warning text-yellow";
                                    break;
                                case "INFO":
                                default:
                                    $classes = "fa-info-circle text-blue";
                                    break;
                            }
                    ?>
                        <tr class="table-rowlink" data-href="<?= $this->Url->build(["controller" => "Notifications", "action" => "view", $notification->id]) ?>">
                            <td><i class="fa <?= $notification->is_read ? 'fa-check-square-o' : 'fa-envelope-o' ?>"></i></td>
                            <td><i class="fa <?= $classes ?>"></i> <?= h($notification->description) ?></td>
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

<script>
    <?php $this->Html->scriptStart(['block' => true]); ?>
    $(document).ready(function() {
        $('.table-rowlink[data-href]').each(function () {
            var $this = $(this);
            $this.on("click", function () {
                window.location = $this.attr('data-href');
            });
        });
    });
    <?php $this->Html->scriptEnd(); ?>
</script>

