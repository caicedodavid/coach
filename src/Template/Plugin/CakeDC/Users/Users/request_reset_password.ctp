<div class="users form">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create('User') ?>
    <fieldset>
        <legend><?= __d('CakeDC/Users', 'Please enter your email to reset your password') ?></legend>
        <?= $this->Form->input('reference', ['class' => 'form-control']) ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), '/login' ,['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>
