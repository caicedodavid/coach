<nav class="top-bar expanded" data-topbar role="navigation">
    <ul class="title-area large-3 medium-4 columns">
        <li class="name">
            <h1><a href=""><?= $this->fetch('title') ?></a></h1>
        </li>
    </ul>
    <div class="top-bar-section">
        <ul class="right">
            <?php if ($userAuth):?>
                <?= $this->Html->link(__d('CakeDC/Users', 'Logout'), ['action' => 'logout']);?>
            <?php else: ?>
                <?= $this->Html->link(__d('CakeDC/Users', 'Register'), ['action' => 'register']);?>
                <?= $this->Html->link(__d('CakeDC/Users', 'Login'), ['action' => 'login']);?>
            <?php endif; ?>
        </ul>
    </div>
</nav>