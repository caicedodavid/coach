<nav class="large-3 medium-4 columns" id="actions-sidebar">
</nav>
<div class="sessions">
    <h3><?= __('Your scheduled sessions with coaches') ?></h3>
        <?php if (!$sessions->count()):?>
            <div class="alert alert-info"><?= __('You have no programed sessions')?></div>
        <?php else: ?>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Preloaded</a></li>
                    <li><a href="#tabs-2">Tab 1</a></li>
                </ul>
                <div id="tabs-1">
                    <?= $this->element('Sessions/approved');?>
                </div>
                <div id="tabs-2">
                    <?= "JAJAJA";?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
