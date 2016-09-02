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
	<?= __d('Sessions', "Hi {0}", $coach["first_name"]) ?>,
</p>
<p>
    <?= __d('Sessions', "{0} has requested a session with you", $user["full_name"]) ?>
</p>
<p>
    <?= __d('Sessions', "Email: {0}", $user["email"]) ?>
</p>
<p>
    <strong><?= __d('Sessions', "Session details:") ?></strong>
</p>
<p>
    <?= __d('Sessions', "Subject: {0}",$session["subject"]) ?>
</p>
<p>
    <?= __d('Sessions', "Date: {0}",h($user->birthdate)) ?>
</p>
<p>
    <?= __d('Sessions', "Comments: {0}",$session["comments"]) ?>
</p>