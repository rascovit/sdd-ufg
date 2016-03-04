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

        $table = $this->table('process_configurations');
        $table
            ->addColumn('process_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => 'ALLOCATED_QUANTITY',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('priority', 'integer', [
                'default' => 1,
                'limit' => 3,
                'null' => false,
            ])
            ->addIndex(
                [
                    'process_id'
                ]
            )
            ->create();

        /** Associations */
        $this->table('process_configurations')
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
        $this->table('process_configurations')
            ->dropForeignKey(
                'process_id'
            );

        $this->dropTable('processes');
        $this->dropTable('process_configurations');
    }
}
