<?php
    $type = $this->fetch('typeSession');
    $class1 = ($type === "approved" ? "active": null);
    $class2 = ($type === "pending" ? "active": null);
    $class3 = ($type === "historic" ? "active": null);
?>
<div class="sessions">
    <h3><?= __('Your scheduled sessions with coaches') ?></h3>
    <div class="container">
        <ul class="nav nav-tabs">
            <?php
            echo "<li class=" . $class1 . ">";
            echo $this->AuthLink->link(__('Upcomming'), ['action' => 'approved','controller' => 'Sessions', 'data-toggle'=>"tab"]);
            echo "</li>";
            echo "<li class=" . $class2 . ">";
            echo $this->AuthLink->link(__('Pending'), ['action' => 'pending','controller' => 'Sessions', 'data-toggle'=>"tab"]);
            echo "</li>";
            echo "<li class=" . $class3 . ">";
            echo $this->AuthLink->link(__('Historic'), ['action' => 'historic','controller' => 'Sessions', 'data-toggle'=>"tab"]);
            echo "</li>";
            ?>
        </ul>
        <?= $this->fetch('content') ?>
    </div>
</div>