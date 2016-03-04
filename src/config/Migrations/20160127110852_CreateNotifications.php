<?php
use Migrations\AbstractMigration;

class CreateNotifications extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('notifications');
        $table
            ->addColumn('type', 'string', [
                'default' => 'ALERT',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('is_read', 'boolean', [
                'default' => false,
                'null' => false,
            ])
            ->addColumn('link', 'string', [
                'default' => '/',
                'null' => false,
                'limit' => 255
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(['user_id'])
            ->create();

        // Associations
        $this->table('notifications')
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
    }

    public function down()
    {
        $this->table('notifications')
            ->dropForeignKey(
                'user_id'
            );

        $this->dropTable('notifications');
    }
}
