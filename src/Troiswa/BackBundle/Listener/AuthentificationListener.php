<?php
namespace Troiswa\BackBundle\Listener;


use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
/**
 * Class AuthentificationListener
 * @package Store\BackendBundle\Listener
 */
class AuthentificationListener
{
    /**
     * @var EntityManager|null
     */
    protected $em;
    /**
     * @var null|Security
     */
    protected $tokenStorage;

    /**
     * Le constructeur de ma classe
     * avec 2 arguments: l'Entité Manager et le Contexte de Sécurité
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $em,
                                TokenStorage $tokenStorage)
    {
        //je stocke dans 2 attributs les services récupérés
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * Methode qui est déclenché après l'événement InteractiveLogin
     * qui est  l'action de login dans la sécurité
     * @param InteractiveLoginEvent $event
     */
    public function onAuthenticationSuccess(InteractiveLoginEvent $event)
    {
        $now = new \DateTime('now');
        // récupére l'utilisateur  courant connecté
        $user =$this->tokenStorage->getToken()->getUser();

        $route = 'troiswa_back_admin';

        // met à jour la date de connexion de l'utilisateur
        $user->setDateAuth($now);
        //enregistre mon utilisateur avec sa date modifié
        $this->em->persist($user);
        $this->em->flush();
    }
}