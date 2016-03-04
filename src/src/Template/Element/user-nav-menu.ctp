<?php
    $notificationsCount = isset($loggedUser->latest_notifications[0]) ? $loggedUser->latest_notifications[0]->count : 0;
?>
<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <!-- Notifications Menu -->
        <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <?php if($notificationsCount > 0): ?>
                    <span class="label label-warning"><?= $notificationsCount > 99 ? '99+' : $notificationsCount ?></span>
                <?php endif; ?>
            </a>
            <ul class="dropdown-menu">
                <li class="header"><?= sprintf(__('Você possui %d notificações'), $notificationsCount) ?></li>
                <li>
                    <ul class="menu">
                        <?php if(count($loggedUser->latest_notifications) < 1): ?>
                        <li>
                            <a href="#">
                                <i class="fa fa-circle-o text-yellow"></i> <?= __('Nenhuma notificação pendente') ?>
                            </a>
                        </li>
                        <?php else: ?>
                        <?php
                            foreach($loggedUser->latest_notifications as $notification):
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
                        <li>
                            <?= $this->Html->link(
                                '<i class="fa '.$classes.'"></i> ' . $notification->description,
                                [
                                    'controller' => 'notifications',
                                    'action' => 'view',
                                    $notification->id
                                ],
                                [
                                    'escape' => false
                                ])
                            ?>
                        </li>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="footer">
                    <?= $this->Html->link(__('Visualizar todas'), ['controller' => 'notifications']) ?>
                </li>
            </ul>
        </li>

        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?= $this->Gravatar->generate(
                    $loggedUser->email,
                    [
                        'image-options' => ['class' => 'user-image'],
                        'size' => 160,
                        'default' => 'mm'
                    ]
                ) ?>
                <span class="hidden-xs"><?= $loggedUser->name ?></span>
            </a>
            <ul class="dropdown-menu">
                <li class="user-header">
                    <?= $this->Gravatar->generate(
                        $loggedUser->email,
                        [
                            'image-options' => ['class' => 'img-circle'],
                            'size' => 160,
                            'default' => 'mm'
                        ]
                    ) ?>
                    <p>
                        <?= $loggedUser->name ?>
                        <small><?= $loggedUser->title ?></small>
                    </p>
                </li>
                <li class="user-footer">
                    <div class="pull-left">
                        <?= $this->Html->link(__('Minha conta'), ['controller' => 'teachers', 'action' => 'view', $loggedUser->teacher->id ], ['class' => 'btn btn-default btn-flat']) ?>
                    </div>
                    <div class="pull-right">
                        <?= $this->Html->link(__('Sair'), ['controller' => 'users', 'action' => 'logout'], ['class' => 'btn btn-default btn-flat']) ?>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
</div>
