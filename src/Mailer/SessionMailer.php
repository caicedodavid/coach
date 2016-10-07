<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class SessionMailer extends Mailer
{
    /**
     * Send a user an Email about a requested session
     *
     * @param User entity, coach entity, session entity
     *
     */  
    public function userMail($user,$coach,$session)
    {
        $this
            ->to($user["email"])
            ->subject(sprintf('Request session with %s', $coach["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session'));
    }
    /**
     * Send a coach an Email about a requested session
     *
     * @param User entity, coach entity, session entity
     *
     */ 
    public function coachMail($user,$coach,$session)
    {
        $this
            ->to($coach["email"])
            ->subject(sprintf('%s requested a session with you', $user["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session'));
    }
    /**
     * Send the user an Email when the coach accepts a requested session
     *
     * @param User entity, coach entity, session entity
     *
     */ 

    public function approveMail($user,$coach,$session)
    {
        $this
            ->to($user["email"])
            ->subject(sprintf('%s aceppted a session with you', $coach["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session'));
    }

    /**
     * Send the user an Email when the coach rejects a requested session
     *
     * @param User entity, coach entity, session entity
     *
     */ 

    public function rejectMail($user,$coach,$session)
    {
        $this
            ->to($user["email"])
            ->subject(sprintf('%s rejected a session with you', $coach["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session'));
    }

    /**
     * Send the user an Email when the coach cancels a requested session
     *
     * @param User entity, coach entity, session entity
     *
     */ 
    public function coachCancelMail($user,$coach,$session)
    {
        $this
            ->to($user["email"])
            ->subject(sprintf('The coach %s canceled a session with you', $coach["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session'));
    }

    /**
     * Send the user an Email when the user cancels a requested session
     *
     * @param User entity, coach entity, session entity
     *
     */ 
    public function userCancelMail($user,$coach,$session)
    {
        $this
            ->to($coach["email"])
            ->subject(sprintf('The coachee %s canceled a session with you', $user["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session'));
    }


}