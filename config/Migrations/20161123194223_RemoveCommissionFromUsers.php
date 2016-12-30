<?php
use Migrations\AbstractMigration;

class RemoveCommissionFromUsers extends AbstractMigration
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
        $table->removeColumn('commission');
        $table->addColumn('commission', 'decimal', [
            'null' => false,
            'precision' => '2',
            'scale' =>'2', 
            'default' => 0,
            'after' => 'balance'
        ]);
        $table->update();
    }
}
