<?php
use Migrations\AbstractMigration;

class RemovePaymentInfosIdFromPayments extends AbstractMigration
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
        $table = $this->table('payments');
        $table->removeColumn('payment_infos_id');
        $table->removeColumn('amount');
        $table->addColumn('amount', 'decimal', [
            'default' => 0,
            'precision' => '5',
            'scale' =>'2', 
            'null' => false,
            'after' => 'id'
        ]);
        $table->addColumn('payment_infos_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
            'after' => 'amount'
        ]);
        $table->addColumn('payment_type','char', [
            'limit' => 10,
            'null' => false,
            'default' => null
        ]);
        $table->update();
    }
}
