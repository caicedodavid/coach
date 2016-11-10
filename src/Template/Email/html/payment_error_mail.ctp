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
	<?= __d('Sessions', "Hi {0}", $user->first_name) ?>,
</p>
<p>
    <?= __d('Sessions', "The session could not be scheduled because there was a payment error. This was the response:") ?>
</p>
<p>
    <strong><?= __d('Sessions', $message) ?>
</p>
<p>
    <strong><?= __d('Sessions', "Coach information:") ?></strong>
</p>
<p>
    <?= __d('Sessions', "Username:{0}",$coach->username) ?>
</p>
<p>
    <?= __d('Sessions', "Email: {0}", $coach->email) ?>
</p>
<p>
    <strong><?= __d('Sessions', "Session details:") ?></strong>
</p>
<p>
    <?= __d('Sessions', "Subject: {0}",h($session->subject)) ?>
</p>
<p>
    <?= __d('Sessions', "Date: {0}",h($session->schedule)) ?>
</p>
<p>
    <?= __d('Sessions', "Comments: {0}",h($session->comments)) ?>
</p>