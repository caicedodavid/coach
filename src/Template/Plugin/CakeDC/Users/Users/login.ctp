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
<div class="users form">
    <?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __d('CakeDC/Users', 'Please enter your username and password') ?></legend>
        <?= $this->Form->input('username', ['required' => true, 'class' => 'form-control']) ?>
        <?= $this->Form->input('password', ['required' => true, 'class' => 'form-control']) ?>
        <?php
        if (Configure::read('Users.reCaptcha.login')) {
            echo $this->User->addReCaptcha();
        }
        if (Configure::check('Users.RememberMe.active')) {
            echo $this->Form->input(Configure::read('Users.Key.Data.rememberMe'), [
                'type' => 'checkbox',
                'label' => __d('CakeDC/Users', 'Remember me'),
                'checked' => 'checked'
            ]);
        }
        ?>
            <?php
            $registrationActive = Configure::read('Users.Registration.active');
            if ($registrationActive) {
                echo $this->Html->link(__d('Users', 'Register'), ['plugin' =>false,'controller'=>'Pages','action' => 'display','type_user','ext'=>'ctp']);
            }
            if (Configure::read('Users.Email.required')) {
                if ($registrationActive) {
                    echo ' | ';
                }
                echo $this->Html->link(__d('CakeDC/Users', 'Reset Password'), ['action' => 'requestResetPassword']);
                echo ' | ';
                echo $this->Html->link(__d('CakeDC/Users', 'Resend validation email'), ['action' => 'resendTokenValidation']);
            }
            ?>
    </fieldset>
    <?= implode(' ', $this->User->socialLoginList()); ?>
    <?= $this->Form->button(__d('CakeDC/Users', 'Login'), ['class' => 'btn btn-primary pull-right']); ?>
    <?= $this->Form->end() ?>
</div>
