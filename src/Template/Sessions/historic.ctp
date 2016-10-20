<?= $this->extend('/Element/Sessions/session_tabs');
    $this->assign('typeSession', "historic");
?>
<br>
<div id="pagination-container">
    <?php if (!$historicSessions->count()):?>
        <div class="alert alert-info"><?= __('There are no sessions to show.')?></div>
    <?php else: ?>
        <?php foreach ($historicSessions as $session): ?>
            <div class="row">
                <div class="col-md-3">
                <?php echo $this->Img->display(isset($session->coach['user_image'])? $session->coach['user_image'] : $session->user['user_image'], 'small', ['url' => ['action' => 'view', 'controller' => 'AppUsers', isset($session->coach['id'])? $session->coach['id']: $session->user['id']]]);?>
                    <p><?= h(isset($session->coach['full_name'])? $session->coach['full_name']: $session->user['full_name']) ?></p>
                </div>
                <div class="col-md-2"><?= $session->schedule ?></div>
                <div class="col-md-6"><?= $session->subject ?></div>
                <div class="col-md-1"><?= $this->Html->link(__('Details'), ['controller' => 'Sessions', 'plugin' => false, 'action' => 'view', $session->id]) ?></div>
            </div>
        <?php endforeach; ?>
        <?php echo $this->element('classic_pagination'); ?>
    <?php endif;?>
</div>