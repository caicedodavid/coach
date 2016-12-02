<?php
use Migrations\AbstractMigration;

class AddPriceToSession extends AbstractMigration
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
        $table->addColumn('price', 'decimal', [
            'null' => false,
            'precision' => '5',
            'scale' =>'2', 
            'default' => 0,
            'after' => 'duration'
        ]);
        $table->update();
    }
}
