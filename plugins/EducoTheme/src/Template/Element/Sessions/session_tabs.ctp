<?= $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', 'Coach');
    $type = $this->fetch('typeSession');
    $class1 = ($type === "approved" ? "active": null);
    $class2 = ($type === "pending" ? "active": null);
    $class3 = ($type === "historic" ? "active": null);
?>

<ul class="nav nav-tabs" role="tablist">
    <?php
    echo "<li role='presentation' class=" . $class1 . ">";
    echo $this->AuthLink->link(__('Upcomming'), ['action' => 'approvedCoach','controller' => 'Sessions', 'data-toggle' => 'tab', 'aria-controls' => 'approved', 'role' => 'tab']);
    echo $this->AuthLink->link(__('Upcomming'), ['action' => 'approvedUser','controller' => 'Sessions', 'data-toggle' => 'tab', 'aria-controls' => 'approved', 'role' => 'tab']);
    echo "</li>";
    echo "<li role='presentation' class=" . $class2 . ">";
    echo $this->AuthLink->link(__('Pending'), ['action' => 'pending','controller' => 'Sessions', 'data-toggle' => 'tab', 'aria-controls' => 'pending', 'role' => 'tab']);
    echo "</li>";
    echo "<li role='presentation' class=" . $class3 . ">";
    echo $this->AuthLink->link(__('Historic'), ['action' => 'historic','controller' => 'Sessions', 'data-toggle'=>'tab', 'aria-controls' => 'historic', 'role' => 'tab']);
    echo "</li>";
    ?>
</ul>
<?= $this->fetch('content') ?>