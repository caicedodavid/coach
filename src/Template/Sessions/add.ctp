<div class="sessions form">
    <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
    <?= $this->Form->create($session) ?>
    <fieldset>
        <legend><?= __('Request Session with {0}',$coach) ?></legend>

        <?php
            echo $this->Form->input('schedule',  ['class' => 'datepicker','type'=>'text','label'=>'Date of session:   ']);
            echo $this->Form->input('time',  ['class' => 'timepicker','type'=>'text','label'=>'time of session:   ']);
            echo $this->Form->input('subject',  ['class' => 'form-control']);
            echo $this->Form->input('comments',['class' => 'form-control']);
            ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>