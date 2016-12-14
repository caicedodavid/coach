<div class="ed_header_top">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="user-menu pull-left">
                    <ul class="single-line" style="list-style: none;">
                        <li><?= empty($userAuth) ? __('welcome guest') : $userAuth['first_name'] . ' :'?></li>
                    <?php if ($userAuth):?>
                        <li><?= $this->AuthLink->link(__('My Profile'),['action' => 'myProfile', 'controller' => 'AppUsers'])?> |</li>
                        <li><?= $this->AuthLink->link(__d('Users', '| Admin'), ['controller' => 'AppUsers', 'action' => 'index', 'prefix'=>'admin', 'plugin' => false]);?></li>
                    <?php endif;?>
                    </ul>
                </div>
                <div class="ed_info_wrapper pull-right">
                    <?php if (empty($userAuth)):?>
                        <a href="#" id="login_button">Login</a>
                        <div id="login_one" class="ed_login_form">
                            <h3>log in</h3>
                            <?= $this->Flash->render('auth') ?>
                            <?= $this->Form->create(null, ['url' => ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login'], 'class' => 'form']) ?>
                            <?= $this->Form->input('username', ['required' => true, 'class' => 'form-control']) ?>
                            <?= $this->Form->input('password', ['required' => true, 'class' => 'form-control']) ?>
                            <?= $this->Form->button(__d('CakeDC/Users', 'Login')); ?>
                            <?= $this->Html->link(__d('Users', 'Register'), ['plugin' =>false,'controller'=>'Pages','action' => 'display','type_user','ext'=>'ctp'])?>
                            <?= $this->Form->end() ?>
                            <br>
                            <center><?= implode('<br>', $this->User->socialLoginList()); ?></center>
                        </div>
                    <?php else:?>
                        <?= $this->Html->link(__('Logout'), '/logout')?>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>