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
    <?= $this->Form->create('User') ?>
    <br>
    <br>
    <div class="row">
    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    		<fieldset>
    		    <?= $this->Form->input('email', ['class'=>'form-control']) ?>
    		</fieldset>
    		<?= $this->Form->button(__d('CakeDC/Users', 'Submit'), ['class' => 'btn ed_btn ed_orange small']); ?>
    		<?= $this->Form->end() ?>
    	</div>
    </div>
</div>
