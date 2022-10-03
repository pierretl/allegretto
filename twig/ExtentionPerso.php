<?php


include 'action/function/chiffrage.php';

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
            new \Twig\TwigFunction('pageActive', [$this, 'pageActive'], ['needs_context' => true]), //page en cours
            new \Twig\TwigFunction('dechiffre', [$this, 'dechiffre']), // Déchiffre une donnée
            new \Twig\TwigFunction('getLabelFamille', [$this, 'getLabelFamille']), // récupération des label d'une famille a partir de son id
            new \Twig\TwigFunction('strLength', [$this, 'strLength']), // Calcule la taille d'une chaîne
            new \Twig\TwigFunction('subString', [$this, 'subString']), // Retourne un segment de chaîne
            new \Twig\TwigFunction('getenv', [$this, 'getenv']) // Retourne les variable d'environnement
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

    public function dechiffre($valeur)
    {
        return dechiffre($valeur);
    }

    public function getLabelFamille($id)
    {
        $data = getDataJson('data/famille.json');

        $allFamille= [];
        for ($i=0; $i < count($data); $i++){
            $allFamille += [
                $data[$i]['label'] => $data[$i]['id']
            ];
        }

        return array_search($id, $allFamille);
    }

    public function strLength ($label) {
        return strlen($label);
    }

    public function subString ($string, $offset, $length=null) {
        return substr($string, $offset, $length);
    }

    public function getenv ($key) {
        return getenv($key);
    }

}