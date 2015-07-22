<?php

namespace Troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Troiswa\BackBundle\Entity\User;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class UtilisateurCommand extends ContainerAwareCommand
{
    protected function configure() {

        $this->setName('troiswa:user:create')
            ->setDescription("Création d'un utilisateur avec mot de passe crypté")
            ->addArgument('login', InputArgument::REQUIRED, 'Identifiant')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe')
            ->addOption('exist', 'ex', InputOption::VALUE_NONE, 'Metre à jour un utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $factory = $this->getContainer()->get('security.encoder_factory');

        // j'écris en jaune sur fond rouge le tout boldé
        $style = new OutputFormatterStyle('yellow', 'red', ['bold']);
        $output->getFormatter()->setStyle('fire', $style);


        if ($input->getOption('exist')) {
            $user = $em->getRepository('TroiswaBackBundle:User')
                ->findOneBy(['login' => $input->getArgument('login')]);

            if ($user) {
                $encoder = $factory->getEncoder($user);
                $newPassword = $encoder->encodePassword($input->getArgument('password'), $user->getSalt());
                $user->setPassword($newPassword);
                $em->flush();

                $output->writeln("<info>L'utilisateur " . $input->getArgument('login') . " a bien été mis à jour</info>");
            } else {
                $output->writeln("<fire>L'utilisateur " . $input->getArgument('login') . " n'existe pas</fire>");
            }

        } else {
            $user = new User();

            $encoder = $factory->getEncoder($user);
            $newPassword = $encoder->encodePassword($input->getArgument('password'), $user->getSalt());

            $user->setLogin($input->getArgument('login'))
                 ->setPassword($newPassword)
                 ->setAddress('12 rue blabla')
                 ->setBirthday(new \DateTime('now'))
                 ->setEmail('mad@online.net')
                 ->setFirstname('Jean')
                 ->setLastname('Bon')
                 ->setPhone('+33654478951')
                 ->setSexe(0);

            $em->persist($user);
            $em->flush();

            $output->writeln("<info>L'utilisateur a bien été créé</info>");
        }
    }


}