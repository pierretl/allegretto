# Allegretto

## A propos

Projet de calendrier décentralisé pour la gestion d'une résidence familiale

Projet en cours

## Installation


```bash
# Installation des dépendances PHP
composer install

# Installation des dépendances JavaScript
npm install
```

## Pendant le dev


```bash
# Cette commande rebuild le js et sass automatiquement. (le navigateur s'ouvre automatiquement sur http://localhost:3000)
gulp watch
```

Dans le fichier `index.php` désactiver le cache de Twig

```php
$twig = new \Twig\Environment($loader, [
    'cache' => false
]);
```

## Pour la Production


```bash
# Cette commande rebuild tout, compresse le css 
gulp build
```

Dans le fichier `index.php` activer le cache de Twig

```php
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/tmp'
]);
```

Dossier `tmp` a supprimer pour mettre a jour la production

## Licence

Voir le fichier [LICENSE](LICENSE)