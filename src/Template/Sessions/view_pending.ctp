<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
    <table class="vertical-table">
        <tr>
            <th><?= __('Subject: ') ?></th>
            <td><?= h($session->subject) ?></td>
        </tr>
        <tr>
            <th><?= __('Date and time: ') ?></th>
            <td><?= h($session->schedule) ?></td>
        </tr>
        <tr>
            <th><?= __('Comments: ') ?></th>
            <td><?= $session->comments ?></td>
        </tr>
    </table>
</div>