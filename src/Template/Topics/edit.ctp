<div class="topics form large-9 medium-8 columns content">
    <?= $this->Form->create($topic,['type' => 'file']);?>
    <fieldset>
        <br>
        <?php
            echo $this->Img->displayImage($topic['topic_image'], 'large'); 
            echo $this->Form->file('topic_image.file', ['class' => 'form-control', 'required'=>false]);
            echo $this->Form->error('topic_image.file',['class' => 'form-control']);
            echo $this->Form->input('name', ['class' => 'form-control']);
            echo $this->Form->input('description', ['class' => 'form-control']);
            echo $this->Form->input('active');
            echo $this->Form->input('duration', ['options' => $times, 'class' => 'form-control', 'empty' => __('Select...')]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['controller' => 'Topics', 'action' => 'view', $topic->id],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>
