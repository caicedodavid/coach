<?php $this->start('banner') ?>
<?php echo $this->element('banner', ['title' => __('Topics')]); ?>
<?php $this->end() ?>

<div class="page index">
    <?php echo $this->element('Topics/list'); ?>
</div>