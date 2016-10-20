<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
    <table class="vertical-table">
        <?= $this->element('Sessions/general_view', ["session" => $session]);?>
        <tr>
            <th><?= __('Assist to session: ') ?></th>
            <td>
                <?php if ($url):
                    echo $this->Html->link(__('Assist to your class'), $url, ['id' => 'start', 'name'=>$session->id]);
                else:
                    echo __('Your class is not available yet');
                endif;
                ?>
            </td>
        </tr>
    </table>
    <?php if (!$url):
        echo $this->element('Sessions/cancel_session_button', ['session' => $session, 'button' => 'Cancel', 'message' => 'Are you sure you want to cancel this session?']);
    endif;?>
</div>