<?php

namespace Troiswa\BackBundle\Controller;

use MetzWeb\Instagram\Instagram;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Troiswa\BackBundle\Document\Product;
use Troiswa\BackBundle\Entity\User;
use Troiswa\BackBundle\Form\Type\TelType;

class MainController extends Controller
{

    public function indexAction()
    {
        // $user = $this->getUser()

        $_SESSION['panier'] = [
                                ['quantity' => 0, 'id_product' => 1],
                                ['quantity' => 0, 'id_product' => 1],
                                ['quantity' => 0, 'id_product' => 1],
                              ];

        return $this->render('TroiswaBackBundle:Main:index.html.twig');
    }

    public function contactAction(Request $request)
    {

        $formContact = $this->createFormBuilder(null, ['attr' => ['novalidate' => 'novalidate']])
                            ->add('firstname', 'text', [
                                "constraints" => new Assert\NotBlank()
                            ])
                            ->add('lastname', 'text')
                            ->add('email', 'email')
                            ->add('subject', 'choice', [
                                'choices' => [
                                    'technique' => 'technique',
                                    'commercial' => 'commercial',
                                    'partenariat' => 'partenariat',
                                ]
                            ])
                            ->add('content', 'textarea')
                            ->add('phone', new TelType())
                            ->add('phone-service', 'tel')
                            ->add('envoyer', 'submit')
                            ->getForm();

        $formContact->handleRequest($request);

        if ($formContact->isValid())
        {
            $data = $formContact->getData();
            /*
            $message = \Swift_Message::newInstance()
                ->setSubject($data['subject'])
                ->setFrom($data['email'])
                ->setTo('recipient@example.com')
                ->setBody($this->renderView('TroiswaBackBundle:Mails:contact-email.html.twig', ["data" => $data]), 'text/html')
                ->addPart($this->renderView('TroiswaBackBundle:Mails:contact-email.txt.twig', ["data" => $data]), 'text/plain');

            $this->get('mailer')->send($message);
            */
            $this->get('session')->getFlashBag()->add('success', 'Votre mail a bien été envoyé');

            return $this->redirectToRoute('troiswa_back_contact');
        }

        return $this->render('TroiswaBackBundle:Main:contact.html.twig', ['formContact' => $formContact->createView()]);
    }

    public function chatAction()
    {
        return $this->render('TroiswaBackBundle:Main:chat.html.twig');
    }

    public function todoAction()
    {
        /*
        $product = new Product();
        $product->setName('A Foo Bar');
        $product->setPrice('19.99');

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($product);
        $dm->flush();
        */
        $product = $this->get('doctrine_mongodb')
            ->getRepository('TroiswaBackBundle:Product')
            ->find('5623df8e8ead0e92010041a7');
        /*
        $test = new Product();
        $test->setAll(['lala' => 1, 'tab' => ['ok' => 1, 'test' => [1,2,3,4]]]);
        */
        $product = $this->get('doctrine_mongodb')
            ->getRepository('TroiswaBackBundle:Product')
            ->find('5623df8e8ead0e92010041a7');
        $test = new Product();
        $test->setCollect([$product]);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($test);
        $dm->flush();
        die('ok');

        return $this->render('TroiswaBackBundle:Main:todo.html.twig');
    }
}