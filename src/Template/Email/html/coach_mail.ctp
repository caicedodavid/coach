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
	<?= __d('Sessions', "Hi {0}", $coach["first_name"]) ?>,
</p>
<p>
    <?= __d('Sessions', "{0} has requested a session with you", $user["full_name"]) ?>
</p>
<p>
    <strong><?= __d('Sessions', "Coachee information:") ?></strong>
</p>
<p>
    <?= __d('Sessions', "Username:{0}",$user->username) ?>
</p>
<p>
    <?= __d('Sessions', "Email: {0}", $user->email) ?>
</p>
<p>
    <strong><?= __d('Sessions', "Session details:") ?></strong>
</p>
<p>
    <?= __d('Sessions', "Subject: {0}",h($session->subject)) ?>
</p>
<p>
    <?= __d('Sessions', "Date: {0}",h($user->birthdate)) ?>
</p>
<p>
    <?= __d('Sessions', "Comments: {0}",h($session->comments)) ?>
</p>
<p>
	<?php echo __d('Sessions', "Please, answer your request: ");
	 echo $this->Html->link(__d('AppUsers','My Sessions'), Router::url(['plugin' => false,'controller' => 'Sessions', 'action' => 'pending', 'prefix' => false],true), ['escape' => true]);?>
</p>