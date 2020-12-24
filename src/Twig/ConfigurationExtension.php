<?php

namespace App\Twig;
use Doctrine\Persistence\ManagerRegistry; 
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Configuration;

class ConfigurationExtension extends AbstractExtension {

    // Para usar 'Doctrine' necesiatmos de 'ManagerRegistry'
    protected $doctrine;

    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters(): array {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('variable_get', [$this, 'getVariable']),
        ];
    }

    public function getFunctions(): array {
        return [
            new TwigFunction('variable_get', [$this, 'getVariable']),
        ];
    }

    public function getVariable($var_name) {
        return $this->doctrine->getRepository(Configuration::class)->find($var_name);
    }

}
