<?php
use Migrations\AbstractMigration;

class RemoveBirthdateFromUsers extends AbstractMigration
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
        $table->removeColumn('birthdate');
        $table->removeColumn('rating');
        $table->update();
        $table->addColumn('birthdate', 'date', [
            'default' => null,
            'null' => true,
            'after'=>'role',
        ]);
        $table->addColumn('rating', 'decimal', [
            'default' => 0,
            'after'=>'role',
        ]);
        $table->update();
    }
}
