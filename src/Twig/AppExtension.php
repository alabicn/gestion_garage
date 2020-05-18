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
            new TwigFilter('format_km', [$this, 'format_km'])
        ];
    }

    public function format_price($price) {
        return $this->serviceInformations->format_price($price);
    }

    public function format_km($kilometrage) {
        return number_format($kilometrage, 0, '', ' ')." km";
    }

}