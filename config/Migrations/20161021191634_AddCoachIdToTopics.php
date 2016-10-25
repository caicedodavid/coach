<?php
use Migrations\AbstractMigration;

class AddCoachIdToTopics extends AbstractMigration
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
        $table = $this->table('topics');
        $table->addColumn('coach_id', 'char', [
            'limit' => 36,
            'null' => false,
            'after' => 'duration'
        ]);
        $table->update();
    }
}
