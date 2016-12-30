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
use Cake\Routing\Router;
?>
<p>
	<?= __("Hi {0}", $coach["first_name"]) ?>,
</p>
<p>
    <?= __("{0} has requested a session with you", $user["full_name"]) ?>
</p>
<p>
    <strong><?= __("Coachee information:") ?></strong>
</p>
<p>
    <?= __("Username:{0}",$user->username) ?>
</p>
<p>
    <?= __("Email: {0}", $user->email) ?>
</p>
<p>
    <strong><?= __("Session details:") ?></strong>
</p>
<p>
    <?= __("Subject: {0}",h($session->subject)) ?>
</p>
<p>
    <?= __("Date: {0}",h($user->birthdate)) ?>
</p>
<p>
    <?= __("Comments: {0}",h($session->comments)) ?>
</p>
<p>
	<?php echo __("Please, answer your request: ");
	 echo $this->Html->link(__d('AppUsers','Requested Session'), Router::url(['controller' => 'Sessions', 'plugin' => false, 'action' => 'view', $session->id, 'prefix' => false],true), ['escape' => true]);?>
</p>