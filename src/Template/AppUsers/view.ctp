<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
    <table class="vertical-table">
        <tr>
            <td>
            <?php 
                if ($user['user_image']===NULL){
                    echo $this->Image->display($blank, 'large');
                }
                else{
                    echo $this->Image->display($user['user_image'], 'large'); 
                }
            ?></td>
        </tr>
        <tr>
            <th><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($user->full_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Birthdate') ?></th>
            <td><?= h($user->birthdate) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Description') ?></th>
            <td><?= $user->description ?></td>
        </tr>
        <tr>
            <th><?= __('Facebook') ?></th>
            <td><?= $this->Html->link("facebook.com/".$user->fb_account,"https://www.facebook.com/".$user->fb_account) ?></td>
        </tr>
        <tr>
            <th><?= __('Twitter') ?></th>
            <td><?= $this->Html->link("twitter.com/".$user->tw_account,"https://www.twitter.com/".$user->tw_account) ?></td>
        </tr>
    </table>
</div>