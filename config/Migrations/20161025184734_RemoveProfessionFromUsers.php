<?php
use Migrations\AbstractMigration;

class RemoveProfessionFromUsers extends AbstractMigration
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
        $table->removeColumn('profession');
        $table->addColumn('profession', 'char', [
            'default' => null,
            'null' => true,
            'after' => 'birthdate',
            'limit'=>50
        ]);
        $table->update();
    }
}
