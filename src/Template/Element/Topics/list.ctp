<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
<div class="topics">
    <h3><?= __('My Topics') ?></h3>
        <?php if (!$topics->count()):?>
            <div class="alert alert-info"><?= __('You have no topics')?></div>
        <?php else: ?>
            <div id="items">
                <?php foreach ($topics as $topic): ?>
                    <div class="row">
                        <div class="col-md-3">
                            <?php echo $this->Img->displayImage($topic['topic_image'], 'small');?>
                        </div>
                        <div class="col-md-3"><?= $topic->name ?></div>
                        <div class="col-md-5"><?= $topic->description ?></div>
                        <div class="col-md-1"><?= $this->Html->link(__('Details'), ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $topic->id]) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <center><?php echo $this->element('endless_pagination'); ?></center>
    </div>
</div>
