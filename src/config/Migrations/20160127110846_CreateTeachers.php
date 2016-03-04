<?php
use Migrations\AbstractMigration;

class CreateTeachers extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('teachers');
        $table
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('registry', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('url_lattes', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('entry_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('formation', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('workload', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('about', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('rg', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('cpf', 'string', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('birth_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('situation', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('probation', 'string', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('nde_member', 'boolean', [
                'default' => false,
                'null' => false,
            ])
            ->addColumn('exclusive_dedication', 'boolean', [
                'default' => true,
                'null' => false,
            ])
            ->addIndex(['user_id'])
            ->create();


        $table = $this->table('teachers_change_history');
        $table
            ->addColumn('teacher_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('modification_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('is_admin', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('registry', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('url_lattes', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('entry_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('formation', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('workload', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('about', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('rg', 'string', [
                'default' => null,
                'limit' => 15,
                'null' => false,
            ])
            ->addColumn('cpf', 'string', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('birth_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('situation', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex(['teacher_id',])
            ->create();

        /** Associations */
        $this->table('teachers')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('teachers_change_history')
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
        $this->table('teachers')
            ->dropForeignKey(
                'user_id'
            );

        $this->table('teachers_change_history')
            ->dropForeignKey(
                'teacher_id'
            );

        $this->dropTable('teachers');
        $this->dropTable('teachers_change_history');
    }
}
