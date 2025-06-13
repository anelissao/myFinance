# Gestion Intelligente des Finances Personnelles

Une application web simple et intuitive pour gérer vos finances personnelles, suivre vos dépenses, et atteindre vos objectifs financiers.

## Fonctionnalités

- Authentification multi-rôles (Utilisateur, Conseiller Financier, Admin)
- Gestion des revenus et dépenses
- Catégorisation des transactions
- Budgets mensuels avec alertes
- Objectifs financiers avec suivi
- Tableau de bord analytique
- Page publique des conseillers financiers

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL ou PostgreSQL
- Node.js et NPM

## Installation

1. Clonez le dépôt :
```bash
git clone [url-du-repo]
cd myFinance
```

2. Installez les dépendances PHP :
```bash
composer install
```

3. Installez les dépendances JavaScript :
```bash
npm install
```

4. Copiez le fichier d'environnement :
```bash
cp .env.example .env
```

5. Configurez votre base de données dans le fichier `.env`

6. Générez la clé d'application :
```bash
php artisan key:generate
```

7. Exécutez les migrations et les seeders :
```bash
php artisan migrate --seed
```

## Démarrage

1. Lancez le serveur Laravel :
```bash
php artisan serve
```

2. Compilez les assets en mode développement :
```bash
npm run dev
```

## Comptes par défaut

- Admin :
  - Email : admin@example.com
  - Mot de passe : password

- Conseiller Financier :
  - Email : advisor@example.com
  - Mot de passe : password

## Structure du projet

- `app/Models/` - Modèles de l'application
- `database/migrations/` - Migrations de la base de données
- `database/seeders/` - Données initiales
- `routes/` - Routes de l'application
- `resources/views/` - Vues Blade
- `resources/js/` - Code JavaScript/Vue.js
- `resources/css/` - Styles CSS

## Contribution

1. Fork le projet
2. Créez votre branche (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## License

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.
