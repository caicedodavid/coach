<?php
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', 'Coachee');
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar', [
        'tabs' => [
            'Profile' => [
                'null', ['action' => 'userProfile', $user->id, 'controller' => 'AppUsers']
            ],
            'My Sessions' => [
                'null', ['action' => 'approved', $user->id, 'controller' => 'Sessions']
            ],
            'Payment Information' => [
                'active', ['action' => 'cards', $user->id, 'controller' => 'PaymentInfos']
            ]
        ],
        'user' => $user
    ])?>
<?php $this->end('tabs') ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#view" aria-controls="view" role="tab" data-toggle="tab">User Detail</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="view">
    <div class="ed_inner_dashboard_info">
        <div class="paymentInfos index large-9 medium-8 columns content">
            <h3><?= __('Payment Information') ?></h3>
            <?php if (!$cards):?>
                <div class="alert alert-info"><?= __('You have no registerd cards.')?></div>
            <?php else: ?>
                <div id= 'pagination-container'>
                    <table cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Cardholder') ?></th>
                                <th scope="col"><?= __('Card') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paymentInfos as $paymentInfo): ?>
                            <tr>
                                <td><?= h($paymentInfo->name) ?></td>
                                <td><?= h('●●● - ●●●● - ●●●● - ' . $cards[$paymentInfo->external_card_id]['last4']) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('Edit'), '#') ?>
                                    <?= $this->Html->link(__('Delete'), '#') ?>
                                    <!--<?= $this->Form->postLink(__('Delete'), '#', ['confirm' => __('Are you sure you want to delete # {0}?', 
                                    $paymentInfo->id)]);?>-->
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="paginator ed_blog_bottom_pagination">
                            <nav>
                                <?php echo $this->element('classic_pagination'); ?>
                            </nav>
                        </div>
                    </div>
            <?php endif;?>
        </div>
        <?= $this->AuthLink->link(__('Add Card'), ['plugin' => false, 'action' =>'add', 'controller' => 'PaymentInfos'], ['class' => 'btn ed_btn pull-right ed_orange pull-right small']) ?>
    </div>
    </div>
</div>
