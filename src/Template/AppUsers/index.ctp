<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
<div class="users">
    <h3><?= __('Coaches') ?></h3>
    <table cellpadding="0" cellspacing="0" class="table table-striped">
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php
                    if ($user['user_image']===NULL){
                        echo $this->Image->display($blank, 'small');
                    }
                    else{
                        echo $this->Image->display($user['user_image'], 'small'); 
                    }
                ?></td>
                <td><?= h($user->full_name) ?></td>
                <td><?= $user->description ?></td>
                <td><?= h($user->rating) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
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
</div>
