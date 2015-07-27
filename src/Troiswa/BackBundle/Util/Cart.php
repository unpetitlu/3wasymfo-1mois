<?php

namespace Troiswa\BackBundle\Util;

use Symfony\Component\HttpFoundation\Session\Session;
use Troiswa\BackBundle\Entity\Product;

class Cart
{
    private $session;

    function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function add(Product $product, $qty = 1)
    {
        if ($qty > 0)
        {
            if ($this->session->get('cart'))
            {
                $cart = $this->getCart();
            }
            else
            {
                $cart = [];
            }

            if (array_key_exists($product->getId(), $cart))
            {
                $qty += $cart[$product->getId()]['quantity'];
            }

            $cart[$product->getId()] = ['quantity' => $qty];

            $this->save($cart);

        }
    }

    public function delete(Product $product)
    {
        $cart = json_decode($this->session->get('cart'), true);

        if ($cart && array_key_exists($product->getId(), $cart))
        {
            unset($cart[$product->getId()]);
            $this->save($cart);
        }
    }

    public function getCart()
    {
        return json_decode($this->session->get('cart'), true);
    }

    private function save($cart)
    {
        $this->session->set('cart', json_encode($cart));
    }
}