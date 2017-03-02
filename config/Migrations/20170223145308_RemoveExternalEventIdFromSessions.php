<?php
use Migrations\AbstractMigration;

class RemoveExternalEventIdFromSessions extends AbstractMigration
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
        $table->removeColumn('external_event_id');
        $table->addColumn('user_external_event_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'after' => 'user_comments'
        ]);
        $table->addColumn('coach_external_event_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'after' => 'user_external_event_id'
        ]);
        $table->update();
    }
}
