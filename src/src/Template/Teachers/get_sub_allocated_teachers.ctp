<?php

$this->assign('title', 'Docentes'); ?>
<?php $this->start('breadcrumb'); ?>
<li><?= $this->Html->link('<i class="fa fa-dashboard"></i>' . __('Dashboard'), '/', ['escape' => false]) ?></li>
<li class="active">Lista de docentes sub alocados</li>
<?php $this->end(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Lista de docentes sub alocados</h3>
                <form class="form-inline">
                    <div class="form-group">
                        <label for="process">Selecione um processo</label>
                        <select name="process" class="form-control">
                            <?php
                                foreach ($processes as $p){
                                    ?>
                            <option value="<?php echo $p["Processes__id"]; ?>" <?php if($precess_selected){ echo "selected";}?>><?php echo $p["Processes__name"]; ?></option>
                            <?php 
                                }
                            ?>
                        </select>                    </div>
                    <button type="submit" class="btn btn-default">Buscar</button>
                </form>

                <hr/>
                <?php 
                if($precess_selected){
                    foreach ($teachers as $t){
                        
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Professor</th>
                            <th>C. Horária</th>
                            <th>C. Horária alocada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $t['name']; ?></td>
                            <td><?php echo $t['workload']; ?></td>
                            <td><?php echo $t['subject_workload']; ?></td>
                        </tr>
                </table>
                <?php
                }
                }
                    ?>
            </div>
        </div>
    </div>
</div>