<?php
namespace App\SessionAdapters;
/*
 *Interface for the VirtualClassromms adapters
 *
 */
interface SessionAdapter
{
    /**
     * constructor LiveSession adapter
     *
     * @param $apiKey.
     */
    public function __construct($key);

	/**
     * scheduleSession method
     *
     * @param $session array of session info.
     * @return string POST response
     */
    public function scheduleSession($session);

    /**
     * requestSession method
     *
     * @param $session Session entity,
     * @param $session user entity,  
     * @return string POST response
     */
    public function requestSession($session, $user);

     /**
     * getSessionData method
     *
     * @param $session array of session info.
     * @return string POST response
     */
    public function getSessionData($session);

    /**
     * requestSession method
     *
     * @param $session Session entity,
     * @param $session user entity,  
     * @return string POST response
     */
    public function removeSession($session);

    /**
     * manage received request from endpoint
     *
     * @param $session user entity,  
     */
    public function manageRequest($requestArray);
}