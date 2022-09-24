<?php

class ExtentionPerso extends \Twig\Extension\AbstractExtension {

    // Ajout de filtre personnalisé
    public function getFilters(){
        return [
            new \Twig\TwigFilter('filtreDemo', [$this, 'filtreDemoParse']) //exemple de filtre personnalisé
        ];
    }

    // Ajout de function personnalisé
    public function getFunctions(){
        return [
            new \Twig\TwigFunction('functionDemo', [$this, 'functionDemoParse']), // démo
            new \Twig\TwigFunction('pageActive', [$this, 'pageActive'], ['needs_context' => true]) //page en cours
        ];
    }

    public function filtreDemoParse($value)
    {
        return 'filtreDemo : ' . $value; 
    }

    public function functionDemoParse($value)
    {
        return 'functionDemo : ' . $value; 
    }

    public function pageActive(array $context, $page)
    {
        //var_dump($context);
        if ( isset($context['current_page']) && $context['current_page'] === $page) {
            return 'active';
        }
    }

}