<?= $this->extend('/Element/Sessions/coach_list');
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
                    <?php echo $this->Img->display($session->coach['user_image'], 'small');?>
                    <p><?= h($session->coach['full_name']) ?></p>
                </div>
                <div class="col-md-2"><?= $session->schedule ?></div>
                <div class="col-md-5"><?= $session->subject ?></div>
                <div class="col-md-2">
                    <?= $this->Html->link(__d('Session', 'Accept'), ['plugin' => false,'action' => 'register','coach','controller' => 'Sessions'],['class' => 'buttonS','id' =>'accept', 'method'=>'get']);?>
                    <?= $this->Form->postLink(__('Decline'),['plugin' => false,'action' => 'delete',$session->id, 'controller' => 'Sessions'], 
                        ['class' => 'buttonS', 'method'=>'post','id' =>'decline','confirm' => __('Are you sure you want to reject this requested session?')]);?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php echo $this->element('classic_pagination'); ?>
    <?php endif;?>
</div>