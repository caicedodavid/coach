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
    public function userMail($user, $coach, $session)
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
    public function coachMail($user, $coach, $session)
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

    public function approveMail($user, $coach, $session, $message = null)
    {
        $this
            ->to($user["email"])
            ->subject(sprintf('%s accepted a session with you', $coach["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session'));
    }

    /**
     * Send the user an Email when the coach rejects a requested session
     *
     * @param User entity, coach entity, session entity
     *
     */ 

    public function rejectMail($user, $coach, $session, $message = null)
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
    public function coachCancelMail($user, $coach, $session, $message = null)
    {
        $this
            ->to($user["email"])
            ->subject(sprintf('The coach %s canceled a session with you', $coach["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session','message'));
    }

    /**
     * Send the coach an Email when the user cancels a requested session
     *
     * @param User entity, coach entity, session entity
     *
     */ 
    public function userCancelMail($user, $coach, $session, $message = null)
    {
        $this
            ->to($coach["email"])
            ->subject(sprintf('The coachee %s canceled a session with you', $user["full_name"]))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session'));
    }

    /**
     * Send the user an Email when there was a payment error
     *
     * @param User entity, coach entity, session entity
     *
     */ 
    public function paymentErrorMail($user, $coach, $session, $message)
    {
        $this
            ->to($user["email"])
            ->subject(sprintf('The Session could not be scheduled'))
            ->emailFormat('html')
            ->viewVars(compact('user','coach','session','message'));
    }
}