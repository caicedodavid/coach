<?php
    use App\Controller\AppUsersController;
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', __('Payment Information'));
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar',$this->Sidebar->tabs($user, AppUsersController::PROFILE_TABS_PAYMENT_INFOS))?>
<?php $this->end('tabs') ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#view" aria-controls="view" role="tab" data-toggle="tab"><?=__('registered cards')?></a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="view">
    <div class="ed_inner_dashboard_info">
        <div class="paymentInfos index large-9 medium-8 columns content">
            <?php if (!$paymentInfos->count()):?>
                <div class="alert alert-info"><?= __('You have no registerd cards.')?></div>
            <?php else: ?>
                <div id= 'pagination-container'>
                    <table cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col"><?= __('Cardholder') ?></th>
                                <th scope="col"><?= __('Card') ?></th>
                                <th scope="col"><?= __('Default') ?></th>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paymentInfos as $paymentInfo): ?>
                            <tr>
                                <td><?= h($paymentInfo->name) ?></td>
                                <td><?= h('●●● - ●●●● - ●●●● - ' . $cards[$paymentInfo->external_card_id]['last4']) ?></td>
                                <td><?= $paymentInfo->is_default ? $this->Html->image("check.png") : null;?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('Edit'), ['controller' => 'PaymentInfos', 'action' => 'edit', $paymentInfo->id, serialize($cards[$paymentInfo->external_card_id])])?>
                                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PaymentInfos', 'action' => 'delete', $paymentInfo->id], ['confirm' => __('Are you sure you want to delete this card?', 
                                    $paymentInfo->id)]);?>
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
