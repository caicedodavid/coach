<?php
use Migrations\AbstractMigration;

class AddExternalEventIdToSessions extends AbstractMigration
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
        $table->addColumn('external_event_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
            'after' => 'user_comments'
        ]);
        $table->update();
    }
}
