<?php
/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
use Cake\Core\Configure;
?>
<div class="users form large-10 medium-9 columns">
    <?= $this->Form->create($user,['url' => ['plugin' => 'CakeDC/Users','action' => 'register','user','controller' => 'Users']]); ?>
    <fieldset>
        <legend><?= __d('CakeDC/Users', 'Register Coachee') ?></legend>
        <?php
        echo $this->Form->input('username', ['class' => 'form-control']);
        echo $this->Form->input('email', ['class' => 'form-control']);
        echo $this->Form->input('password', ['class' => 'form-control']);
        echo $this->Form->input('password_confirm', ['type' => 'password', 'class' => 'form-control']);
        echo $this->Form->input('first_name', ['class' => 'form-control']);
        echo $this->Form->input('last_name', ['class' => 'form-control']);
        echo $this->Form->hidden('role',['value'=>'coach']);
        if (Configure::read('Users.Tos.required')) {
            echo $this->Form->input('tos', ['type' => 'checkbox', 'label' => __d('CakeDC/Users', 'Accept TOS conditions?'), 'required' => true]);
        }
        if (Configure::read('Users.reCaptcha.registration')) {
            echo $this->User->addReCaptcha();
        }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), '/login' ,['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>
