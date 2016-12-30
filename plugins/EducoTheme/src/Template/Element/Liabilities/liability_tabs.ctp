<?= $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', 'Sessions');
    $type = $this->fetch('typeSession');
    $userId = $this->fetch('userId');
    $isPaid = ($type === "paid" ? "active": null);
    $isPending = ($type === "unpaid" ? "active": null);
?>

<ul class="nav nav-tabs" role="tablist">
    <?= $this->Html->tag('li', null, ['role' => 'presentation', 'class' => $isPaid])?>
    <?= $this->AuthLink->link(__('Received Payments'), ['action' => 'paidSessions', $userId, 'controller' => 'Sessions', 'data-toggle' => 'tab', 'aria-controls' => 'approved', 'role' => 'tab']);?>
    <?= $this->Html->tag('/li')?>
    <?= $this->Html->tag('li', null, ['role' => 'presentation', 'class' => $isPending])?>
    <?= $this->AuthLink->link(__('Pending Payments'), ['action' => 'unpaidSessions', $userId, 'controller' => 'Sessions', 'data-toggle' => 'tab', 'aria-controls' => 'pending', 'role' => 'tab']);?>
    <?= $this->Html->tag('/li')?>
</ul>
<?= $this->fetch('content') ?>