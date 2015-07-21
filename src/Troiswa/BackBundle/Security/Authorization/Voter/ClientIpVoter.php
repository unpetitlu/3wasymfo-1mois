<?php

namespace Troiswa\BackBundle\Security\Authorization\Voter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ClientIpVoter implements VoterInterface
{
    public function __construct(ContainerInterface $container, array $blacklistedIp = array())
    {
        $this->container     = $container;
        $this->blacklistedIp = $blacklistedIp;
    }

    public function supportsAttribute($attribute)
    {
        // vous n'allez pas vérifier l'attribut de l'utilisateur, alors retournez true
        return true;
    }

    public function supportsClass($class)
    {
        // votre voteur supporte tous les types de classes de token, donc retournez true
        return true;
    }

    function vote(TokenInterface $token, $object, array $attributes)
    {
        $request = $this->container->get('request');
        
        if (in_array($request->getClientIp(), $this->blacklistedIp)) {
            return VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}