<p class="login-box-msg"><?= __('Solicitar cadastro') ?></p>

<?= $this->Form->create($user) ?>
    <?php
        echo $this->Form->input('login', ['label' => false, 'placeholder' => 'Nome de usuário']);
        echo $this->Form->input('email', ['label' => false, 'placeholder' => 'E-mail']);
        echo $this->Form->input('name', ['label' => false, 'placeholder' => 'Nome'] );
        echo $this->Form->input('password', ['label' => false, 'placeholder' => 'Senha']);
    ?>
    <span class="divider"></span>
    <?php
            /** Teacher fields */
            echo $this->Form->input('teacher.rg', ['label' => false, 'placeholder' => 'RG']);
            echo $this->Form->input('teacher.cpf', ['label' => false, 'placeholder' => 'CPF']);
            echo $this->Form->input('teacher.birth_date', [ 'label' => false, 'placeholder' => 'Data de Nascimento',
                'type' => 'text', 'class' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy']);
    ?>
    <span class="divider"></span>
    <?php
        echo $this->Form->input('teacher.registry', ['label' => false, 'placeholder' => 'Código da Matrícula']);
        echo $this->Form->input('teacher.entry_date', [ 'label' => false, 'placeholder' => 'Data de Ingresso',
            'type' => 'text', 'class' => 'datepicker', 'data-date-format' => 'dd/mm/yyyy']);
        echo $this->Form->input('teacher.workload', ['type' => 'number', 'min' => '1', 'label' => false, 'placeholder' => 'Carga horária de trabalho']);

        echo $this->Form->submit(__('Solicitar cadastro'), ['class' => 'btn btn-primary btn-block btn-flat']);
    ?>
    <?php $this->Html->scriptStart(['block' => true]); ?>
         $(document).ready(function() {
             $('.datepicker').datepicker({
                 language: 'pt-BR'
             });
         });
    <?php $this->Html->scriptEnd(); ?>
<?= $this->Form->end() ?>



