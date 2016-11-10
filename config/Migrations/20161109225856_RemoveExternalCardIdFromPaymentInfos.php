<?php
use Migrations\AbstractMigration;

class RemoveExternalCardIdFromPaymentInfos extends AbstractMigration
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
        $table = $this->table('payment_infos');
        $table->removeColumn('external_card_id');
        $table->removeColumn('active');
        $table->addColumn('active', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->addColumn('external_card_id', 'string', [
            'limit' => 40,
            'null' => false,
            'after' => 'zipcode'
        ]);
        $table->update();
    }
}
