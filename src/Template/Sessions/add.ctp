<div class="sessions form">
    <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
    <?= $this->Form->create($session) ?>
    <fieldset>
        <legend><?= __('Request Session') ?></legend>

        <?php
            echo $this->Form->input('schedule', ['class' => 'form-control', 'label'=>'Date and time']);
            echo $this->Form->input('subject',  ['class' => 'form-control']);
            echo $this->Form->input('comments',['class' => 'form-control']);
            ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>