<?php

namespace Troiswa\BackBundle\Twig;

class UtilExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            'price' => new \Twig_Filter_Method($this, 'priceFilter'),
            'gender' => new \Twig_Filter_Method($this, 'genderFilter'),
            'truncate'=> new \Twig_Filter_Method($this,'truncFilter'),
        ];
    }

    public function getFunctions()
    {
        return [
            'age' => new \Twig_Function_Method($this, 'ageFunction'),
        ];
    }

    public function truncFilter($texte,$nbr = 50)
    {

        return (strlen($texte) > $nbr ? substr(substr($texte,0,$nbr),0,strrpos(substr($texte,0,$nbr)," "))." ..." : $texte);

    }

    public function ageFunction($date)
    {
        $now = new \DateTime("now");
        $diff = $date->diff($now);
        return $diff->format('%y');
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price.' €';
        return $price;
    }

    public function genderFilter($sexe)
    {
        if ($sexe)
        {
            return 'homme';
        }
        return 'femme';
    }

    public function getName()
    {
        return 'troiswa_back_extension';
    }
}