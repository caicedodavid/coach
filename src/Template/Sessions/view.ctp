<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
    <table class="vertical-table">
        <?= $this->element('Sessions/general_view', ["session" => $session]);?>
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