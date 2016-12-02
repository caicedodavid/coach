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
	<?= __("Hi {0}", $user->first_name) ?>,
</p>
<p>
    <?= __("The coach {0} has ACCEPTED a session with you", $coach->full_name) ?>
</p>
<p>
    <?= __("Because of this, you will be charged {0}.", $this->Number->currency(isset($session->topic['price']) ? $session->topic['price'] : 10, 'USD'));?>
</p>
<p>
    <strong><?= __("Coach information:") ?></strong>
</p>
<p>
    <?= __("Username:{0}",$coach->username) ?>
</p>
<p>
    <?= __("Email: {0}", $coach->email) ?>
</p>
<p>
    <strong><?= __("Session details:") ?></strong>
</p>
<p>
    <?= __("Subject: {0}",h($session->subject)) ?>
</p>
<p>
    <?= __("Date: {0}",h($session->schedule)) ?>
</p>
<p>
    <?= __("Comments: {0}",h($session->comments)) ?>
</p>
<p>
	<?php echo __("View your Session");
	 echo $this->Html->link(__d('AppUsers','My Session'), Router::url(['controller' => 'Sessions', 'plugin' => false, 'action' => 'view', $session->id, 'prefix' => false],true), ['escape' => true]);?>
</p>