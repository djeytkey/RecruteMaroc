# Déploiement en production

Guide pour mettre la plateforme de recrutement en ligne sur un hébergement web.

---

## 1. Prérequis serveur

- **PHP** 8.2 ou supérieur
- **Extensions PHP** : BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- **Composer** (en ligne de commande sur le serveur, ou build local puis upload)
- **MySQL** ou **MariaDB** (recommandé en production) — ou SQLite si l’hébergeur le permet
- **Node.js & npm** (pour compiler les assets avec Vite, ou build en local puis upload)

---

## 2. Checklist avant mise en ligne

- [ ] **APP_ENV=production** et **APP_DEBUG=false** dans `.env`
- [ ] **APP_URL** = l’URL réelle du site (ex. `https://votresite.com`)
- [ ] **APP_KEY** générée (`php artisan key:generate`)
- [ ] Base de données créée (MySQL/MariaDB) et variables **DB_*** configurées
- [ ] **SESSION_DRIVER=database** (ou `file`) — cohérent avec la config
- [ ] **MAIL_***** configurés pour envoyer les vrais emails (activation recruteur, refus, récompense)
- [ ] **Stripe** : clés live (`STRIPE_KEY`, `STRIPE_SECRET`), webhook en production pointant vers `https://votresite.com/webhook/stripe`
- [ ] **Google OAuth** : URI de redirection autorisée = `https://votresite.com/auth/google/callback`
- [ ] **Facebook OAuth** : URI de redirection autorisée = `https://votresite.com/auth/facebook/callback`
- [ ] **HTTPS** activé sur le domaine (obligatoire pour OAuth et Stripe)

---

## 3. Variables d’environnement (.env) en production

Exemple minimal à adapter sur l’hébergement :

```env
APP_NAME="Recrutement Maroc"
APP_ENV=production
APP_KEY=base64:xxxx...   # générer avec php artisan key:generate
APP_DEBUG=false
APP_URL=https://recrute.moroccoder.com   # ou votre domaine

LOG_CHANNEL=stack
LOG_LEVEL=error

# Base de données (MySQL recommandé)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_base
DB_USERNAME=utilisateur
DB_PASSWORD=mot_de_passe

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true

CACHE_STORE=database
QUEUE_CONNECTION=database

# Emails (SMTP de l’hébergeur ou SendGrid, Mailgun, etc.)
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-hebergeur.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@votredomaine.com"
MAIL_FROM_NAME="${APP_NAME}"

# Stripe (clés live)
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Google OAuth (même app, URI de redirection en https)
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=
# GOOGLE_OAUTH_STATELESS=false  # garder false en prod

# Facebook OAuth
FACEBOOK_CLIENT_ID=...
FACEBOOK_CLIENT_SECRET=
# FACEBOOK_OAUTH_STATELESS=false

# Optionnel
SEND_RECRUITER_ACTIVATION_EMAIL=true
```

---

## 4. Commandes à exécuter sur le serveur

Après avoir uploadé le code (ou après un `git pull`) :

```bash
cd /chemin/vers/votre/app   # racine du projet Laravel (où se trouve artisan)

# Dépendances PHP (sans les paquets dev)
composer install --optimize-autoloader --no-dev

# Si pas déjà fait : clé d’application
php artisan key:generate

# Migrations
php artisan migrate --force

# Lien symbolique storage -> public/storage (CV, etc.)
php artisan storage:link

# Cache de la config, routes et vues
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Assets (CSS/JS)** : soit vous buildiez en local puis uploadez le dossier `public/build`, soit sur le serveur :

```bash
npm ci
npm run build
```

---

## 5. Point d’entrée web (document root)

Le **document root** du serveur web (Apache ou Nginx) doit pointer vers le dossier **`public`** du projet, et non vers la racine du projet.

- **Chemin correct** : `.../laravel_temp/public`
- Ainsi, les requêtes passent par `public/index.php` et les fichiers sensibles (`.env`, `app/`, etc.) ne sont pas accessibles par le navigateur.

### Apache

Le fichier `public/.htaccess` est déjà fourni par Laravel. Vérifier que `mod_rewrite` est activé.

Exemple de VirtualHost (à adapter) :

```apache
<VirtualHost *:80>
    ServerName votredomaine.com
    DocumentRoot /chemin/vers/laravel_temp/public

    <Directory /chemin/vers/laravel_temp/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx

Exemple de bloc `server` (à adapter) :

```nginx
root /chemin/vers/laravel_temp/public;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    include fastcgi_params;
}
```

---

## 6. Tâches planifiées (cron) et files d’attente

Si l’application envoie des emails ou des jobs en queue, configurer le cron et éventuellement un worker.

**Cron** (une seule entrée Laravel) :

```bash
* * * * * cd /chemin/vers/votre/app && php artisan schedule:run >> /dev/null 2>&1
```

**Queue** (jobs en base) : si vous utilisez des jobs (emails différés, etc.), lancer un worker en arrière-plan ou via Supervisor :

```bash
php artisan queue:work --tries=3
```

(Sur un hébergement partagé sans Supervisor, certains hébergeurs proposent un « cron » qui lance `queue:work` pendant 1 minute.)

---

## 7. Sécurité et bonnes pratiques

- Ne jamais commiter le fichier **`.env`** (il doit rester uniquement sur le serveur).
- En production : **APP_DEBUG=false** pour ne pas exposer les erreurs.
- Utiliser **HTTPS** partout ; si vous forcez HTTPS au niveau serveur, vous pouvez ajouter en `.env` : `SESSION_SECURE_COOKIE=true`.
- Vérifier les **permissions** : `storage/` et `bootstrap/cache/` doivent être en écriture par le serveur web (souvent 755 ou 775 selon l’hébergeur).
- Mettre à jour régulièrement Laravel et les dépendances (`composer update`, tests, puis déploiement).

---

## 8. o2switch : faire pointer le domaine vers le dossier `public`

Si en visitant votre domaine vous voyez la **liste des dossiers** (app, config, public, etc.) au lieu du site, c’est que la racine du domaine pointe vers la racine du projet Laravel au lieu du dossier **`public`**.

### Méthode recommandée : modifier la racine des documents dans cPanel

1. Connectez-vous à **cPanel** (o2switch).
2. Allez dans **Domaines** → **Domaines configurés** (ou **Domaines supplémentaires**).
3. Dans la liste des domaines, repérez le domaine concerné et cliquez sur **Gérer** (ou **Modifier** / **Modifier le domaine**).
4. Modifiez le champ **« Racine des documents »** (Document Root) :
   - **Avant** : quelque chose comme `public_html` ou `public_html/recrut` (le dossier où se trouve tout Laravel).
   - **Après** : le **sous-dossier `public`** de ce même dossier.  
     Exemples :
   - Si votre site est dans `public_html/recrut` → mettez **`public_html/recrut/public`**.
   - Si votre site est directement dans `public_html` → mettez **`public_html/public`**.
5. Enregistrez. Après quelques secondes, en visitant le domaine vous devriez voir Laravel et plus la liste des dossiers.

Référence : [FAQ o2switch – Configurer un nom de domaine](https://faq.o2switch.fr/cpanel/domaines/configuration-domaine/) ; [Héberger une application Symfony (même principe : pointer vers `/public`)](https://faq.o2switch.fr/guides/php/heberger-application-symfony/).

### Méthode alternative : .htaccess à la racine du projet

Si vous ne pouvez pas changer la racine des documents (par exemple domaine principal sans option), placez un fichier **`.htaccess`** **à la racine du projet Laravel** (à côté de `artisan`, pas dans `public`), avec le contenu ci-dessous. Toutes les requêtes seront alors redirigées vers le dossier `public` :

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Si votre hébergeur a déjà un `.htaccess` à la racine, ajoutez uniquement les lignes `RewriteEngine On` et `RewriteRule` en respectant les règles existantes.  
**Inconvénient** : l’URL visible restera la même, mais Laravel recevra bien les requêtes via `public/index.php`. Assurez-vous que le dossier `public` n’est pas listable (pas d’accès direct aux sous-dossiers).

Un fichier **`.htaccess.racine-exemple`** est fourni à la racine du projet : copiez-le en **`.htaccess`** à la racine si vous utilisez cette méthode.

---

## 9. Mise à jour via Git (recrute.moroccoder.com)

Pour mettre à jour le site **https://recrute.moroccoder.com** sans tout ré-uploader à la main :

### En local (sur votre PC)

1. **Initialiser Git** (une seule fois, si ce n’est pas déjà fait) :
   ```bash
   cd laravel_temp
   git init
   git add .
   git commit -m "Initial commit"
   ```

2. **Créer un dépôt distant** (GitHub, GitLab, Bitbucket, ou autre) et y pousser :
   ```bash
   git remote add origin https://github.com/VOTRE_UTILISATEUR/recrut.git
   # ou : git@github.com:VOTRE_UTILISATEUR/recrut.git
   git branch -M main
   git push -u origin main
   ```

3. **À chaque modification** : commiter et pousser :
   ```bash
   git add .
   git commit -m "Description des changements"
   git push origin main
   ```

### Sur le serveur (o2switch, SSH ou Terminal cPanel)

4. **Première fois** : cloner le dépôt dans le dossier prévu (ex. `public_html/recrute` ou le chemin utilisé pour la racine des documents) :
   ```bash
   cd ~/public_html   # ou le répertoire parent de votre site
   git clone https://github.com/VOTRE_UTILISATEUR/recrut.git recrute
   cd recrute
   ```
   Puis créer le **`.env`** (copie de `.env.example`), le remplir, et exécuter le script de déploiement (voir ci-dessous).

5. **À chaque mise à jour** : récupérer le code et déployer :
   ```bash
   cd ~/public_html/recrute   # adapter au chemin réel de votre projet
   git pull origin main
   bash deploy.sh
   ```

Le script **`deploy.sh`** à la racine du projet fait : `composer install --no-dev`, `php artisan migrate --force`, `storage:link`, puis mise en cache de la config, des routes et des vues. Vous pouvez l’éditer pour ajouter `npm run build` si vous compilez les assets sur le serveur.

**À vérifier** : le **document root** du domaine **recrute.moroccoder.com** doit pointer vers le dossier **`public`** du projet (ex. `public_html/recrute/public`). Le fichier **`.env`** ne doit pas être dans Git ; il reste uniquement sur le serveur.

---

## 10. Après le premier déploiement

1. Tester la page d’accueil, l’inscription candidat/recruteur, la connexion (email + Google + Facebook).
2. Tester une offre, une candidature, le paiement Stripe (mode live) et le webhook Stripe.
3. Vérifier l’envoi d’emails (activation recruteur, refus candidat, etc.).
4. Changer le mot de passe du compte admin de test (**admin@recrutement.ma**).

---

En cas de problème, consulter les logs : `storage/logs/laravel.log`.

**Site concerné** : https://recrute.moroccoder.com
