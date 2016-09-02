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
	<?= __d('Sessions', "Hi {0}", $user["first_name"]) ?>,
</p>
<p>
    <?= __d('Sessions', "You have scheduled a session with {0}", $coach["full_name"]) ?>
</p>
<p>
    <strong><?= __d('Sessions', "Details:") ?></strong>
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