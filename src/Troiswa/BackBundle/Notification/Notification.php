<?php

namespace Troiswa\BackBundle\Notification;

use Symfony\Component\HttpFoundation\Session\Session;
/**
 * Service de Notification
 * Class Notification
 * @package Troiswa\BackBundle\Notification
 */
class Notification{
    /**
     * @var session
     */
    protected $session;
    /**
     * Constructeur qui recevra
     * mon service session
     */
    public function __construct(Session $session){
        $this->session = $session;
    }
    /**
     * Methode qui va notifier une action
     *  + $message: le message de notre notification
     *  + $criticity:  success - danger - warning - info
     */
    public function notify($message, $criticity = "warning"){

        $this->session->getFlashBag()->add($criticity, $message);
    }
}