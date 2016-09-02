<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
<div class="users">
    <h3><?= __('Coaches') ?></h3>
        <?php foreach ($users as $user): ?>
            <div class="row">
                <div class="col-md-3">
                    <?php echo $this->Img->display($user['user_image'], 'small', ['url' => ['action' => 'view', $user->id]]);?>
                    <p><?= h($user->full_name) ?></p>
                </div>
                <div class="col-md-6"><?= $user->description ?></div>
                <div class="col-md-2"><?php echo "<span class=\"stars\">" .$user->rating . "</span>"?></div>
                <div class="col-md-1"><?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?></div>
            </div>
        <?php endforeach; ?>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
