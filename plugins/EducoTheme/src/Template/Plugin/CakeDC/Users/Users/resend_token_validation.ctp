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
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="users form">
            <?= $this->Form->create($user); ?>
            <fieldset>
                <legend><?= __d('CakeDC/Users', 'Resend Validation email') ?></legend>
                <?php
                echo $this->Form->input('reference', ['label' => __d('CakeDC/Users', 'Email or username'), 'class' => 'form-control']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'), ['class' => 'btn ed_btn pull-right ed_orange pull-right small']) ?>
            <?= $this->Html->link(__('Cancel'), '/login' ,['class' => 'btn ed_btn ed_green pull-right small']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>