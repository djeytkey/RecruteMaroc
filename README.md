# Plateforme de recrutement Maroc

Application Laravel (dernière version) conforme au cahier des charges « Présentation Site de recrutement Maroc », avec un frontend inspiré des sites de recrutement type emploi.ma.

## Fonctionnalités principales

- **Espace public** : accueil, liste des offres avec recherche (mots-clés, lieu, secteur, type de contrat), détail d’une offre.
- **Candidats** : inscription, profil structuré (coordonnées, mobilité, disponibilité, expérience, compétences, langues, secteur, niveau d’études), upload CV PDF, candidature avec réponses aux critères et **score de compatibilité** automatique.
- **Recruteurs** : inscription avec création du compte entreprise, choix du pack (Essentiel / Optimisé / Stratégique), création d’offres avec **critères de sélection** (quantitatifs / déclaratifs) et pondération, publication d’offres, liste des candidatures avec score, **comparaison** de candidats, actions (refuser, mettre en attente, sélectionner, recruter), **récompense candidat** (création du dossier récompense après recrutement).
- **Admin** : back-office complet (CRUD utilisateurs, entreprises ; consultation offres et récompenses ; exports Excel).
- **Paiement** : Stripe Checkout pour publier une offre après paiement (optionnel : bouton « Publier sans paiement » en test).
- **Emails** : activation recruteur (lien par email), refus candidat, notification récompense.
- **Connexion sociale** : inscription / connexion avec Google et Facebook (Laravel Socialite) pour les candidats.

## Prérequis

- PHP 8.2+
- Composer
- MySQL / PostgreSQL / SQLite
- Node.js & npm (pour Vite)

## Installation

```bash
cd laravel_temp
cp .env.example .env
php artisan key:generate
# Configurer DB_* dans .env
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
npm install && npm run build
```

## Lancer l’application

```bash
php artisan serve
# Ouvrir http://127.0.0.1:8000
```

Compte admin de test : **admin@recrutement.ma** / **password**.

## Structure du projet

- **Modèles** : `User` (rôles candidate, recruiter, admin), `Company`, `CandidateProfile`, `JobOffer`, `OfferCriterion`, `Application`, `ApplicationAnswer`, `Reward`, `RecruitmentPack`, `Sector`.
- **Config** : `config/recruitment.php` (listes : mobilité, disponibilité, types de contrat, niveaux de compétence, etc.).
- **Routes** : `web.php` (accueil, offres, contact), `auth.php`, `candidat.php`, `recruteur.php`, `admin.php`.

## Packs recrutement (seed)

| Pack        | Prix/annonce | Prime candidat | Publication |
|------------|-------------|----------------|-------------|
| Essentiel  | 1 500 MAD   | 500 MAD        | 30 jours    |
| Optimisé   | 2 500 MAD   | 1 000 MAD      | 45 jours    |
| Stratégique| 4 490 MAD   | 1 500 MAD      | 60 jours    |

## Configuration avancée

- **Stripe** : dans `.env`, définir `STRIPE_KEY`, `STRIPE_SECRET` et éventuellement `STRIPE_WEBHOOK_SECRET`. Webhook URL : `POST /webhook/stripe`. Sans Stripe, le recruteur peut « Publier sans paiement » (test).
- **Emails** : configurer `MAIL_*` dans `.env`. Par défaut `MAIL_MAILER=log` écrit les mails dans `storage/logs/laravel.log`.
- **Activation recruteur** : mettre `SEND_RECRUITER_ACTIVATION_EMAIL=true` pour envoyer un email avec lien d’activation (sinon le compte est actif dès l’inscription).
- **Google / Facebook** : configurer les apps OAuth et les URI de redirection (`/auth/google/callback`, `/auth/facebook/callback`) puis renseigner les clés dans `.env`.

## Déploiement

Pour mettre l’application en ligne (hébergement partagé ou VPS), suivre le guide **[DEPLOYMENT.md](DEPLOYMENT.md)** : prérequis serveur, variables d’environnement, commandes à exécuter, point d’entrée `public/`, HTTPS, cron et bonnes pratiques.

Pour **recrute.moroccoder.com** : mise à jour via Git (push en local → `git pull` + `bash deploy.sh` sur le serveur). Détails dans DEPLOYMENT.md, section *Mise à jour via Git*.

## Notes

- Le frontend utilise Tailwind CSS et Vite ; le thème est sobre type « emploi.ma » (vert emerald, fond clair).
- Stripe accepte la devise MAD ; en cas d’erreur selon la région du compte Stripe, adapter la devise dans `PaymentController`.
