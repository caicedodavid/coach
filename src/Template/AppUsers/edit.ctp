<div class="users form">
    <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
    <?= $this->Form->create($user,['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>

        <?php
            echo $this->Img->display($user['user_image'], 'large'); 
            echo $this->Form->file('user_image.file', ['class' => 'form-control']);
            echo $this->Form->error('user_image.file',['class' => 'form-control']);
            echo $this->Form->input('first_name', ['class' => 'form-control']);
            echo $this->Form->input('last_name',  ['class' => 'form-control']);
            echo $this->Form->input('birthdate',  ['class' => 'datepicker','type'=>'text']);
            echo $this->Form->input('fb_account', ['class' => 'form-control','label' => 'Facebook Account']);
            echo $this->Form->input('tw_account', ['class' => 'form-control','label' => 'Twitter Account']);
            echo $this->Form->input('description',['class' => 'form-control']);
            ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>