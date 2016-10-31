<?php
use Migrations\AbstractMigration;

class RemoveDurationFromTopics extends AbstractMigration
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
        $table->removeColumn('duration');
        $table->addColumn('duration', 'integer', [
            'default' => null,
            'null' => true,
            'after' => 'active'
        ]);
        $table->update();
    }
}
