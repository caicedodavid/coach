<?= $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', 'Sessions');
    $type = $this->fetch('typeSession');
    $userId = $this->fetch('userId');
    $isApproved = ($type === "approved" ? "active": null);
    $isPending = ($type === "pending" ? "active": null);
    $isHistoric = ($type === "historic" ? "active": null);
?>

<ul class="nav nav-tabs" role="tablist">
    <?php
    echo "<li role='presentation' class=" . $isApproved . ">";
    echo $this->AuthLink->link(__('Upcomming'), ['action' => 'approved', $userId, 'controller' => 'Sessions', 'data-toggle' => 'tab', 'aria-controls' => 'approved', 'role' => 'tab']);
    echo "</li>";
    echo "<li role='presentation' class=" . $isPending . ">";
    echo $this->AuthLink->link(__('Pending'), ['action' => 'pending', $userId, 'controller' => 'Sessions', 'data-toggle' => 'tab', 'aria-controls' => 'pending', 'role' => 'tab']);
    echo "</li>";
    echo "<li role='presentation' class=" . $isHistoric . ">";
    echo $this->AuthLink->link(__('Historic'), ['action' => 'historic', $userId, 'controller' => 'Sessions', 'data-toggle'=>'tab', 'aria-controls' => 'historic', 'role' => 'tab']);
    echo "</li>";
    ?>
</ul>
<?= $this->fetch('content') ?>