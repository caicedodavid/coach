<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => __('Change Password')]);?>
<?php $this->end() ?>
<?= $this->Flash->render('auth') ?>
<?= $this->Form->create($user) ?>
<div class="row">

    <div class="col-lg-2 col-md-2 col-sm-2">
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <fieldset>
            <?= $this->Form->input('password', [
                'type' => 'password',
                'required' => true,
                'label' => __('New password'),
                'class' => 'form-control',
                'value'=> ''
            ]);?>
            <?= $this->Form->input('password_confirm', [
                'type' => 'password',
                'required' => true,
                'label' => __('Confirm password'),
                'value'=> '',
                'class' => 'form-control'
            ]);?>
        </fieldset>
        <?= $this->Form->button(__('Submit'), ['class ' => 'ed_btn ed_orange small']); ?>
    </div>

</div>
<?= $this->Form->end() ?>
