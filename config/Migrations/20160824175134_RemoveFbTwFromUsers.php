<?php
use Migrations\AbstractMigration;

class RemoveFbTwFromUsers extends AbstractMigration
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
        $table->removeColumn('fb_account');
        $table->removeColumn('tw_account');
        $table->update();
        $table->addColumn('fb_account', 'string', [
            'default' => null,
            'null' => true,
            'after'=>'role',
            'limit'=>25
        ]);
        $table->addColumn('tw_account', 'string', [
            'default' => null,
            'null' => true,
            'after'=>'role',
            'limit'=>25
        ]);
        $table->update();
    }
}
