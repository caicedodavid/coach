<?php
use Migrations\AbstractMigration;

class AddDurationToSessions extends AbstractMigration
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
        $table->removeColumn('coach_time');
        $table->removeColumn('user_time');
        $table->addColumn('duration', 'integer', [
            'default' => null,
            'null' => true,
            'after' => 'external_class_id'
        ]);
        $table->addColumn('start_time', 'time', [
            'default' => null,
            'null' => true,
            'after' => 'external_class_id'
        ]);
        $table->update();
    }
}
