<?php
namespace App\SessionAdapters;
/*
 *Interface for the VirtualClassromms adapters
 *
 */
interface SessionAdapter
{
    public function scheduleSession($session);

    public function requestSession($session, $user);

}