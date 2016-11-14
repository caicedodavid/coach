<?php
use Migrations\AbstractMigration;

class AddBalanceToUsers extends AbstractMigration
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
        $table->addColumn('balance', 'integer', [
            'default' => 0,
            'limit' => 11,
            'null' => false,
            'after' => 'description'
        ]);
        $table->addColumn('external_payment_id', 'string', [
            'default' => null,
            'limit' => 40,
            'null' => true,
            'after' => 'description'
        ]);
        $table->update();
    }
}
