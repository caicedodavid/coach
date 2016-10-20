<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
    <table class="vertical-table">
        <tr>
            <td><?php echo $this->Img->display($user['user_image'], 'large');?></td>
        </tr>
        <tr>
            <th><?= __('Username: ') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th><?= __('Name: ') ?></th>
            <td><?= h($user->full_name) ?></td>
        </tr>
        <tr>
            <th><?= __('Birthdate: ') ?></th>
            <td><?= h($user->birthdate) ?></td>
        </tr>
        <tr>
            <th><?= __('Email: ') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Reputation: ') ?></th>
            <td><div class="col-md-2"><?php echo "<span class=\"stars\", data-rating=\"" . $user->rating ."\"></span>"?></div></td>
        </tr>
        <tr>
            <th><?= __('Description: ') ?></th>
            <td><?= $user->description ?></td>
        </tr>
        <tr>
            <th><?= __('Facebook: ') ?></th>
            <td><?php if($user->fb_account){echo $this->Html->link("facebook.com/".$user->fb_account,"https://www.facebook.com/".$user->fb_account, ['target' => '_blank']);} ?></td>
        </tr>
        <tr>
            <th><?= __('Twitter: ') ?></th>
            <td><?php if($user->tw_account){echo $this->Html->link("twitter.com/".$user->tw_account,"https://www.twitter.com/".$user->tw_account, ['target' => '_blank']);}?></td>
        </tr>
        <tr>
            <th><?= __('Request a Session: ') ?></th>
            <td><?= $this->Html->link(__d('Sessions', 'click here'), [$user->id,$user->full_name,'plugin' => false,'controller' => 'Sessions', 'action' => 'add', 'prefix' => false]);?></td>
        </tr>
    </table>
</div>