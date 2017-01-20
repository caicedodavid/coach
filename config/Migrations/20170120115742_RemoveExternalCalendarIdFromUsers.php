<?php
use Migrations\AbstractMigration;

class RemoveExternalCalendarIdFromUsers extends AbstractMigration
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
        $table->removeColumn('external_calendar_id');
        $table->addColumn('external_calendar_id', 'string', [
            'default' => null,
            'limit' => 500,
            'null' => true,
            'after' => 'external_payment_id' 
        ]);
        $table->update();
    }
}
