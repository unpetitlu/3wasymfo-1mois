<?php


namespace Troiswa\BackBundle\Util;

use Symfony\Component\HttpFoundation\Session\Session;
use Troiswa\BackBundle\Entity\Product;

class Paypal
{
    private $user;
    private $pwd;
    private $signature;

    private $endpoint = "https://api-3t.sandbox.paypal.com/nvp";

    public $errors = [];

    public function __construct($user, $pwd, $siqnature, $prod = false)
    {
        $this->user = $user;
        $this->pwd = $pwd;
        $this->signature = $siqnature;

        if ($prod)
        {
            $this->endpoint = str_replace('sandbox', '', $this->endpoint);
        }
    }

    public function request($method, $params)
    {
        $params = array_merge($params, [
            "METHOD" => $method,
            "VERSION" => 74.0,
            "USER" => $this->user,
            "SIGNATURE" => $this->signature,
            "PWD" => $this->pwd,
        ]);
        $params = http_build_query($params); // convertir un tableau en chaine

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->endpoint,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => 1, //les infos ne sont pas affiché cela renvoit le résultat
            CURLOPT_SSL_VERIFYPEER => false, // pour ne pas vérifier le certificat ssl
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_VERBOSE => 1 // Affiche si il y a des erreurs
        ]);


        $response = curl_exec($curl);
        $responseArray = [];
        parse_str($response, $responseArray);
        // permet d'avoir une url pour l'utilisateur
        // useraction=commit permet de modifier le bouton côté paypal en payer plutôt que continuer
        //die(dump($responseArray, "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=".$responseArray['TOKEN']));
        // si problème de curl

        if (curl_errno($curl)) {
            //dump(curl_error($curl));
            //curl_close($curl);
            //die;
            $this->errors = curl_error($curl);
            return false;
        }
        else {
            // sinon il y a peut être un problème avec api paypal

            if ($responseArray['ACK'] == "Success") {
                curl_close($curl);
                return $responseArray;
            } else {
                //dump($responseArray);
                //curl_close($curl);
                //die;
                $this->errors = curl_error($curl);
                return false;
            }
        }
    }
}