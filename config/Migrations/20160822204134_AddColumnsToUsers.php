<?php
use Migrations\AbstractMigration;

class AddColumnsToUsers extends AbstractMigration
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
        $table->addColumn('fb_account', 'text', [
            'default' => null,
            'null' => true,
            'after'=>'role'
        ]);
        $table->addColumn('tw_account', 'text', [
            'default' => null,
            'null' => true,
            'after'=>'role'
        ]);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => true,
            'after'=>'role'
        ]);
        $table->addColumn('rating', 'decimal', [
            'default' => null,
            'null' => true,
            'after'=>'role'
        ]);
        $table->addColumn('birthdate', 'datetime', [
            'default' => '1992-08-05',
            'null' => false,
            'after'=>'role'
        ]);
        $table->update();
    }
}
