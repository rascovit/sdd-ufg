<?php
use Migrations\AbstractMigration;

class CreateProcesses extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('processes');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('initial_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('teacher_intent_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('primary_distribution_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('substitute_intent_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('secondary_distribution_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('final_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'default' => 'OPENED',
                'limit' => '50',
                'null' => false,
            ])
            ->create();

        $table = $this->table('processes_process_configurations', ['id' => false, 'primary_key' => ['process_id', 'process_configuration_id']]);
        $table
            ->addColumn('process_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('process_configuration_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'process_configuration_id', 'process_id'
                ]
            )
            ->create();

        /** Associations */
        $this->table('processes_process_configurations')
            ->addForeignKey(
                'process_configuration_id',
                'process_configurations',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'process_id',
                'processes',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('processes_process_configurations')
            ->dropForeignKey(
                'process_configuration_id'
            )
            ->dropForeignKey(
                'process_id'
            );

        $this->dropTable('processes');
        $this->dropTable('processes_process_configurations');
    }
}
