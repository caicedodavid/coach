<div class="page index">
    <?php echo $this->element('Topics/list'); ?>
    <?= $this->Html->link(__d('Topics', 'Add new topic'), ['plugin' => false,'action' =>'add', 'controller' => 'Topics'],
	['class' => 'buttonS']);?>
</div>
