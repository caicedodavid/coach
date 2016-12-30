<?php
use Migrations\AbstractMigration;

class CreateLiabilities extends AbstractMigration
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
        $table = $this->table('liabilities');
        $table->addColumn('amount', 'decimal', [
            'default' => 0,
            'precision' => '5',
            'scale' =>'2', 
            'null' => false,
        ]);
        $table->addColumn('commission', 'integer', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('type','char', [
            'limit' => 10,
            'null' => false,
            'default' => null
        ]);
        $table->addColumn('fk_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('fk_table', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
