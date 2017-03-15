<?php use Cake\Core\Configure as Config;?>
<?php $this->start('headCss'); ?>
    <?php echo $this->AssetCompress->css('EducoTheme.crop');?>
    <?php echo $this->Html->css('crop');?>
<?php $this->end('headCss'); ?>
<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => __('Add Topic')]); ?>
<?php $this->end() ?>
<?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
<div class="container" id="crop-avatar">
    <?= $this->Form->create($topic, ['type' => 'file', 'id' => 'topic-form', 'class' => 'avatar-form']);?>
        <div class="avatar-view preview-topic" title="Change the avatar">
            <?php echo $this->Img->displayImage($topic['topic_image'], 'big');?>
        </div>
        <div class="avatar-preview preview-huge" hidden></div>
        <!-- Loading state -->
        <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>  
        <?php
            echo $this->Form->input('name', ['class' => 'form-control']);
            echo $this->Form->input('description',['required' => false]);
        ?>
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <?= $this->Form->input('price',[
                        'required' => true,
                        'class' => 'form-control',
                        'min' => 1,
                        'default' => '',
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
        <?= $this->Form->button(__('Submit'), ['id' => 'submit-button', 'class' => 'ed_btn ed_orange medium btn btn-primary pull-right avatar-save']) ?>
        <?= $this->Html->link(__('Cancel'), ['action' => 'coachTopics', $userAuth['id'], 'controller' => 'Topics'], ['class' => 'ed_btn ed_green medium btn btn-default pull-right'])?>
        <!-- Cropping modal -->
        <?php echo $this->element('crop_modal', ['previewClass' => 'preview-topic-sm']); ?>
    <?= $this->Form->end() ?>
</div>    
<?php $this->start('bottomScript'); ?>
    <?php echo $this->AssetCompress->script('EducoTheme.crop');?>
    <?= $this->Html->script('crop');?>
    <script type="text/javascript">
        $(function () {
            return new CropAvatar($('#crop-avatar'), <?=Config::read('FileStorage.imageSizes.Topics.big.thumbnail.width')/Config::read('FileStorage.imageSizes.Topics.big.thumbnail.height')?>);
        });
    </script>
<?php $this->end('bottomScript'); ?>