<?php
use Migrations\AbstractMigration;

class RemovecardIdFromPaymentInfos extends AbstractMigration
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
        $table->removeColumn('card_id');
        $table->addColumn('external_card_id', 'string', [
            'limit' => 40,
            'null' => false,
        ]);
        $table->update();
    }
}
