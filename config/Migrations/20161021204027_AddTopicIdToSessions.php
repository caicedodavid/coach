<?php
use Migrations\AbstractMigration;

class AddTopicIdToSessions extends AbstractMigration
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
        $table->addColumn('topic_id', 'char', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
