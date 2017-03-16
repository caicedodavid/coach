<?php
    use App\Controller\AppUsersController;
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', $this->fetch('title'));
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar',$this->Sidebar->tabs($user, AppUsersController::PROFILE_TABS_PROFILE))?>
<?php $this->end('tabs') ?>
<?= $this->AuthLink->link(__d('AppUsers', 'Edit Profile'), ['plugin' => false, 'controller' => 'AppUsers', 'action' => 'edit', $user->id, 'prefix' => false], ['class' => 'btn ed_btn ed_orange pull-right small', 'id' => 'edit-profile']);?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#view" aria-controls="view" role="tab" data-toggle="tab"><?=__('Details')?></a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="view">
    <div class="ed_inner_dashboard_info">
        <h2><?= __('{0} ({1})', $user->full_name, $this->fetch('title'));?></h2>
        <table class="vertical-table">
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
                <td><?php if($user->fb_account){echo $this->Html->link("facebook.com/".$user->fb_account,"https://www.facebook.com/".$user->fb_account, ['target' => '_blank']);}?></   td>
            </tr>
            <tr>
                <th><?= __('Twitter: ') ?></th>
                <td><?php if($user->tw_account){echo $this->Html->link("twitter.com/".$user->tw_account,"https://www.twitter.com/".$user->tw_account, ['target' => '_blank']);}?></td>
            </tr>
        </table>
        <?= !$this->fetch('isCoach') ? $this->Html->link(__d('Sessions', 'Request a Session'), ['plugin' => false, 'prefix' => false, 'controller' => 'Sessions', 'action' => 'add', $user->id], ['class' => 'btn ed_btn ed_orange pull-right small']) : null;?>
    </div>
    </div>
</div>
<?=$editProfile ? $this->AuthLink->link(__d('CakeDC/Users', 'Reset Password'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'changePassword', $user->id], ['class' => 'reset-password pull-right']) : null?>
