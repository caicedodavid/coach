<?php
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', 'Coach');
?>

<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar', [
        'tabs' => [
            'Profile' => [
                'active', ['action' => 'coachProfile', $user->id, 'controller' => 'AppUsers']
            ],
            'Topics' => [
                'null', ['action' => 'coachTopics', $user->id, 'controller' => 'Topics']
            ],
            'My Sessions' => [
                'null', ['action' => 'approvedCoach', $user->id, 'controller' => 'Sessions']
            ]
        ],
        'user' => $user
    ])?>
<?php $this->end('tabs') ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#view" aria-controls="view" role="tab" data-toggle="tab">Coach Detail</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="view">
    <div class="ed_inner_dashboard_info">
        <h2><?= $user->full_name . ' (Coach)';?></h2>
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
            <tr>
                <th><?= __('Request a Session: ') ?></th>
                <td><?= $this->Html->link(__d('Sessions', 'click here'), [$user->id,$user->full_name,'plugin' => false,'controller' => 'Sessions', 'action' => 'add', 'prefix'=>    false]);?></td>
            </tr>
        </table>
    </div>
    </div>
<div>