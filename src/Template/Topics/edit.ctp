<div class="topics form large-9 medium-8 columns content">
    <?= $this->Form->create($topic,['type' => 'file']);?>
    <fieldset>
        <legend><?= __('Edit Topic') ?></legend>
        <?php
            echo $this->Img->displayImage($topic['topic_image'], 'large'); 
            echo $this->Form->file('topic_image.file', ['class' => 'form-control', 'required'=>false]);
            echo $this->Form->error('topic_image.file',['class' => 'form-control']);
            echo $this->Form->input('name', ['class' => 'form-control']);
            echo $this->Form->input('description', ['class' => 'form-control']);
            echo $this->Form->input('active');
            echo $this->Form->input('duration', ['class' => 'form-control']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['controller' => 'Topics', 'action' => 'view', $topic->id],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>
