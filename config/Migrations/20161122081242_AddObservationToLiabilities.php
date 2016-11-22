<?php
use Migrations\AbstractMigration;

class AddObservationToLiabilities extends AbstractMigration
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
        $table->removeColumn('type');
        $table->addColumn('status','char', [
            'limit' => 10,
            'null' => false,
            'default' => null,
            'after'=> 'commission'
        ]);
        $table->addColumn('observation', 'text', [
            'default' => null,
            'null' => true,
            'after'=> 'status'
        ]);
        $table->update();
    }
}
