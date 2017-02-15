<?php 
    use App\Controller\AppUsersController;
    $this->extend('/Element/Sessions/session_tabs');
    $this->assign('typeSession', "pending");
    $this->assign('userId', $user['id']);
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar',$this->Sidebar->tabs($user, AppUsersController::PROFILE_TABS_SESSIONS))?>
<?php $this->end('tabs') ?>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="pending">
        <div class="ed_inner_dashboard_info">
            <div class="ed_course_single_info">
                <?php if (!$sessions->count()):?>
                    <div class="alert alert-info"><?= __('There are no pending sessions to confirm.')?></div>
                <?php else: ?>
                    <div id="pagination-container">
                        <?php foreach ($sessions as $session): ?>
                            <div class="ed_add_students">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4">
                                        <?php echo $this->Img->display($session->user['user_image'], 'small', ['url' => ['action' => 'userProfile', 'controller' => 'AppUsers', $session->user['id']]]);?>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-8">
                                        <span><?= $this->Html->link(__($session->user['full_name']), ['action' => 'userProfile', $session->user['id'], 'controller' => 'AppUsers']) ?></span>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <?= $this->Html->link(__($session->subject), ['controller' => 'Sessions', 'plugin' => false, 'action' => 'view', $session->id]);?>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <?= $session->schedule->nice($timezone)?>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <?= $this->element('Sessions/accept_decline_buttons', ['session' => $session]);?>
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