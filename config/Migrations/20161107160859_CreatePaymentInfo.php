<?php
use Migrations\AbstractMigration;

class CreatePaymentInfo extends AbstractMigration
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
        $table = $this->table('payment_infos');
        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'limit' => 40,
            'null' => false,
        ]);
        $table->addColumn('address1', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('address2', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('country', 'string', [
            'limit' => 30,
            'null' => false,
        ]);
        $table->addColumn('state', 'string', [
            'limit' => 30,
            'null' => false,
        ]);
        $table->addColumn('city', 'string', [
            'limit' => 30,
            'null' => false,
        ]);
        $table->addColumn('zipcode', 'string', [
            'limit' => 10,
            'null' => false,
        ]);
        $table->addColumn('card_id', 'string', [
            'limit' => 40,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('active', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->create();
    }
}
