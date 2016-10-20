<?= $this->extend('/Element/Sessions/session_tabs');
    $this->assign('typeSession', "pending");
?>
<br>
<div id="pagination-container">
    <?php if (!$pendingSessions->count()):?>
        <div class="alert alert-info"><?= __('There are no pending sessions to confirm.')?></div>
    <?php else:?>
        <?php foreach ($pendingSessions as $session): ?>
            <div class="row">
                <div class="col-md-3">
                    <?php echo $this->Img->display($session->coach['user_image'], 'small', ['url' => ['action' => 'view', 'controller' => 'AppUsers', $session->coach['id']]]);?>
                    <p><?= h($session->coach['full_name']) ?></p>
                </div>
                <div class="col-md-2"><?= $session->schedule ?></div>
                <div class="col-md-4"><?= $this->Html->link(__($session->subject), ['controller' => 'Sessions', 'plugin' => false, 'action' => 'view', $session->id]);?></div>
                <div class="col-md-1">
                    <?= $this->element('Sessions/cancel_session_button', ['session' => $session, 'button' => 'Cancel', 'message' => 'Are you sure you want to cancel this requested session?']);?>
                </div>
                <div class="col-md-2"><?= $this->Html->link(__('Details'), ['controller' => 'Sessions', 'plugin' => false, 'action' => 'viewPendingUser', $session->id]) ?></div>
            </div>
        <?php endforeach; ?>
        <?php echo $this->element('classic_pagination'); ?>
    <?php endif;?>
</div>