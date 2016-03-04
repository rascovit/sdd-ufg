<section class="sidebar">
    <ul class="sidebar-menu">
        <li class="header"><?= __('MENU PRINCIPAL') ?></li>
        <li>
            <?= $this->Html->link('<i class="fa fa-dashboard"></i> <span>' . __('Dashboard') . '</span>', '/', ['escape' => false]) ?>
        </li>
        <li class="treeview">
            <a href="#"><i class="fa fa-gavel"></i> <span><?= __('Distribuição de disciplinas') ?></span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Processos em aberto'), ['controller' => 'processes', 'action' => 'index'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Turmas em aberto'), ['controller' => 'clazzes', 'action' => 'index', '?' => ['status' => 'OPENED']], ['escape' => false]) ?></li>
				<li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Minhas inscrições'), ['controller' => 'clazzes', 'action' => 'index', '?' => ['teachers' => [$loggedUser->teacher->id]]], ['escape' => false]) ?></li>
            </ul>
        </li>
        <?php if($loggedUser !== false && $loggedUser->canAdmin()): ?>
        <li class="treeview">
            <a href="#"><i class="fa fa-gear"></i> <span><?= __('Administração') ?></span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Núcleos de conhecimento'), ['controller' => 'knowledges'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Cursos'), ['controller' => 'courses'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Disciplinas'), ['controller' => 'subjects'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Docentes'), ['controller' => 'teachers'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Usuários'), ['controller' => 'users'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Locais de aula'), ['controller' => 'locals'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Horários de aula'), ['controller' => 'schedules'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Processos de distribuição'), ['controller' => 'processes'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Turmas'), ['controller' => 'clazzes'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Critérios / Restrições'), ['controller' => 'ProcessConfigurations'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Papéis de docentes'), ['controller' => 'roles'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Distribuição automática'), ['controller' => 'processes', 'action' => 'distribute'], ['escape' => false]) ?></li>
                <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> ' . __('Reverter distribuição'), ['controller' => 'processes', 'action' => 'revert'], ['escape' => false]) ?></li>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
</section>
