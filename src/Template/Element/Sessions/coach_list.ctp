<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
<div class="sessions">
    <h3><?= __('Your scheduled sessions with coaches') ?></h3>
        <?php if (!$sessions->count()):?>
            <div class="alert alert-info"><?= __('You have no programed sessions')?></div>
        <?php else: ?>
            <div id="users">
                <?php foreach ($sessions as $session): ?>
                    <div class="row">
                        <div class="col-md-3">
                            <?php echo $this->Img->display($session->coach['user_image'], 'small');?>
                            <p><?= h($session->coach['full_name']) ?></p>
                        </div>
                        <div class="col-md-2"><?= $session->schedule ?></div>
                        <div class="col-md-6"><?= $session->subject ?></div>
                        <div class="col-md-1"><?= $this->Html->link(__('Details'), ['controller' => 'Sessions', 'plugin' => false, 'action' => 'view', $session->id]) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <center><?php echo $this->element('pagination'); ?></center>
    </div>
</div>
