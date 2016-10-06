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
        <tr>
            <th><?= __('Assist to session: ') ?></th>
            <td><div class="col-md-1">
                <?php if ($url):
                    echo $this->Html->link(__('Assist to your class'), $url, ['id' => 'start', 'name'=>$session->id]);
                else:
                    echo __('Your class is not available yet');
                endif;
                ?>
            </td>
        </tr>
    </table>
</div>