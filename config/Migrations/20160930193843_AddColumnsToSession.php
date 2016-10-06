<?php
use Migrations\AbstractMigration;

class AddColumnsToSession extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('sessions');
        $table->addColumn('user_comments', 'text', [
            'default' => null,
            'null' => true,
            'after'=>'external_class_id'
        ]);
        $table->addColumn('coach_comments', 'text', [
            'default' => null,
            'null' => true,
            'after'=>'external_class_id'
        ]);
        $table->addColumn('user_rating', 'integer', [
            'default' => null,
            'limit' => 1,
            'null' => true,
            'after'=>'external_class_id'
        ]);
        $table->addColumn('coach_rating', 'integer', [
            'default' => null,
            'limit' => 1,
            'null' => true,
            'after'=>'external_class_id'
        ]);
        $table->addColumn('user_time', 'time', [
            'default' => null,
            'null' => true,
            'after'=>'external_class_id'
        ]);
        $table->addColumn('coach_time', 'time', [
            'default' => null,
            'null' => true,
            'after'=>'external_class_id'
        ]);
        $table->update();
    }
}
