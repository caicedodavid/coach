<?php
    use App\Controller\AppUsersController;
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('userId', $user['id']);
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar',$this->Sidebar->tabs($user, AppUsersController::PROFILE_TABS_PURCHASES))?>
<?php $this->end('tabs') ?>

<ul class="nav nav-tabs" role="tablist">
    <?= $this->Html->tag('li', null, ['role' => 'presentation', 'class' => 'active'])?>
    <?= $this->AuthLink->link(__('Received Payments'), ['action' => 'purchases', $user->id, 'controller' => 'Payments', 'data-toggle' => 'tab', 'aria-controls' => 'approved', 'role' => 'tab']);?>
    <?= $this->Html->tag('/li')?>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="historic">
        <div class="ed_inner_dashboard_info">
            <div class="ed_course_single_info">
                <?php if (!$payments):?>
                    <div class="alert alert-info"><?= __('There are no purchases to show.')?></div>
                <?php else: ?>
                    <div class="row"><b>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <?= __('Purchases') ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <?= __('Price')?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <?= __('Purchase date') ?>
                        </div>
                    </b></div>
                    <div id="pagination-container">
                        <?php foreach ($payments as $payment): ?>
                            <div class="ed_add_students">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <?= $payment->session->subject;?>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                        <?= $this->Number->currency($payment->amount, 'USD');?>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                        <?= $payment->created;?>
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