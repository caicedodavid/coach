<?php
use Migrations\AbstractMigration;

class AddPriceToTopics extends AbstractMigration
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
        $table->addColumn('price', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
            'after' => 'coach_id'
        ]);
        $table->update();
    }
}
