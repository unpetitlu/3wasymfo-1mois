<?php

namespace Troiswa\BackBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
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
        // Récupération du service panier
        $cart = $this->get('troiswa.back.cart');
        // Ajout du produit
        $cart->add($product, $qty);

        return $this->redirectToRoute('troiswa_back_cart');
    }

    public function indexAction(Request $request)
    {
        $cartUtil = $this->get('troiswa.back.cart');

        $cart = $cartUtil->getCart();
        $products = [];
        if ($cart)
        {
            $idProducts = array_keys($cart);
            $em = $this->getDoctrine()->getManager();
            $products = $em->getRepository('TroiswaBackBundle:Product')->findProductByIds($idProducts);
        }

        return $this->render('TroiswaBackBundle:Cart:index.html.twig', compact('products', 'cart'));
    }

    public function deleteAction(Product $product, Request $request)
    {
        $cart = $this->get('troiswa.back.cart');

        $cart->delete($product);

        if ($request->isXmlHttpRequest())
        {
            return new JsonResponse('Votre produit a bien été supprimé');
        }

        return $this->redirectToRoute('troiswa_back_cart');
    }
}