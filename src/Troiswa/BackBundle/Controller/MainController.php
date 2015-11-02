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

    public function paiementAction()
    {

        $total =20;
        $totalTTC = 24;
        $port = 10;

        $params = [
            "RETURNURL" => "http://localhost/3wasymfo-1mois/web/app_dev.php/admin/processpaypal",
            "CANCELURL" => "http://localhost/3wasymfo-1mois/web/app_dev.php/admin/processpaypal",

            // payment en plusieurs fois

            "PAYMENTREQUEST_0_AMT" => $totalTTC + $port, // prix total
            "PAYMENTREQUEST_0_CURRENCYCODE" => "EUR", // monnaie
            "PAYMENTREQUEST_0_SHIPPINGAMT" => $port, // prix frais de port
            "PAYMENTREQUEST_0_ITEMAMT" => $totalTTC, // somme total des produits sans frais de port
        ];
        $products = [
            [
                'name' => 'lorem',
                'description' => 'du super text',
                'price' => 10,
                'qty' => 1,
            ],
            [
                'name' => 'ipsum',
                'description' => 'encore',
                'price' => 14,
                'qty' => 1,
            ],
        ];

        // "L_PAYMENTREQUEST_0_NAME0" pour rajouter des informations sur le produit
        // Permet d'ajouter des information paypal pour les informations ;)
        foreach($products as $key => $prod)
        {
            $params["L_PAYMENTREQUEST_0_NAME".$key] = $prod['name'];
            $params["L_PAYMENTREQUEST_0_DESC".$key] = $prod['description'];
            $params["L_PAYMENTREQUEST_0_AMT".$key] = $prod['price']; // prix avec tva
            $params["L_PAYMENTREQUEST_0_QTY".$key] = $prod['qty'];
        }

        $paypal = $this->get('troiswa.back.paypal');
        $response = $paypal->request('SetExpressCheckout', $params);

        if ($response)
        {
            // variable à mettre sur le bouton payer ;)
            $paypalUrl = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=" . $response['TOKEN'];
        }
        else
        {
            var_dump($paypal->errors);
            die;
        }

        return $this->render('TroiswaBackBundle:Main:paiement.html.twig', compact('paypalUrl'));
    }

    public function processpaypalAction(Request $request)
    {
        $paypal = $this->get('troiswa.back.paypal');

        // ATTENTION : Vérifier si le token est définie ;)

        $params = [
            "TOKEN" => $request->query->get('token')
        ];

        $responseArray = $paypal->request('GetExpressCheckoutDetails', $params);



        // L'ETAPE DU DESSUS PEUT ËTRE PASSE :)
        $params = [
            "TOKEN" => $request->query->get('token'),
            "PAYERID" => $request->query->get('PayerID'),
            "PAYMENTACTION" => 'Sale',
            "PAYMENTREQUEST_0_AMT" => $responseArray['PAYMENTREQUEST_0_AMT'], // NE PAS FAIRE CONFIANCE A LA SESSION il faut faire la méthode du dessus pour tout payer
            "PAYMENTREQUEST_0_CURRENCYCODE" => "EUR",
            "PAYMENTREQUEST_0_SHIPPINGAMT" => $responseArray['PAYMENTREQUEST_0_SHIPPINGAMT'], // prix frais de port
            "PAYMENTREQUEST_0_ITEMAMT" => $responseArray['PAYMENTREQUEST_0_ITEMAMT'], // somme total des produits sans frais de port
        ];

        $products = [
            [
                'name' => 'lorem',
                'description' => 'du super text',
                'price' => 10,
                'qty' => 1,
            ],
            [
                'name' => 'ipsum',
                'description' => 'encore',
                'price' => 14,
                'qty' => 1,
            ],
        ];

        // "L_PAYMENTREQUEST_0_NAME0" pour rajouter des informations sur le produit
        // Permet d'ajouter des information paypal pour les informations ;)
        foreach($products as $key => $prod)
        {
            $params["L_PAYMENTREQUEST_0_NAME".$key] = $prod['name'];
            $params["L_PAYMENTREQUEST_0_DESC".$key] = $prod['description'];
            $params["L_PAYMENTREQUEST_0_AMT".$key] = $prod['price']; // prix avec tva
            $params["L_PAYMENTREQUEST_0_QTY".$key] = $prod['qty'];
        }


        $response = $paypal->request('DoExpressCheckoutPayment', $params);

        if ($response)
        {
            die('FIN');
        }
        else
        {
            var_dump($paypal->errors);
            die;
        }

        //IL FAUT ABSOLUMENT SAUVEGARDER LA TRANSACTION ID : PAYMENTINFO_0__TRANSACTIONID
    }
}