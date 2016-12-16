<?php
use Migrations\AbstractMigration;

class AddRatingToTopic extends AbstractMigration
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
        $table->addColumn('rating', 'decimal', [
            'null' => false,
            'after' => 'price',
            'precision' => '3',
            'scale' =>'2', 
            'default' => 0,
        ]);
        $table->update();
    }
}
