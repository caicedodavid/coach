<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="users form">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Form->create('User') ?>
            <fieldset>
                <legend><?= __d('CakeDC/Users', 'Please enter your email to reset your password') ?></legend>
                <?= $this->Form->input('reference', ['class' => 'form-control']) ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class' => 'btn ed_btn pull-right ed_orange pull-right small']) ?>
            <?= $this->Html->link(__('Cancel'), '/login' ,['class' => 'btn ed_btn ed_green pull-right small']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
