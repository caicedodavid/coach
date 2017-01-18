<?php
use Migrations\AbstractMigration;

class AddCalendarKeyToUsers extends AbstractMigration
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
        $table->addColumn('external_calendar_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
            'after' => 'external_payment_id' 
        ]);
        $table->update();
    }
}
