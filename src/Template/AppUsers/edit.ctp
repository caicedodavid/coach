<div class="users form">
    <?= $this->Form->create($user,['type' => 'file']) ?>
    <?php $this->TinyMCE->editor(array('theme' => 'advanced', 'mode' => 'textareas'));?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
            echo $this->Form->input('first_name', ['class' => 'form-control']);
            echo $this->Form->input('last_name',  ['class' => 'form-control']);
            echo $this->Form->input('fb_account', ['class' => 'form-control']);
            echo $this->Form->input('tw_account', ['class' => 'form-control']);
            echo $this->Form->input('description',['class' => 'form-control']);
            echo $this->Form->file('user_images', ['class' => 'form-control']);
            echo $this->Form->error('user_images',['class' => 'form-control']);

        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
    <div id='prueba'></div>
    <script>
    $('#prueba').text("hola");
    </script>
</div>