<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use App\Service\ServiceInformations;


class AppExtension extends AbstractExtension
{
    public function __construct(ServiceInformations $serviceInformations)
    {
        $this->serviceInformations = $serviceInformations;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('format_price', [$this, 'format_price']),
            new TwigFilter('format_km', [$this, 'format_km']),
            new TwigFilter('local_date', [$this, 'local_date'])
        ];
    }

    public function format_price($price) {
        return $this->serviceInformations->format_price($price);
    }

    public function format_km($kilometrage) {
        return number_format($kilometrage, 0, '', ' ')." km";
    }

    public function local_date($obj_date) {
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
        $str_date = $obj_date->format('Y-m-d');
        $local_date = utf8_encode(strftime("%B %G", strtotime($str_date)));
        return $local_date;
    }

}