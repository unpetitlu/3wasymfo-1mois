<?php

namespace Troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class MainController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

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
}