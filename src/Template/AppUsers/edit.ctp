<div class="users form">
<div class="control-group warning">
    <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
    <?= $this->Form->create($user,['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
            echo $this->Img->display($user['user_image'], 'large'); 
            echo $this->Form->file('user_image.file', ['class' => 'form-control', 'required'=>false]);
            echo $this->Form->error('user_image.file',['class' => 'form-control']);
            echo $this->Form->input('first_name', ['class' => 'form-control']);
            echo $this->Form->input('last_name',  ['class' => 'form-control']);
            echo $this->Form->input('birthdate',[
                'class' => 'datepicker',
                'type'=>'text',
                'id'=>'date',
                'name'=>'date',
                'placeholder'=>'YYYY-MM-DD', 
                'data-date-viewmode'=>'years',
                'between' =>'<span class=\"add-on\"><i class=\"icon-calendar\"></i></span>'
            ]);
            echo "<span class=\"add-on\"><i class=\"icon-calendar\"></i></span>";
            echo $this->Form->input('fb_account', ['class' => 'form-control','label' => 'Facebook Account']);
            echo $this->Form->input('tw_account', ['class' => 'form-control','label' => 'Twitter Account']);
            echo $this->Form->input('description',['class' => 'form-control']);
            ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>
</div>