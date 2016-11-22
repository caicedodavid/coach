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
        $table->addColumn('observation', 'text', [
            'default' => null,
            'null' => true,
            'after'=> 'type'
        ]);
        $table->update();
    }
}
