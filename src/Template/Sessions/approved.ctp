<?= $this->extend('/Element/Sessions/coach_list');
    $this->assign('typeSession', "approved");
?>
<br>
<div id="pagination-container">
    <?php if (!$approvedSessions->count()):?>
        <div class="alert alert-info"><?= __('There are no scheduled sessions.')?></div>
    <?php else: ?>
        <?php foreach ($approvedSessions as $session): ?>
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
        <?php echo $this->element('classic_pagination'); ?>
    <?php endif;?>
</div> 