<?php

namespace Troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QuantityProductCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('quantity:product')
            ->setDescription('Envoie un mail des produits inférieur à 5 quantités')
            ->addArgument('quantite', InputArgument::REQUIRED, 'Nombre de produit?')
            ->addOption('valide', '-val', InputOption::VALUE_NONE, 'Si définie, un petit message apparaitra')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // permet de récupérer les arguments
        //$input->getArgument('name');
        // permet de récupérer les options --yell
        //$input->getOption('yell')

        //récupérer le service de doctrine manager
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody('lala')
        ;
        $this->getContainer()->get('mailer')->send($message);


        $output->writeln("<info>Cool</info>");
    }


}