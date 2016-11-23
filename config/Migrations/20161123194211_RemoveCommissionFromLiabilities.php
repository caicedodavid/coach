<?php
use Migrations\AbstractMigration;

class RemoveCommissionFromLiabilities extends AbstractMigration
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
        $table = $this->table('liabilities');
        $table->removeColumn('commission');
        $table->addColumn('commission', 'decimal', [
            'null' => false,
            'after' => 'amount',
            'precision' => '2',
            'scale' =>'2', 
            'default' => 0,
        ]);
        $table->update();
    }
}
