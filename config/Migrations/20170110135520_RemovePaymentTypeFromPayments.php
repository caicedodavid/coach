<?php
use Migrations\AbstractMigration;

class RemovePaymentTypeFromPayments extends AbstractMigration
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
        $table->removeColumn('payment_type');
        $table->addColumn('payment_type','integer', [
            'after' => 'amount',
            'null' => false,
            'default' => null
        ]);
        $table->update();
    }
}