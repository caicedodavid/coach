<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
    <table class="vertical-table">
        <tr>
            <th><?= __('Subject: ') ?></th>
            <td><?= h($session->Subject) ?></td>
        </tr>
        <tr>
            <th><?= __('Date and time: ') ?></th>
            <td><?= h($session->schedule) ?></td>
        </tr>
        <tr>
            <th><?= __('Comments: ') ?></th>
            <td><?= $session->comments ?></td>
        </tr>
        <tr>
            <th><?= __('Assist to session: ') ?></th>
            <td><div class="col-md-1"><?= $this->Html->link(__('Assist to your class'), $url) ?></td>
        </tr>
    </table>
</div>