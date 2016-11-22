<?= $this->extend('/Element/Liabilities/liability_tabs');
    $this->assign('typeSession', "paid");
    $this->assign('userId', $user['id']);
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar', [
        'tabs' => [
            'Profile' => [
                'null', ['action' => 'coachProfile', $user->id, 'controller' => 'AppUsers']
            ],
            'Topics' => [
                'null', ['action' => 'coachTopics', $user->id, 'controller' => 'Topics']
            ],
            'My Sessions' => [
                'null', ['action' => 'approved', $user->id, 'controller' => 'Sessions']
            ],
            'Payments' => [
                'active', ['action' => 'paidSessions', $user->id, 'controller' => 'Sessions']
            ]
        ],
        'user' => $user
    ])?>
<?php $this->end('tabs') ?>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="historic">
        <div class="ed_inner_dashboard_info">
            <div class="ed_course_single_info">
                <?php if (!$sessions->count()):?>
                    <div class="alert alert-info"><?= __('There are no sessions to show.')?></div>
                <?php else: ?>
                    <div id="pagination-container">
                        <?php foreach ($sessions as $session): ?>

                            <div class="ed_add_students">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span><?= $this->Html->link(__($session->subject), ['controller' => 'Sessions', 'plugin' => false, 'action' => 'viewHistoricCoach', $session->id]);?></span>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <?= $this->Html->link(__($session->user['full_name']), ['action' => 'userProfile', $session->user['id'], 'controller' => 'AppUsers']) ?>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <?= $session->schedule ?>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                <span><?= $this->Number->currency($session->topic->price, 'USD')?></span>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                <span><?=$session->liability->commission?></span>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <?= $session->liability->observation ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="ed_blog_bottom_pagination ed_toppadder40">
                                    <nav>
                                        <?php echo $this->element('classic_pagination'); ?>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>