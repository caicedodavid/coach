<?php
use Migrations\AbstractMigration;

class AddStatusToSession extends AbstractMigration
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
        $table = $this->table('sessions');
        $table->addColumn('status', 'integer', [
            'default' => 1,
            'null' => false,
            'after'=> 'coach_id'
        ]);
        $table->update();
    }
}