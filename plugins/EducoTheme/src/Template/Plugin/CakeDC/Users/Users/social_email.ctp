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
?>
<div class="users form">
    <?= $this->Flash->render() ?>
    <?= $this->Form->create('User') ?>
    <fieldset>
        <legend><?= __d('CakeDC/Users', 'Please enter your email') ?></legend>
        <?= $this->Form->input('email', ['class'=>'form-control']) ?>
    </fieldset>
    <?= $this->Form->button(__d('CakeDC/Users', 'Submit'), ['class' => 'btn ed_btn pull-right ed_orange pull-right small']); ?>
    <?= $this->Form->end() ?>
</div>
