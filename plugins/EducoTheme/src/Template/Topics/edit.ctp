<?php $this->start('banner') ?>
<?php echo $this->element('banner', ['title' => __('Edit Topic')]); ?>
<?php $this->end() ?>
<?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <?= $this->Form->create($topic,['type' => 'file']);?>
        <fieldset>
            <?php
                echo $this->Img->displayImage($topic['topic_image'], 'large'); 
                echo $this->Form->file('topic_image.file', ['class' => 'form-control', 'required'=>false]);
                echo $this->Form->error('topic_image.file',['class' => 'form-control']);
                echo $this->Form->input('name', ['class' => 'form-control']);
                echo $this->Form->input('description',['required' => false]);
                echo $this->Form->input('price',[
                    'required' => true,
                    'class' => 'form-control',
                    'label' => 'Price (USD)']);
                echo $this->Form->input('active');
                echo $this->Form->input('duration', ['options' => $times, 'class' => 'form-control', 'empty' => __('Select...')]);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit'), ['class' => 'ed_btn ed_orange medium btn btn-primary pull-right']) ?>
        <?= $this->Html->link(__('Cancel'), ['controller' => 'Topics', 'action' => 'view', $topic->id],['class' => 'ed_btn ed_green medium btn btn-default pull-right']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
