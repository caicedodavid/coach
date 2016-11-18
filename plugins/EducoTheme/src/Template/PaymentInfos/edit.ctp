<?php use Cake\Core\Configure;?>
<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => 'Payment Information']); ?>
<?php $this->end() ?>
<!--checkout start-->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="woo-cart-table">
			<?= $this->Form->create($paymentInfo,['class' => 'checkout woocommerce-checkout']) ?>
				<fieldset>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h3 class="checkout-heading">Billing Details</h3>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<?= $this->Form->input('name',['class' => 'form-control', 'label' => 'Name on card']);?>
									</div>
								</div>	    
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<?= $this->Form->input('address1',['class' => 'form-control']);?>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<?= $this->Form->input('address2',['class' => 'form-control']);?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<div class="form-group">
										<?= $this->Form->input('country',['class' => 'form-control']);?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<div class="form-group">
										<?= $this->Form->input('state',['class' => 'form-control']);?>
									</div>
								</div>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
									<div class="form-group">
										<?= $this->Form->input('city',['class' => 'form-control']);?>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
									<div class="form-group">
										<?= $this->Form->input('zipcode',['class' => 'form-control']);?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h3 class="checkout-heading">Card Details</h3>
							<div class="row">
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
									<div class="form-group">
										<?= $this->Form->input('card_number',[
											'label' => 'Card number',
											'class' => 'form-control',
											'type' => 'text',
											'maxlength' => '19',
											'required' => false,
											'placeholder' => '●●● - ●●●● - ●●●● - ' . $card['last4']
										]);?>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
									<div class="form-group">
										<?= $this->Form->input('cvc',[
											'label' => 'CVC',
											'class' => 'form-control',
											'type' => 'text',
											'placeholder' => 'XXX',
											'pattern' => '[0-9]{3}',
											'maxlength' => '3',
											'required' => false
										]);?>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
									<div class="form-group">
                    					<?= $this->Form->input('exp_month', [
                       						'type' => 'text',
                       						'class' => 'form-control',
                       						'label' => false,
                       						'placeholder' => 'MM',
                       						'pattern' => '[0-9]{2}',
                       						'value' => sprintf("%02d", $card['exp_month']),
                       						'maxlength' => '2'
                       					])?>

									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
									<div class="form-group">
                    					<?= $this->Form->input('exp_year', [
                        					'class' => 'form-control',
                       						'type' => 'text',
                       						'class' => 'form-control',
                       						'label' => false,
                       						'placeholder' => 'YYYY',
                       						'pattern' => '[0-9]{4}',
                       						'value' => $card['exp_year'],
                       						'maxlength' => '4'
                       					])?>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<span class="payment-errors"></span>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
									    <?php if(!$paymentInfo->is_default):?>
									    	<?= $this->Form->input('is_default', [
                       							'label' => 'Default card',
                       						])?>
                       					<?php else:?>
                       						<?php
                       							echo $this->Html->tag('div', null, ['class' => 'input checkbox']);
                       							// Not allowed change - submit value..
												echo $this->Form->hidden('is_default', [
                       								'value' => true,
                       							]);
                       							echo '<label>';
												// .. and show user the value being submitted
												echo '<input type="checkbox" disabled checked="checked"> Default card';
												echo '</label>';
												$this->Html->tag('/div', null);
                       						?>
                       					<?php endif;?>
                       				</div>
								</div>
								
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
									<!-- <button type="button" class="btn ed_btn pull-right ed_orange" id='stripe'>place order</button>-->
									</div>
								</div>
							</div>
						</div>				
					</div>
				</fieldset>
			<?= $this->Html->link(__('Cancel'), ['controller' => 'PaymentInfos', 'action' => 'cards', $this->request->session()->read('Auth.User.id')],['class' => 'ed_btn ed_green btn btn-default pull-right']) ?>
			<?= $this->Form->button(__('Submit'),['class'=>'btn ed_btn pull-right ed_orange']) ?>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
<?php $this->start('bottomScript'); ?>
	<?= $this->Html->script('payments');?>
<?php $this->end('bottomScript'); ?>