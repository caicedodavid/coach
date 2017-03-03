<?php $this->start('banner') ?>
<?php echo $this->element('banner', ['title' => __('Add Topic')]); ?>
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
            ?>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <?= $this->Form->input('price',[
                            'required' => true,
                            'class' => 'form-control',
                            'min' => 1,
                            'default' => 1,
                            'label' => 'Price (USD)']);
                    ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                <br>
                    <?= $this->Form->input('is_free', [
                                          'type'=>'checkbox',
                                          'label' => 'Free',
                                          'format' => ['before', 'input', 'between', 'label', 'after', 'error'],
                                          'hiddenField' => false,
                                          'id' => 'is_free'
                                          ]);

                    ?>
                </div>
            </div>
            <?php
                echo $this->Form->input('duration', ['options' => $times, 'class' => 'form-control', 'empty' => __('Select...')]);
                echo $this->Form->input('categories._ids', [
                    'options' => $categories, 
                    'multiple' => 'multiple',
                    'id' => 'categories-select',
                    'templates' => [
                        'formGroup' => '{{label}}<br>{{input}}'
                    ],
                    'class' => 'educo-theme',
                    'ids' => '[]'
                ]);
            ?>
            <?= $this->Form->input('active');?>
            <?= $this->Form->unlockField('is_free');?>
        </fieldset>
        <?= $this->Form->button(__('Submit'), ['class' => 'ed_btn ed_orange medium btn btn-primary pull-right']) ?>
        <?= $this->Html->link(__('Cancel'), ['action' => 'coachTopics', $userAuth['id'], 'controller' => 'Topics'], ['class' => 'ed_btn ed_green medium btn btn-default pull-right']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
