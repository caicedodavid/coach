<?php use Cake\Core\Configure;?>
<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => 'Payment Information']); ?>
<?php $this->end() ?>
<!--checkout start-->
<div class="ed_graysection ed_toppadder80 ed_bottompadder80">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="woo-cart-table">
					<?= $this->Form->create($paymentInfo,['class' => 'checkout woocommerce-checkout', 'id' => 'payment-form']) ?>
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
													'data-stripe' => 'number',
													'label' => 'Card number',
													'class' => 'form-control',
													'type' => 'text'
												]);?>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
											<div class="form-group">
												<?= $this->Form->input('cvc',[
													'data-stripe' => 'cvc',
													'label' => 'CVC',
													'class' => 'form-control',
													'type' => 'text',
													'placeholder' => 'XXX'
												]);?>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
											<div class="form-group">
                            					<?= $this->Form->input('exp_month', [
                                					'data-stripe' => "exp-month",
                               						'type' => 'text',
                               						'class' => 'form-control',
                               						'label' => false,
                               						'placeholder' => 'MM'
                               					])?>

											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
											<div class="form-group">
                            					<?= $this->Form->input('exp_year', [
                                					'data-stripe' => "exp-year",
                                					'class' => 'form-control',
                               						'type' => 'text',
                               						'class' => 'form-control',
                               						'label' => false,
                               						'placeholder' => 'YYYY'
                               					])?>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<span class="payment-errors"></span>
										</div>
										
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
											<!-- <button type="button" class="btn ed_btn pull-right ed_orange" id='stripe'>place order</button>-->
											<?= $this->Form->unlockField('token_id');?>
											<?= $this->Form->hidden('token_id', ['value' => $paymentInfo->token_id, 'id'=>'token-input']);?>
											<?= $this->Form->button(__('Submit'),['class'=>'btn ed_btn pull-right ed_orange']) ?>
											 
											</div>
										</div>
									</div>
								</div>				
							</div>
						</fieldset>
					<?= $this->Form->end() ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->Html->script('https://js.stripe.com/v2/',['block' => true]);?>
<?= $this->Html->scriptblock(
		sprintf("Stripe.setPublishableKey('%s');", Configure::read('Omnipay.Stripe.testPublishableKey')),['block' => true]
	);
?>