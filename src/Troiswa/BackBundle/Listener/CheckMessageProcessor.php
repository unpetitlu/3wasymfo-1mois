<?php
// src/OC/PlatformBundle/Bigbrother/CensorshipProcessor.php

namespace Troiswa\BackBundle\Listener;

use Symfony\Component\Security\Core\User\UserInterface;

class CheckMessageProcessor
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    // Méthode pour notifier par e-mail un administrateur
    public function notifyEmail($message, UserInterface $user)
    {
        /*
        $message = \Swift_Message::newInstance()
            ->setSubject("Nouveau message d'un utilisateur surveillé")
            ->setFrom('admin@votresite.com')
            ->setTo('admin@votresite.com')
            ->setBody("L'utilisateur surveillé '".$user->getUsername()."' a posté le message suivant : '".$message."'");

        $this->mailer->send($message);
        */
    }

    // Méthode pour censurer un message (supprimer les mots interdits)
    public function censorMessage($message)
    {
        $message = str_replace(array('top secret', 'mot interdit'), '', $message);

        return $message;
    }
}