<?php
namespace Troiswa\BackBundle\Listener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Troiswa\BackBundle\Entity\Product;
use Troiswa\BackBundle\Notification\Notification;

class ProductListener
{
    /**
     * @var Email
     */
    protected $notif;

    /**
     * Constructeur qui reçoie en argument le service Email
     * @param Email $email
     */
    public function __construct(Notification $notif)
    {
        $this->notif = $notif;
    }
    /**
     * Cette methode sera appelé depuis mon services.yml
     * ET reçoie en argument mon evenement Doctrine 2
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        // Je récupère mon objet après modification (update)
        $entity = $args->getObject();

        $em =  $args->getObjectManager(); // récupérer l'Entité Manager
        // Si mon objet est un objet de mon entité Product
        if ($entity instanceof Product) {

            // quand la quantité sera égal à 1
            if($entity->getQuantity() < 5)
            {
                $this->notif->notify('Attention, le produit'.$entity->getTitle().' a une quantité inférieur à 5');
            }
        }
    }
    /**
     * Avant la modification de mon objet
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args){
    }
    /**
     * Cette methode sera appelé depuis mon services.yml
     * ET reçoie en argument mon evenement Doctrine
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        // appel d'une methode dans une autre: appel de la methode postUpdate()
        $this->postUpdate($args);
    }
}