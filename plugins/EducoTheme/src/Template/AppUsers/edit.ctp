<?php $this->start('banner') ?>
<?php echo $this->element('banner', ['title' => __('Edit Profile')]); ?>
<?php $this->end() ?>
<div class="users form">
<div class="control-group warning">
    <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
    <?= $this->Form->create($user,['type' => 'file']) ?>
    <fieldset>
        <br>
        <?php
            echo $this->Img->display($user['user_image'], 'large'); 
            echo $this->Form->file('user_image.file', ['class' => 'form-control', 'required'=>false]);
            echo $this->Form->error('user_image.file',['class' => 'form-control']);
            echo $this->Form->input('first_name', ['class' => 'form-control']);
            echo $this->Form->input('last_name',  ['class' => 'form-control']);
            echo $this->Form->input('email', ['class' => 'form-control']);
            echo "<b>Birthdate</b></br>";
            echo $this->Form->input('birthdate',[
                'error'=> false,
                'id' => 'birthdate',
                'class' => 'form-control',
                'type'=>'text',
                'placeholder'=>'YYYY-MM-DD',
                'label' => false,
                'templates' => [
                    'inputContainer' => '<div class="input text required"><div class="input-group date" id="date" name ="date">{{content}}<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div>'
                ],
            ]);
            if ($this->Form->isFieldError('birthdate')) {
                echo $this->Form->error('birthdate');
            }
            echo $this->Form->input('profession',['class' => 'form-control']);
            echo $this->Form->input('fb_account', ['class' => 'form-control','label' => 'Facebook Account']);
            echo $this->Form->input('tw_account', ['class' => 'form-control','label' => 'Twitter Account']);
            echo $this->Form->input('description',['class' => 'form-control']);
            ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'ed_btn ed_orange medium btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'],['class' => 'ed_btn ed_green medium btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>
</div>