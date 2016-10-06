<div class="sessions form">
    <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
    <?= $this->Form->create($session,['type' => 'post']) ?>
    <fieldset>
        <legend><?= __('Rate your session') ?></legend>
        <?php
        	echo $this->Form->label('Session.user_rating','Rate the class:',['class'=>'control-label']);
            echo $this->Form->input('user_rating',  ['class' => 'rating','data-min'=>'0', 'data-max'=>'5', 'data-step'=>'1','label'=>false,'data-size'=>'xs']);
            echo $this->Form->input('user_comments',['class' => 'form-control']);
            ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'approved'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>