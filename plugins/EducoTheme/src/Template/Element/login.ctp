<div class="ed_header_top">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="user-menu pull-left">
                    <ul class="single-line" style="list-style: none;">
                        <li><?= empty($userAuth) ? __('welcome guest') : $userAuth['first_name']?></li>
                    </ul>
                </div>
                <div class="ed_info_wrapper pull-right">
                    <?php if (empty($userAuth)):?>
                        <a href="#" id="login_button"><?=__('Login')?></a>
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id ="languague-button"><?= $selectedLanguage ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <?php foreach($availableLanguages as $lang => $alias): ?>
                                    <?php if ($alias === $selectedLanguage) continue; ?>
                                    <?php $pass = $this->request->param('pass') ?>
                                    <li>
                                        <?= $this->Html->link($alias, [
                                                'language' => $lang,
                                                'controller' => $this->request->param('controller'),
                                                'action' => $this->request->param('action'),
                                                isset($pass[0]) ? $pass[0] : null
                                            ]) ?>
                                    </li>
                                <?php endforeach; ?>
                            </li>
                        </ul>
                </div>
            </div>
        </div>
    </div>
</div>