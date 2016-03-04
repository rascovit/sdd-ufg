<?php
use Migrations\AbstractMigration;

class CreateClazzes extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('clazzes');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('vacancies', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('subject_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('process_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(['subject_id', 'process_id'])
            ->create();

        $table = $this->table('clazzes_schedules_locals', ['id' => false, 'primary_key' => ['clazz_id', 'schedule_id', 'local_id', 'week_day']]);
        $table
            ->addColumn('clazz_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('schedule_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('local_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('week_day', 'integer', [
                'default' => null,
                'limit' => 2,
                'null' => false,
            ])
            ->addIndex(
                [
                    'clazz_id', 'schedule_id', 'local_id'
                ]
            )
            ->create();

        $table = $this->table('clazzes_teachers', ['id' => false, 'primary_key' => ['clazz_id', 'teacher_id']]);
        $table
            ->addColumn('clazz_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('teacher_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'default' => 'PENDING',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('simulation_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('previous_status', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addIndex(
                [
                    'clazz_id', 'teacher_id'
                ]
            )
            ->create();

        /** Create associations */
        $this->table('clazzes')
            ->addForeignKey(
                'subject_id',
                'subjects',
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

        $this->table('clazzes_schedules_locals')
            ->addForeignKey(
                'clazz_id',
                'clazzes',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'schedule_id',
                'schedules',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'local_id',
                'locals',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('clazzes_teachers')
            ->addForeignKey(
                'clazz_id',
                'clazzes',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'teacher_id',
                'teachers',
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
        $this->table('clazzes')
            ->dropForeignKey(
                'process_id'
            )
            ->dropForeignKey(
                'subject_id'
            );

        $this->table('clazzes_schedules_locals')
            ->dropForeignKey(
                'clazz_id'
            )
            ->dropForeignKey(
                'schedule_id'
            )
            ->dropForeignKey(
                'local_id'
            );

        $this->table('clazzes_teachers')
            ->dropForeignKey(
                'clazz_id'
            )
            ->dropForeignKey(
                'teacher_id'
            );

        $this->dropTable('clazzes');
        $this->dropTable('clazzes_schedules_locals');
        $this->dropTable('clazzes_teachers');
    }
}
