<?php?>
<div class="row">
    <?php if (!$users->count()):?>
        <div class="alert alert-info"><?= __('There are no available coaches')?></div>
    <?php else: ?>
        <div id="items">
            <?php foreach ($users as $user): ?>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="ed_team_member">
                        <div class="ed_team_member_img">
                            <?php echo $this->Img->display($user['user_image'], 'medium', ['url' => ['action' => 'coachProfile', $user->id]]);?>
                        </div>
                        <div class="ed_team_member_description">
                            <div class="coach-name">
                                <h4><?= $this->Html->link(__($user->full_name), ['action' => 'coachProfile', $user->id, 'controller' => 'AppUsers']) ?></h4>
                            </div>
                            <div class="ed_rating">
                                <div class="ed_stardiv">
                                    <div class="col-md-2"><?= $this->element('rating', ["rating" => $user->rating])?></div>
                                </div>
                            </div>
                            <p><?= $user->profession ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <center><?php echo $this->element('endless_pagination'); ?></center>
    <?php endif; ?>
</div>

