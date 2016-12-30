<?php
use Migrations\AbstractMigration;

class AddDateToLiabilities extends AbstractMigration
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
        $table->addColumn('date', 'date', [
            'default' => null,
            'null' => true,
            'after' => 'observation'
        ]);
        $table->update();
    }
}
