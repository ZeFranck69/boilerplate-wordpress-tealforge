# Boilerplate WordPress Tealforge

Boilerplate WordPress réutilisable pour les projets Tealforge : DDEV, thème custom,
Timber/Twig, ACF JSON, WPForms, Vite, scripts de workflow et déploiement SSH.

## Prérequis

- Git
- Docker Desktop
- DDEV
- Accès au dépôt Git du projet
- Accès WordPress admin local/distant selon le projet
- Accès SSH serveur si déploiement distant

PHP, Composer, Node et WP-CLI doivent être utilisés via DDEV, pas supposés
installés directement sur la machine.

## Démarrage d'un projet

Créer d'abord un dépôt GitHub vide pour le projet, sans README ni commit initial,
puis suivre le workflow ci-dessous.

À terme, le dépôt `boilerplate-wordpress-tealforge` pourra devenir un template
GitHub pour éviter l'étape `git remote remove origin`.

## Vue d'ensemble du workflow

Pour un nouveau projet, le principe est toujours le même :

1. créer le dépôt GitHub vide du projet ;
2. cloner le boilerplate dans `~/Sites/NOM_PROJET` ;
3. remplacer le remote Git du boilerplate par celui du projet ;
4. adapter `PROJECT.md` et `.ddev/config.yaml` ;
5. pousser le socle dans le dépôt projet ;
6. installer WordPress localement dans `web/` ;
7. installer les dépendances du thème, lancer le build et activer le thème ;
8. installer les plugins obligatoires ;
9. si un dev/prod existe déjà, importer sa BDD vers le local avec WPvivid ;
10. commencer le développement du thème.

Commandes principales du lancement projet :

```bash
cd ~/Sites
git clone git@github.com:ORGANISATION/boilerplate-wordpress-tealforge.git NOM_PROJET
cd NOM_PROJET

git remote remove origin
git remote add origin git@github.com:ORGANISATION/NOM_PROJET.git

cp PROJECT.md.example PROJECT.md
```

Puis adapter :

```text
PROJECT.md
.ddev/config.yaml
```

`PROJECT.md.example` contient des exemples de saisie. Le copier vers
`PROJECT.md`, remplacer `NOM_PROJET`, compléter les sections utiles, puis
supprimer les exemples qui ne s'appliquent pas.

Dans `.ddev/config.yaml`, remplacer le nom DDEV :

```yaml
name: NOM_PROJET
```

Si le dépôt projet contient déjà un commit initial, faire un `pull --rebase` ou
recréer le dépôt vide avant le premier push.

Premier commit et premier push :

```bash
bin/commit "Initialise le projet NOM_PROJET"
bin/push
```

Installer ensuite WordPress localement :

Voir la section **Installation WordPress locale** plus bas.

Installer les dépendances du thème :

Voir la section **Installation des dépendances du thème** plus bas.

Au quotidien :

```bash
bin/status
bin/check
bin/commit "Message clair"
bin/push
```

## Répartition du travail avec Codex

Dans un projet réel, l'utilisateur garde la main sur l'initialisation et
l'exploitation de l'environnement.

L'utilisateur exécute notamment :

- création du dépôt GitHub projet ;
- clone du boilerplate ;
- changement de remote Git ;
- adaptation de `PROJECT.md` ;
- adaptation de `.ddev/config.yaml` ;
- `ddev start` ;
- installation WordPress ;
- création de `wp-config.php` ;
- installation/configuration des plugins tiers ;
- import ou restauration de base de données ;
- accès SSH, hébergement, cPanel, DNS ;
- commandes de déploiement distant.

Codex intervient sur :

- développement du thème ;
- templates PHP/Twig ;
- sections ACF JSON ;
- intégration des maquettes ;
- CSS et JavaScript ;
- documentation ou préparation de plugins custom spécifiques projet ;
- scripts de workflow ;
- documentation des commandes ;
- vérification du code, diff et build liés au développement.

Codex peut fournir les commandes d'installation ou de déploiement, mais ne les
exécute pas par défaut.

## Installation WordPress locale

Cette étape est exécutée par l'utilisateur. Codex peut aider à préparer ou
diagnostiquer les commandes, mais ne lance pas l'installation WordPress par
défaut.

Après avoir adapté `.ddev/config.yaml` :

```bash
ddev start
```

Télécharger WordPress dans `web/` sans écraser le dossier `wp-content` du
boilerplate :

```bash
ddev wp core download \
  --path=/var/www/html/web \
  --locale=fr_FR \
  --skip-content
```

Créer le fichier de configuration local :

```bash
ddev wp config create \
  --path=/var/www/html/web \
  --dbname=db \
  --dbuser=db \
  --dbpass=db \
  --dbhost=db
```

Installer WordPress avec un compte administrateur local :

```bash
ddev wp core install \
  --path=/var/www/html/web \
  --url=https://NOM_PROJET.ddev.site \
  --title="NOM_PROJET" \
  --admin_user=tf-admin \
  --admin_password='CHANGE_ME_LOCAL_ONLY' \
  --admin_email=dev@tealforge.local \
  --skip-email
```

Les identifiants ci-dessus sont des valeurs locales temporaires. Les adapter au
projet et ne jamais les utiliser en développement distant ou production.

Configurer les permaliens :

```bash
ddev wp rewrite structure '/%postname%/' --path=/var/www/html/web
ddev wp rewrite flush --path=/var/www/html/web
```

Notes :

- `web/wp-config.php` est ignoré par Git ;
- `--skip-content` permet de conserver le `wp-content` déjà présent dans le
  boilerplate ;
- si WordPress est déjà installé, ne pas relancer les commandes `core install` ;
- ne pas activer le thème avant d'avoir installé ses dépendances Composer ;
- les plugins tiers sont installés et configurés manuellement selon le projet.

## Installation des dépendances du thème

Cette étape est exécutée par l'utilisateur juste après l'installation WordPress
locale, avant l'activation du thème et avant l'installation des plugins.

Installer Timber et les dépendances Composer du thème :

```bash
ddev composer --working-dir=/var/www/html/web/wp-content/themes/tealforge install
```

Installer les dépendances front-end du thème :

```bash
ddev npm --prefix /var/www/html/web/wp-content/themes/tealforge install
```

Construire les assets :

```bash
bin/build
```

Activer ensuite le thème :

```bash
ddev wp theme activate tealforge --path=/var/www/html/web
```

Les dossiers `vendor` et `node_modules` ne sont pas versionnés. Les fichiers de
verrouillage `composer.lock` et `package-lock.json` doivent être versionnés dès
qu'ils existent.

## Plugins WordPress obligatoires

Après l'installation de WordPress, des dépendances du thème et l'activation du
thème, installer les plugins de socle suivants depuis l'administration WordPress
ou via le processus validé pour le projet.

Plugins obligatoires par défaut :

- ACF Pro : champs administrables et sections flexibles ;
- WPForms : formulaires ;
- WPvivid : sauvegardes et migrations de base de données/médias ;
- WP-Optimize : cache, nettoyage et optimisations ;
- plugin de maintenance : page ou mode maintenance pendant les phases de recette ;
- All-In-One Security, aussi appelé AIOS : durcissement sécurité WordPress.

Points de vigilance :

- ne pas versionner les plugins tiers dans le dépôt ;
- ne pas activer toutes les options de sécurité/cache d'un coup ;
- tester après chaque activation ou changement de réglage ;
- vérifier que REST API, admin-ajax, Gutenberg, ACF, WPForms et
  `dist/manifest.json` restent accessibles ;
- documenter dans `PROJECT.md` les plugins réellement activés, les licences et les
  réglages sensibles du projet, sans secrets.

## Alignement de la BDD locale

Une fois WordPress installé en local et sur l'environnement de développement ou
de production, installer WPvivid sur les deux environnements.

Le local démarre avec une base neuve. Si le dev/prod contient déjà les bons
utilisateurs, réglages ou contenus de référence, le sens recommandé est :

```text
dev/prod -> local
```

Workflow recommandé :

1. installer WPvivid en local ;
2. installer WPvivid sur le dev/prod ;
3. faire un backup complet de l'environnement source ;
4. exporter la base de données depuis le dev/prod ;
5. importer ou restaurer cette base en local ;
6. vérifier les utilisateurs, pages, menus, options, ACF et formulaires ;
7. vider les caches.

Ne jamais écraser une base dev/prod depuis le local sans validation explicite.
La base de données et les médias ne sont pas versionnés dans Git.

## Structure cible

```text
.
├── AGENTS.md
├── PROJECT.md
├── README.md
├── bin/
├── .ddev/
└── web/
    └── wp-content/
        └── themes/
            └── tealforge/
```

## Ce qui est versionné

Le repo projet contient le code et les fichiers de workflow :

```text
AGENTS.md
PROJECT.md
README.md
.gitignore
.ddev/config.yaml
bin/
web/wp-content/themes/tealforge
```

Le coeur du projet WordPress est le thème :

```text
web/wp-content/themes/tealforge
```

Le dossier `dist` du thème et les fichiers `acf-json` doivent être versionnés.

Les plugins spécifiques projet ne sont pas placés dans ce boilerplate. Ils doivent
être développés à part, validés, zippés, puis installés via le back-office
WordPress dans `Extensions`.

## Ce qui n'est pas versionné

```text
web/wp-config.php
web/wp-content/uploads/
web/wp-content/cache/
web/wp-content/wpvivid_staging/
sauvegardes
exports SQL
archives de déploiement
node_modules
vendor
secrets
```

## Commandes utiles

```bash
bin/status
bin/build
bin/check
bin/commit "Message du commit"
bin/push
bin/package-theme
bin/deploy-theme
```

La checklist complete de lancement projet est disponible ici :

```text
docs/checklists/nouveau-projet.md
```

La documentation de depannage est disponible ici :

```text
docs/depannage.md
```

`bin/status` affiche l'état Git, les remotes, l'état DDEV lorsque disponible et
les principaux indicateurs du thème : `vendor`, `node_modules` et
`dist/manifest.json`.

## Build du thème

Le dossier `dist` du thème est versionné pour permettre un déploiement sans Node
sur le serveur.

```bash
bin/build
```

Le build doit garder les fichiers lisibles :

```bash
chmod -R u+rwX,go+rX web/wp-content/themes/tealforge/dist
```

## Vérifications avant commit

```bash
bin/check
```

La commande vérifie notamment :

- `git diff --check`
- syntaxe PHP des fichiers PHP modifiés dans le thème si DDEV est disponible
- build Vite si DDEV et les dépendances npm sont disponibles
- état Git final

Sur un projet tout juste cloné, `bin/check` peut donc passer en mode partiel tant
que WordPress, DDEV ou les dépendances ne sont pas encore installés.

## Commit simplifié

```bash
bin/commit "Ajoute la section hero"
```

Le script lance les vérifications puis crée le commit. Le push reste volontairement
manuel :

```bash
bin/push
```

## ACF JSON

Les définitions ACF doivent être versionnées dans :

```text
web/wp-content/themes/tealforge/acf-json
```

Après déploiement :

1. vérifier les permissions des JSON ;
2. supprimer les fichiers `._*` ;
3. synchroniser/importer les groupes ACF dans l'admin ;
4. ne pas sauvegarder une page si des champs attendus manquent.

## Déploiement thème

Quand `rsync` n'est pas disponible, utiliser une archive :

```bash
bin/package-theme
```

Pour préparer les variables du projet :

```bash
cp deploy.example.env deploy.local.env
```

Adapter ensuite `deploy.local.env`. Ce fichier est ignoré par Git.

Pour générer les commandes complètes de déploiement :

```bash
source deploy.local.env
bin/deploy-theme
```

Il est aussi possible de passer les variables directement :

```bash
DEPLOY_HOST="HOST" \
DEPLOY_PORT="PORT" \
DEPLOY_USER="USER" \
DEPLOY_WP_PATH="/chemin/vers/wordpress" \
bin/deploy-theme
```

`bin/deploy-theme` prépare l'archive et affiche les commandes `scp`, `ssh` et
serveur à exécuter. Il ne se connecte pas au serveur et ne modifie pas la
production.

Exemple des commandes serveur affichées :

```bash
cd /chemin/vers/wordpress/wp-content/themes
rm -rf tealforge-new
mkdir tealforge-new
tar -xzf /home/USER/tealforge-theme.tar.gz -C tealforge-new --strip-components=1
find tealforge-new -type d -exec chmod 755 {} \;
find tealforge-new -type f -exec chmod 644 {} \;
mv tealforge tealforge-backup-temp
mv tealforge-new tealforge
rm -rf tealforge-backup-temp
cd /chemin/vers/wordpress
wp theme activate tealforge
wp cache flush
```

Vérifier ensuite :

```text
/wp-content/themes/tealforge/dist/manifest.json
```

Le HTML doit charger `/themes/tealforge/dist/...`, pas un dossier backup.

## Base de données et médias

La BDD et les médias ne sont pas versionnés.

Utiliser une stratégie claire par projet :

- WPvivid pour backups/migrations si retenu ;
- import distant vers local avant grosses reprises de contenu ;
- jamais d'écrasement distant sans validation explicite.

## Problèmes connus

### Fichiers macOS `._*`

Toujours créer les archives avec :

```bash
COPYFILE_DISABLE=1
```

### Permissions assets

Les dossiers doivent être en `755`, les fichiers en `644`.

### Erreur WordPress `footnotes`

Si Gutenberg bloque l'enregistrement :

```bash
wp post meta list ID_PAGE --keys=footnotes
wp post meta delete ID_PAGE footnotes
wp cache flush
```

Ne jamais faire cette opération sans backup ou validation sur un environnement
sensible.
