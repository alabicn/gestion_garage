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
        ];
    }

    public function format_price($price) {
        return $this->serviceInformations->format_price($price);
    }

}