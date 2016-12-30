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
    <?= __("Email: {0}", $user["email"]) ?>
</p>
<p>
    <strong><?= __("Session details:") ?></strong>
</p>
<p>
    <?= __("Subject: {0}",$session["subject"]) ?>
</p>
<p>
    <?= __("Date: {0}",h($user->birthdate)) ?>
</p>
<p>
    <?= __("Comments: {0}",$session["comments"]) ?>
</p>