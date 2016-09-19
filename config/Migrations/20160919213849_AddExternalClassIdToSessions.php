<?php
use Migrations\AbstractMigration;

class AddExternalClassIdToSessions extends AbstractMigration
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
        $table->removeColumn('class_id');
        $table->addColumn('external_class_id', 'integer', [
            'default' => null,
            'null' => true,
            'after'=>'coach_id',
        ]);
        $table->update();
    }
}