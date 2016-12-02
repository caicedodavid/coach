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
	<?= __("Hi {0}", $coach->first_name) ?>,
</p>
<p>
    <?= __("Your sessions have been paid. Yo have received {0} for the following sessions:", $this->Number->currency($amount, 'USD')) ?>
</p>
<?php foreach ($sessions as $session):?>
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
        <?= __("Coachee: {0}",h($session->user->username)) ?>
    </p>
<?php endforeach;?>
<p>
    <strong><?= __("Observation:") ?></strong>
    <?= $message?>
</p>