<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Container;

class ServiceInformations {

    /**
     * Supprime tous les accents de $chaine
     * @param string $chaine
     * @return mixed
     */
    public function replaceAccent($chaine) {
        $accents = array('À','Á','Â','Ã','Ä','Å','Č','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','?','đ','à','á','â','ã','ä','å','©','ç','ć','è','é','ê','ë','ì','í','î','ï','?','ò','ó','ô','õ','ö','ù','ú','û','ü','?','ÿ','ž','\t','"','\'',' ','«e','«_','ď');
        $sans = array('A','A','A','A','A','A','C','C','E','E','E','E','I','I','I','I','O','O','O','O','O','U','U','U','U','Y','d','a','a','a','a','a','a','č','c','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y','z','','_','_','_','i','e','d');
        $text = str_replace($accents, $sans, $chaine);
        return $text;
    }

    /**
     * Formatte un prix et retourne une chaine de caractère
     */
    public function format_price($price)
    {
        return (is_nan($price) || $price == 0 ? '- €' : number_format(round($price, 2), 2, ',', ' ') . ' €');
    }

    /**
     * Retourne le nom de pays 
     */
    public function getAllVilles(){

        $pays = array(
            "67000"=>"Strasbourg", "68100"=>"Mulhouse", "68000"=>"Colmar", "75000"=>"Paris", "57000"=>"Metz", "67700"=>"Saverne");

        asort($pays); // trier les villes par ordre alphabétique
        return $pays;
    }

    /**
     * Vérification si le numéro d'immatriculation est en bon format
     */
    public function verificationImmatriculation($immatriculation){
        $regex = "#^[A-Z]{2}-[0-9]{3}-[A-Z]{2}$#";

        if (preg_match($regex, $immatriculation))
            return true;
        else
            return false;
    }
}