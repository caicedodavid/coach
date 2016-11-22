<?php
use Migrations\AbstractMigration;

class AddCommissionToUsers extends AbstractMigration
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
        $table->addColumn('commission', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
            'after' => 'balance'
        ]);
        $table->update();
    }
}
