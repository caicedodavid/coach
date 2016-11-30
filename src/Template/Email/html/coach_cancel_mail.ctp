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
<p>
	<?= __("Hi {0}", $user->first_name) ?>,
</p>
<p>
    <?= __("The coach {0} has CANCELED a session with you", $coach->full_name) ?>
</p>
<p>
    <?= __("Because of this, you will be added {0} to your balance", $this->Number->currency($session->price));?>
</p>
<p>
    <?= __("Coach comments: {0}", $message);?>
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