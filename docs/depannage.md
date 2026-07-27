# Depannage WordPress Tealforge

Cette page regroupe les problemes deja rencontres sur des projets WordPress
Tealforge et les controles rapides a effectuer.

## Assets non charges

Symptomes :

- le site apparait sans CSS ;
- les animations ou le menu mobile ne fonctionnent pas ;
- le navigateur retourne une erreur sur `manifest.json`, CSS ou JS.

Verifier :

```text
/wp-content/themes/tealforge/dist/manifest.json
```

Le fichier doit repondre en HTTP 200, pas en 403 ou 404.

Verifier aussi que le manifest pointe bien vers les fichiers presents dans
`dist/assets`.

## Permissions des fichiers

Sur serveur distant, les permissions attendues sont :

```text
dossiers : 755
fichiers : 644
```

Commandes serveur typiques :

```bash
find tealforge -type d -exec chmod 755 {} \;
find tealforge -type f -exec chmod 644 {} \;
```

## Fichiers macOS parasites

Les fichiers `._*` peuvent etre crees dans les archives depuis macOS et perturber
certains serveurs.

Creer les archives avec :

```bash
COPYFILE_DISABLE=1
```

Le script `bin/package-theme` le fait deja.

## Mauvais dossier de theme actif

Le theme actif doit garder le slug :

```text
tealforge
```

Ne pas laisser WordPress actif sur un dossier temporaire ou backup :

```text
tealforge-new
tealforge-backup-temp
tealforge-backup-YYYYMMDD
```

## ACF JSON absent ou non synchronise

Si des champs disparaissent dans l'administration :

1. verifier que les fichiers JSON existent dans `acf-json` ;
2. verifier les permissions ;
3. supprimer les fichiers `._*` ;
4. synchroniser ou importer les groupes ACF ;
5. ne pas sauvegarder une page tant que les champs attendus sont absents.

## Erreur WordPress footnotes

Symptome :

```text
La mise a jour de la valeur de la meta de footnotes dans la base de donnees n'est pas possible.
```

Apres backup et validation, verifier puis supprimer uniquement la meta concernee :

```bash
wp post meta list ID_PAGE --keys=footnotes
wp post meta delete ID_PAGE footnotes
wp cache flush
```

## Plugins de securite ou cache

AIOS, WP-Optimize ou un autre plugin de securite/cache peut bloquer :

- REST API ;
- admin-ajax ;
- Gutenberg ;
- ACF ;
- WPForms ;
- fichiers JSON ;
- `dist/manifest.json` ;
- CSS/JS compiles.

Activer les options progressivement et tester apres chaque changement.
