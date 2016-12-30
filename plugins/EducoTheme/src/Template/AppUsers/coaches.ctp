<?php $this->start('banner') ?>
<?php echo $this->element('banner', ['title' => __('Coaches')]); ?>
<?php $this->end() ?>

<div class="page index">
    <?php echo $this->element('AppUsers/list'); ?>
</div>