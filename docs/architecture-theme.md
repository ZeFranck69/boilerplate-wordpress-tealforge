# Architecture du theme

Le theme Tealforge utilise WordPress, Timber 2 et Twig.

WordPress choisit le fichier PHP a executer selon sa hierarchie de templates.
Le PHP prepare les donnees, puis Twig affiche le HTML.

## Fichiers d'entree

```text
web/wp-content/themes/tealforge/
├── front-page.php
├── page.php
├── index.php
└── 404.php
```

Ces fichiers doivent rester courts.

Exemple de role :

```php
$context = tealforge_get_context();
$context['post'] = Timber\Timber::get_post();

tealforge_render('pages/page.twig', $context);
```

## Organisation PHP

```text
inc/
├── setup.php
├── assets.php
├── timber.php
└── helpers.php
```

- `setup.php` : supports WordPress, menus, initialisation du theme.
- `assets.php` : chargement des fichiers Vite depuis `dist/manifest.json`.
- `timber.php` : contexte Timber et rendu Twig.
- `helpers.php` : petites fonctions reutilisables pour preparer les donnees.

Quand une fonctionnalite grandit, creer un nouveau fichier dans `inc/` plutot que
laisser grossir `functions.php`.

Exemples :

```text
inc/acf.php
inc/post-types.php
inc/admin.php
inc/ajax.php
```

Ne pas creer ces fichiers trop tot. Les ajouter seulement quand le projet en a
besoin.

## Organisation Twig

```text
views/
├── layouts/
├── pages/
├── sections/
├── components/
└── partials/
```

- `layouts/` : structure HTML globale.
- `pages/` : templates de pages.
- `sections/` : sections ACF ou blocs de page.
- `components/` : petits composants reutilisables.
- `partials/` : header, footer, menus, fragments globaux.

## Organisation front-end

```text
assets/
├── styles/
└── scripts/
```

Le socle utilise Vite, du CSS natif moderne et du JavaScript natif.

Le dossier `dist/` est versionne pour permettre un deploiement sans Node.js sur le
serveur.

## Regle simple

- PHP prepare les donnees.
- Twig affiche les donnees.
- CSS gere le rendu.
- JavaScript gere les interactions.
- ACF definit ce qui est administrable.
