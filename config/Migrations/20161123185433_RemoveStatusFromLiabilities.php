<?php
use Migrations\AbstractMigration;

class RemoveStatusFromLiabilities extends AbstractMigration
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
        $table->removeColumn('status');
        $table->addColumn('status', 'integer', [
            'default' => 1,
            'null' => false,
            'after'=> 'commission'
        ]);

        $table->update();
    }
}