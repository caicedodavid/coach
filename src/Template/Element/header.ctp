<nav class="top-bar expanded" data-topbar role="navigation">
    <ul class="title-area large-3 medium-4 columns">
        <li class="name">
            <h1><a href=""><?= $this->fetch('title') ?></a></h1>
        </li>
    </ul>
    <div class="top-bar-section">
        <ul class="right">
            <?php if ($userAuth):?>

            <?php else: ?>
                <?= $this->Html->link(__d('CakeDC/Users', 'Register'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'register']);?>
                <?= $this->Html->link(__d('CakeDC/Users', 'Login'), ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login']);?>
            <?php endif; ?>
        </ul>
    </div>
</nav>