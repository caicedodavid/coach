<?php
use Migrations\AbstractMigration;

class RemoveBalanceFromUsers extends AbstractMigration
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
        $table->removeColumn('balance');
        $table->addColumn('balance', 'decimal', [
            'default' => 0,
            'precision' => '5',
            'scale' =>'2', 
            'null' => false,
            'after' => 'description'
        ]);
        $table->update();
    }
}
