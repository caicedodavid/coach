<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => 'Rate Session']); ?>
<?php $this->end() ?>
<div class="rate form">
    <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
    <?= $this->Form->create($session,['type' => 'post']) ?>
    <fieldset>
    <br>
        <?php
        	echo $this->Form->label('Session.coach_rating','Rate the class:');
            echo $this->Form->input('coach_rating',  ['class' => 'rating','data-min'=>'0', 'data-max'=>'5', 'data-step'=>'1','label'=>false,'data-size'=>'xs','required' => true]);
            echo $this->Form->input('coach_comments',['class' => 'form-control']);
            ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'approved'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>