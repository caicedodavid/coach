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
<div class="users form large-10 medium-9 columns">
    <?= $this->Form->create($user); ?>
    <fieldset>
        <legend><?= __d('CakeDC/Users', 'Resend Validation email') ?></legend>
        <?php
        echo $this->Form->input('reference', ['label' => __d('CakeDC/Users', 'Email or username'), 'class' => 'form-control']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), '/login' ,['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>
