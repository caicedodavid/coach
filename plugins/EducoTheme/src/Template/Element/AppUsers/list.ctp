<div class="ed_transprentbg ed_toppadder80">
    <div class="container">
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
                                    <h4><?= $this->Html->link(__($user->full_name), ['action' => 'coachProfile', $user->id, 'controller' => 'AppUsers']) ?></h4>
                                    <div class="ed_rating">
                                        <div class="ed_stardiv">
                                            <div class="col-md-2"><?php echo "<span class=\"stars\", data-rating=\"" . $user->rating ."\"></span>"?></div>
                                        </div>
                                    </div>
                                    <p><?= $user->profession ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <center><?php echo $this->element('endless_pagination'); ?></center>
        </div>
    </div>         
</div>