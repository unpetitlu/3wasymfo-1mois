<?php

namespace Troiswa\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Troiswa\BackBundle\Entity\Product;


/**
 * Cart controller.
 *
 */
class CartController extends Controller
{
    public function addCartAction(Product $product, Request $request)
    {
        // Récupération des informations du formulaire d'ajout au panier
        $qty = $request->request->getInt('qty');

        if ($qty > 0)
        {
            $session = $request->getSession();

            if ($session->get('cart'))
            {
                $cart = json_decode($session->get('cart'), true);
            }
            else
            {
                $cart = [];
            }

            if (array_key_exists($product->getId(), $cart))
            {
                $qty += $cart[$product->getId()]['quantity'];
            }

            $cart[$product->getId()] = $qty;

            $session->set('cart', json_encode($cart));

        }

        return $this->redirectToRoute('troiswa_back_cart');
    }

    public function indexAction(Request $request)
    {

        $session = $request->getSession();
        $cart = json_decode($session->get('cart'), true);
        $products = [];
        if ($cart)
        {
            $idProducts = array_keys($cart);
            $em = $this->getDoctrine()->getManager();
            $products = $em->getRepository('TroiswaBackBundle:Product')->findProductByIds($idProducts);
        }

        return $this->render('TroiswaBackBundle:Cart:index.html.twig', compact('products'));
    }
}