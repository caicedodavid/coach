<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="users form">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->input('username', ['class' => 'form-control']);
            echo $this->Form->input('email', ['class' => 'form-control']);
            echo $this->Form->input('password',  ['class' => 'form-control']);
            echo $this->Form->input('first_name',  ['class' => 'form-control']);
            echo $this->Form->input('last_name',  ['class' => 'form-control']);
            echo $this->Form->input('active');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['action' => 'index'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>
