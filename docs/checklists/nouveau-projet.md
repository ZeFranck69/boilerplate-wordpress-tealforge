# Checklist nouveau projet WordPress Tealforge

Cette checklist sert de fil conducteur pour lancer un nouveau projet a partir du
boilerplate.

Codex peut aider a documenter, diagnostiquer et developper le theme ou les
plugins custom, mais l'utilisateur garde la main sur l'installation WordPress,
les plugins tiers, les imports de base de donnees et les deploiements.

## 1. Depot et fichiers projet

- [ ] Creer le depot GitHub projet vide, sans README initial.
- [ ] Cloner le boilerplate dans `~/Sites/NOM_PROJET`.
- [ ] Remplacer le remote Git par le depot du projet.
- [ ] Copier `PROJECT.md.example` vers `PROJECT.md`.
- [ ] Completer `PROJECT.md` sans secret.
- [ ] Adapter le nom dans `.ddev/config.yaml`.
- [ ] Faire le premier push du socle vers le depot projet.

## 2. WordPress local

- [ ] Lancer `ddev start`.
- [ ] Telecharger WordPress avec `--skip-content`.
- [ ] Creer `wp-config.php`.
- [ ] Installer WordPress localement.
- [ ] Configurer les permaliens en `/%postname%/`.
- [ ] Verifier que l'accueil repond en local.

## 3. Theme

- [ ] Installer les dependances Composer du theme.
- [ ] Installer les dependances npm du theme.
- [ ] Lancer `bin/build`.
- [ ] Activer le theme `tealforge`.
- [ ] Verifier que `dist/manifest.json` existe.
- [ ] Verifier que les permissions du dossier `dist` sont correctes.
- [ ] Verifier que la page 404 existe.

## 4. Plugins de socle

- [ ] Installer ACF Pro.
- [ ] Installer WPForms.
- [ ] Installer WPvivid.
- [ ] Installer WP-Optimize.
- [ ] Installer le plugin de maintenance choisi.
- [ ] Installer AIOS.
- [ ] Activer les reglages de securite et cache progressivement.
- [ ] Verifier REST API, admin-ajax, Gutenberg, ACF et WPForms apres reglages.

## 5. Alignement BDD locale

- [ ] Installer WPvivid en local.
- [ ] Installer WPvivid sur le dev/prod.
- [ ] Faire un backup complet de l'environnement source.
- [ ] Exporter la BDD du dev/prod.
- [ ] Importer ou restaurer la BDD dev/prod en local.
- [ ] Verifier les utilisateurs WordPress.
- [ ] Verifier les pages, menus, options, ACF et formulaires.
- [ ] Vider les caches.

## 6. ACF et contenus

- [ ] Synchroniser ou importer les groupes ACF JSON.
- [ ] Creer les pages de base.
- [ ] Definir la page d'accueil dans les reglages WordPress.
- [ ] Creer les menus WordPress.
- [ ] Saisir les contenus de test necessaires.
- [ ] Verifier les champs ACF avant de sauvegarder les pages.

## 7. Avant premier commit utile

- [ ] Lancer `bin/status`.
- [ ] Lancer `bin/check`.
- [ ] Relire le diff.
- [ ] Verifier qu'aucun secret, backup, upload ou export SQL n'est versionne.
- [ ] Committer avec `bin/commit "Message clair"`.
- [ ] Pousser avec `bin/push`.

## 8. Avant recette ou deploiement

- [ ] Faire un backup de l'environnement cible.
- [ ] Copier `deploy.example.env` vers `deploy.local.env` si un deploiement SSH est prevu.
- [ ] Completer `deploy.local.env` sans le committer.
- [ ] Builder le theme localement.
- [ ] Creer une archive avec `bin/package-theme`.
- [ ] Generer les commandes avec `source deploy.local.env` puis `bin/deploy-theme`.
- [ ] Transferer l'archive selon les acces du projet.
- [ ] Remplacer le theme en gardant le slug `tealforge`.
- [ ] Verifier les permissions `755` pour les dossiers et `644` pour les fichiers.
- [ ] Verifier `dist/manifest.json` en HTTP.
- [ ] Vider les caches.
- [ ] Tester desktop et mobile.
