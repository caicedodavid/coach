<?= $this->extend('/Element/Sessions/session_tabs');
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
                    <?php echo $this->Img->display(isset($session->coach['user_image'])? $session->coach['user_image'] : $session->user['user_image'], 'small');?>
                    <p><?= h(isset($session->coach['full_name'])? $session->coach['full_name']: $session->user['full_name']) ?></p>
                </div>
                <div class="col-md-1"><?= $session->schedule ?></div>
                <div class="col-md-6"><?= $session->subject ?></div>
                <div class="col-md-1">
                    <?= $this->Form->postLink(__('Cancel'),['plugin' => false,'action' => 'cancelSession',$session->id, 'controller' => 'Sessions'], 
                            ['class' => 'buttonS', 'method'=>'post','id' =>'decline','confirm' => __('Are you sure you want to cancel this session?')]);?>
                </div>
                <div class="col-md-1"><?= $this->Html->link(__('Details'), ['controller' => 'Sessions', 'plugin' => false, 'action' => 'view', $session->id]) ?></div>
            </div>
        <?php endforeach; ?>
        <?php echo $this->element('classic_pagination'); ?>
    <?php endif;?>
</div> 