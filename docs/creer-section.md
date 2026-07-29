# Creer une section

Ce document sert de guide rapide pour ajouter une nouvelle section administrable
dans un projet Tealforge.

## 1. Definir le besoin

Avant de coder, clarifier :

- le nom de la section ;
- les champs administrables ;
- les variantes visuelles ;
- les etats desktop, tablette et mobile ;
- les champs optionnels ;
- les liens et boutons ;
- les images et leurs tailles attendues.

## 2. Ajouter les champs ACF

Dans WordPress, ajouter un layout au Flexible Content principal, par defaut :

```text
page_sections
```

Utiliser des noms en `snake_case`.

Exemple :

```text
layout: text_media
fields:
- eyebrow
- title
- text
- image
- primary_link
- image_position
```

Apres modification, verifier que le fichier JSON ACF est genere dans :

```text
web/wp-content/themes/tealforge/acf-json
```

## 3. Preparer les donnees en PHP

Ajouter une fonction de preparation dans le fichier PHP adapte.

Pour un petit projet, utiliser :

```text
inc/acf.php
```

Pour un projet plus avance, separer les prepareurs dans :

```text
inc/Sections/
```

Principe :

```php
function tealforge_prepare_text_media_section(array $section): array
{
    return [
        'eyebrow' => $section['eyebrow'] ?? '',
        'title' => $section['title'] ?? '',
        'text' => $section['text'] ?? '',
        'image' => tealforge_prepare_image_field($section['image'] ?? null),
        'primary_link' => tealforge_prepare_link_field($section['primary_link'] ?? null),
        'image_position' => $section['image_position'] ?? 'right',
    ];
}
```

## 4. Creer le template Twig

Créer le fichier :

```text
views/sections/text-media.twig
```

Le template Twig ne doit pas contenir de requete WordPress.

Il affiche uniquement les donnees deja preparees.

## 5. Ajouter les styles

Pour une petite base, ajouter le CSS dans :

```text
assets/styles/main.css
```

Si le projet grossit, creer une structure :

```text
assets/styles/sections/text-media.css
```

et l'importer depuis le point d'entree CSS.

Respecter BEM avec le prefixe `tf-`.

## 6. Ajouter le JavaScript si necessaire

Ne creer du JavaScript que si la section a un comportement interactif.

Utiliser des hooks :

```html
data-tf-component="text-media"
```

Eviter d'utiliser les classes CSS comme seul contrat JavaScript.

## 7. Verifier

Avant commit :

```bash
bin/check
```

Puis controler :

- champs vides ;
- texte long ;
- image absente ;
- liens absents ;
- desktop ;
- mobile ;
- absence d'erreur PHP ou JavaScript.
