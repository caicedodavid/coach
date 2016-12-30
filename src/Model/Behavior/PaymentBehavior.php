<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Omnipay\Omnipay;
use Cake\Core\Configure;
use Omnipay\Common\CreditCard;

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
     * @param $tokenId
     * @param $customerId id in payment api
     * @return $response Array
     */
	public function addCreditCard($tokenId, $customerId)
	{
    	$response = $this->gateway->createCard([
			'token' => $tokenId,
        	'customerReference' => $customerId,
        ])
		->send();
        $responseArray = array();
	    if ($response->isSuccessful()) {
        // Find the card ID
	    	$responseArray['status'] = self::SUCCESSFUL_STATUS;
            $cardId = $response->getCardReference();
            $responseArray['card_id'] = $cardId;
            $this->setAsDefaultCard($customerId, $cardId);
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
     * @param $tokenId
     * @param $email user email
     * @return response Array
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
     * Get Customer cards 
	 *
     * Method that gets a user in payment api and returns his cards
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
     * @param $customerId Id of the customer in payment api
     * @param $amount amount to be charged
     * @return $response Arraay
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

    /**
     * Update Card information
     *
     * Method that charges a user in Stripe
     *
     * @param $paymentInfo paymentInfo entity
     * @param $cardData card data form the form
     * @return $response Array
     */
    public function updateCard($paymentInfo, $cardData)
    {
        if($cardData['card_number'] xor $cardData['cvc']) {
            $responseArray['status'] = self::ERROR_STATUS;
            $responseArray['message'] = 'Both card number and CVC have to be updated at the same time';
            return $responseArray;
        } 
        #if there is a change in the cardnumber or cvc, a new card must be created
        if($cardData['card_number'] and $cardData['cvc']) {
            $response = $this->createCard($paymentInfo, $cardData);
        } else {
            $card = new CreditCard([
                'expiryMonth'  => $cardData['exp_month'],
                'expiryYear'   => $cardData['exp_year'],
            ]);
            $transaction = $this->gateway->updateCard([
                'customerReference' => $paymentInfo->app_user->external_payment_id,
                'cardReference' => $paymentInfo->external_card_id,
                'card' => $card
            ]);
            $response = $transaction->send();
        }

        if ($response->isSuccessful()) {
            $responseArray['status'] = self::SUCCESSFUL_STATUS;
            $responseArray['card_id'] = $response->getCardReference();
        } else {
            $responseArray['status'] = self::ERROR_STATUS;
            $responseArray['message'] = $response->getMessage();
        }
        return $responseArray;
    }

    /**
     * Create Card
     *
     * Method that creates a card wihout a token 
     *
     * @param $paymentInfo paymentInfo entity
     * @param $cardData card data form the form
     * @return $response Array
     */
    public function createCard($paymentInfo, $cardData)
    {
        $newCard = new CreditCard([
            'expiryMonth'  => $cardData['exp_month'],
            'expiryYear'   => $cardData['exp_year'],
            'cvv' => $cardData['cvc'],
            'number' => $cardData['card_number'],
        ]);
        $transaction = $this->gateway->createCard([
            'card' => $newCard,
            'customerReference' => $paymentInfo->app_user->external_payment_id,
        ]);
        return $transaction->send();
    }

    /**
     * SetAsDefaultCard
     *
     * Method that makes a card the default card of a user
     *
     * @param $customerId Id of customer in payment API
     * @param $cardId card data form the form
     * @return $response Array
     */
    public function setAsDefaultCard($customerId, $cardId)
    {
        $transaction = $this->gateway->updateCustomer([
            'cardReference' => $cardId,
            'customerReference' => $customerId,
        ]);
        return $transaction->send();
    }            
      
}