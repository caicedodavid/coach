<?php
use Migrations\AbstractMigration;

class RemoveRatingFromUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table->removeColumn('rating');
        $table->addColumn('rating', 'decimal', [
            'null' => false,
            'after' => 'profession',
            'precision' => '3',
            'scale' =>'2', 
            'default' => 0,
        ]);
        $table->update();
    }
}
