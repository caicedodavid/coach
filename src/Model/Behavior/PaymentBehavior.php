<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Omnipay\Omnipay;
use Cake\Core\Configure;

class PaymentBehavior extends Behavior
{

	const SUCCESSFUL_STATUS = 'ok';
	const ERROR_STATUS = 'error';
	private $gateway = NULL;


	public function initialize(array $config)
	{
		parent::initialize($config);
    	$gateway = Omnipay::create('Stripe');
        $this->gateway = $gateway->setApiKey(Configure::read('Omnipay.Stripe.testSecretKey'));
	}

    /**
     * Add credit Card
	 *
     * Method that adds a credit card to stripe and returns a card token 
     *
     * @param tokenId
     * @return \Cake\Validation\Validator
     */
	public function addCreditCard($tokenId, $userToken)
	{
    	$response = $this->gateway->createCard([
			'token' => $tokenId,
        	'customerReference' => $userToken,
        ])
		->send();
        $responseArray = array();
	    if ($response->isSuccessful()) {
        // Find the card ID
	    	$responseArray['status'] = self::SUCCESSFUL_STATUS;
            $cardId = $response->getCardReference();
            $responseArray['card_id'] = $cardId;
        } else {
        	$responseArray['status'] = self::ERROR_STATUS;
        	$responseArray['message'] = $response->getMessage();
        }
        return $responseArray;
	}

    /**
     * Add User
	 *
     * Method that creates a user in stripe
     *
     * @param tokenId
     * @return \Cake\Validation\Validator
     */
	public function createUser($tokenId, $email)
	{
        $response = $this->gateway->createCard([
        	'token' => $tokenId,
        	'description' => $email,
        ])
        ->send();
        $responseArray = array();
	    if ($response->isSuccessful()) {
        // Find the card ID
	    	$responseArray['status'] = self::SUCCESSFUL_STATUS;
            $responseArray['card_id'] = $response->getCardReference();
            $responseArray['user_token'] = $response->getCustomerReference();
        } else {
        	$responseArray['status'] = self::ERROR_STATUS;
        	$responseArray['message'] = $response->getMessage();
        }
        return $responseArray;
	}

	 /**
     * Get Customer from omnipay
	 *
     * Method that creates a user in stripe
     *
     * @param tokenId
     * @return \Cake\Validation\Validator
     */
	public function getUserCards($customerId)
	{
		$response = $this->gateway->fetchCustomer([
        	'customerReference' => $customerId,
        ])
        ->send();
        return $response->getSources();
	}

	/**
     * Charge User
	 *
     * Method that charges a user in Stripe
     *
     * @param tokenId
     * @return \Cake\Validation\Validator
     */
	public function chargeUser($customerId, $amount)
	{
		if (is_null($customerId)) {
			return ['status' => self::ERROR_STATUS];
		}

   		$transaction = $this->gateway->purchase([
   		    'amount' => (float) $amount,
   		    'currency' => 'USD',
   		    'description' => 'This is a session purchase transaction.',
   		    'customerReference' => $customerId,
   		]);
   		$response = $transaction->send();
   		$responseArray = [];
   		if ($response->isSuccessful()) {
   			$responseArray['status'] = self::SUCCESSFUL_STATUS;
            $responseArray['card_id'] = $response->getSource()['id'];
   		    $responseArray['transaction_id'] = $response->getTransactionReference();
   		} else {
        	$responseArray['status'] = self::ERROR_STATUS;
        	$responseArray['message'] = $response->getMessage();
        }
        return $responseArray;
	}
}