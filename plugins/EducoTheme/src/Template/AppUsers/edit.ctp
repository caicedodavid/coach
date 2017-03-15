<?php use Cake\Core\Configure as Config;?>
<?php $this->start('headCss'); ?>
    <?php echo $this->AssetCompress->css('EducoTheme.crop');?>
    <?php echo $this->Html->css('crop');?>
<?php $this->end('headCss'); ?>
<?php
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', __('Edit Profile'));
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar',$this->Sidebar->tabs($user))?>
<?php $this->end('tabs') ?>
<div class="tab-content">
    <div class="users form">
        <div class="control-group warning">
            <div id="crop-avatar">
                <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
                <?= $this->Form->create($user,['type' => 'file', 'id' => 'topic-form', 'class' => 'avatar-form']) ?>
                    <div class="avatar-view preview-user" title="Change the avatar">
                        <?php echo $this->Img->displayImage($user['user_image'], 'medium');?>
                    </div>
                    <div class="avatar-preview preview-huge" hidden></div>
                    <!-- Loading state -->
                    <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                    <?php
                        echo $this->Form->input('first_name', ['class' => 'form-control']);
                        echo $this->Form->input('last_name',  ['class' => 'form-control']);
                        echo $this->Form->input('email', ['class' => 'form-control']);
                        echo "<b>" . __('Birthdate') . "</b></br>";
                        echo $this->Form->input('birthdate',[
                            'error'=> false,
                            'id' => 'birthdate',
                            'defaultDate' => $defaultDate,
                            'class' => 'form-control',
                            'type'=>'text',
                            'label' => false,
                            'templates' => [
                                'inputContainer' => '<div class="input text required"><div class="input-group date" id="date" name ="date">{{content}}<span class="input-group-addon"><span     class="glyphicon glyphicon-calendar"></span></span></div></div>'
                            ],
                        ]);
                        if ($this->Form->isFieldError('birthdate')) {
                            echo $this->Form->error('birthdate');
                        }
                        echo $this->Form->input('profession',['class' => 'form-control', 'label' => __('Profession') ]);
                        echo $this->Form->input('fb_account', ['class' => 'form-control','label' => __('Facebook Account')]);
                        echo $this->Form->input('tw_account', ['class' => 'form-control','label' => __('Twitter Account')]);
                        echo $this->Form->input('description',['class' => 'form-control']);
                        ?>
                <?= $this->Form->button(__('Submit'), ['class' => 'ed_btn ed_orange medium btn btn-primary pull-right avatar-save']) ?>
                <?= $this->Html->link(__('Cancel'), ['action' => 'myProfile'],['class' => 'ed_btn ed_green medium btn btn-default pull-right']) ?>
                <?php echo $this->element('crop_modal', ['previewClass' => 'preview-user-sm']); ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<?php $this->start('bottomScript'); ?>
    <?php echo $this->AssetCompress->script('EducoTheme.crop');?>
    <?= $this->Html->script('crop');?>
    <script type="text/javascript">
        $(function () {
            return new CropAvatar($('#crop-avatar'), <?=Config::read('FileStorage.imageSizes.AppUsers.medium.thumbnail.width')/Config::read('FileStorage.imageSizes.AppUsers.medium.thumbnail.height')?>);
        });
    </script>
<?php $this->end('bottomScript'); ?>

