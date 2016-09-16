<?php
use Migrations\AbstractMigration;

class AddClassIdToSessions extends AbstractMigration
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
        $table->addColumn('class_id', 'integer', [
            'default' => null,
            'null' => true,
            'after'=>'coach_id',
        ]);
        $table->update();
    }
}
