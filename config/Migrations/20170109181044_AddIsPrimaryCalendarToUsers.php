<?php
use Migrations\AbstractMigration;

class AddIsPrimaryCalendarToUsers extends AbstractMigration
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
        $table->addColumn('is_primary_calendar', 'boolean', [
            'default' => false,
            'null' => false,
            'after' => 'external_calendar_id' 
        ]);
        $table->update();
    }
}
