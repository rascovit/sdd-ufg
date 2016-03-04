<div class="box-body table-responsive no-padding">
    <table class="table table-striped table-valign-middle">
        <thead>
        <tr>
            <th><?= $this->Paginator->sort('codDisciplina', __('#CodDisciplina')) ?></th>
            <th><?= $this->Paginator->sort('disciplina', __('Disciplina')) ?></th>
            <th><?= $this->Paginator->sort('matricula', __('Matrícula')) ?></th>
            <th><?= $this->Paginator->sort('docente', __('Docente')) ?></th>
            <th><?= $this->Paginator->sort('local', __('Local')) ?></th>
            <th><?= $this->Paginator->sort('codHorario', __('#CodHorario')) ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($allocatedAndNonConflictingClazzes as $clazzId => $clazzInfo): ?>
            <tr>
                <td><?= $this->Number->format($clazzId) ?></td>
                <td><?= h($clazzInfo['subjectName']) ?></td>
                <td><?= h($clazzInfo['teacherRegistry']) ?></td>
                <td><?= h($clazzInfo['userName']) ?></td>
                <td><?= h($clazzInfo['locals']) ?></td>
                <td><?= h($clazzInfo['schedules']) ?></td>
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
