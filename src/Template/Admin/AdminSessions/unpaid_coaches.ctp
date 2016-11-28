<nav class="large-3 medium-4 columns" id="actions-sidebar">
<div class="users">
    <h3><?= __('Pending for payment') ?></h3>
        <?php if (!$coaches->count()):?>
            <div class="alert alert-info"><?= __('There are no coaches to be paid')?></div>
        <?php else: ?>
            <table cellpadding="0" cellspacing="0" class="table table-striped">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('username') ?></th>
                        <th><?= $this->Paginator->sort('full_name') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coaches as $coach): ?>
                    <tr>
                        <td><?= h($coach->Coaches->username) ?></td>
                        <td><?= h($coach->Coaches->full_name) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('Pay'), ['action' => 'payCoach', $coach->Coaches->id, 'controller' => 'AdminSessions', 'plugin' => false]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                </ul>
                <p><?= $this->Paginator->counter() ?></p>
            </div>
        <?php endif;?>
    </div>
</div>
