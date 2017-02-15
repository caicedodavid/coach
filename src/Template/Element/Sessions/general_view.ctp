<tr>
    <th><?= __('Subject: ') ?></th>
    <td><?= h($session->subject) ?></td>
</tr>
<tr>
    <th><?= __('Date and time: ') ?></th>
    <td><?= h($session->schedule->nice($timezone)) ?></td>
</tr>
<tr>
    <th><?= __('Comments: ') ?></th>
    <td><?= $session->comments ?></td>
</tr>