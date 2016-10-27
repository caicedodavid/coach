<?= $this->extend('/Element/Sessions/session_tabs');
    $this->assign('typeSession', "historic");
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar', [
        'tabs' => [
            'Profile' => [
                'null', ['action' => 'coachProfile', $user->id, 'controller' => 'AppUsers']
            ],
            'Topics' => [
                'null', ['action' => 'coachTopics', 'controller' => 'Topics']
            ],
            'My Sessions' => [
                'active', ['action' => 'approved', $user->id, 'controller' => 'Sessions']
            ]
        ],
        'user' => $user
    ])?>
<?php $this->end('tabs') ?>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="approved">
        <div class="ed_inner_dashboard_info">
            <div class="ed_course_single_info">
                <?php if (!$historicSessions->count()):?>
                    <div class="alert alert-info"><?= __('There are no sessions to show.')?></div>
                <?php else: ?>
                    <div id="pagination-container">
                        <?php foreach ($historicSessions as $session): ?>
                            <div class="ed_add_students">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                                        <?php echo $this->Img->display($session->user['user_image'], 'small', ['url' => ['action' => 'view', 'controller' => 'AppUsers', $session->user['id']]]);?>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-8">
                                        <span><?= h($session->user['full_name']) ?></span>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <?= $this->Html->link(__($session->subject), ['controller' => 'Sessions', 'plugin' => false, 'action' => 'view', $session->id]);?>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                                <?= $session->schedule ?>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <span><?= $statusArray[$session->status]?></span>
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