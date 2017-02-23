<?php
use Migrations\AbstractMigration;

class AddCalendarProviderToUsers extends AbstractMigration
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
        $table->addColumn('calendar_provider', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
            'after' => 'external_calendar_id'
        ]);
        $table->update();
    }
}
