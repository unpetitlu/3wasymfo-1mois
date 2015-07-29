<?php

namespace Troiswa\BackBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener
{
    private $isMaintenance;

    private $environnement;

    private $twig;

    public function __construct($maintenance, $environnement, \Twig_Environment $twig)
    {
        $this->isMaintenance = $maintenance;
        $this->environnement = $environnement;
        $this->twig = $twig;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $debug = in_array($this->environnement,['test', 'dev']);

        if ($this->isMaintenance && !$debug)
        {
            $content = $this->twig->render('TroiswaBackBundle:Maintenance:index.html.twig');
            $event->setResponse(new Response($content, 503));
            $event->stopPropagation();
        }
    }
}