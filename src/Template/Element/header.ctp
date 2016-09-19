<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Coach</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
               <?php if ($userAuth):?>
                    <li class="pull-right dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $userAuth['first_name']?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <?= $this->Html->link(__d('AppUsers','Edit Profile'), ['plugin' => false,'controller' => 'AppUsers', 'action' => 'edit', 'prefix' => false]);?>
                                <?= $this->Html->link(__d('AppUsers','My Sessions'), ['plugin' => false,'controller' => 'Sessions', 'action' => 'index', 'prefix' => false]);?>
                                <?= $this->Html->link(__d('CakeDC/Users', 'Logout'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'logout', 'prefix'=>false]);?>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <?= $this->AuthLink->link(__d('AppUsers', 'Coaches'), ['plugin' => false,'controller' => 'AppUsers', 'action' => 'coaches', 'prefix' => false]);?>
                    </li>
                   <?php if ($this->AuthLink->isAuthorized(['controller' => 'Users', 'action' => 'index', 'prefix'=>'admin', 'plugin' => false])):?>
                       <li class="pull-right dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= __('Admin')?> <span class="caret"></span></a>
                           <ul class="dropdown-menu">
                               <li>
                                   <?= $this->AuthLink->link(__d('Users', 'Users'), ['controller' => 'Users', 'action' => 'index', 'prefix'=>'admin', 'plugin' => false]);?>
                               </li>
                           </ul>
                       </li>
                   <?php endif; ?>
                <?php else: ?>
                    <li>
                        <?= $this->Html->link(__d('CakeDC/Users', 'Login'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login', 'prefix' => false]);?>
                    </li>
                <?php endif; ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
