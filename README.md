# Plateforme de Recrutement

Une plateforme moderne de gestion de recrutement développée avec Laravel. Cette application permet aux recruteurs de publier des offres d'emploi, de gérer les candidatures et de planifier des entretiens, tout en offrant aux candidats un espace dédié pour postuler et suivre l'état de leurs candidatures.

## Fonctionnalités Principales

### Pour les Candidats :
- **Recherche d'offres d'emploi** avec filtres multicritères (lieu, type de contrat, salaire, télétravail).
- **Profil complet** : gestion des expériences professionnelles, des formations et des compétences.
- **Suivi des candidatures** en temps réel.
- **Messagerie intégrée** pour communiquer avec les recruteurs.
- **Gestion des entretiens** : confirmation et visioconférence.
- **Offres favorites** : sauvegarde des annonces intéressantes.

### Pour les Recruteurs :
- **Publication et gestion** des offres d'emploi.
- **Tableau de bord interactif** avec statistiques de recrutement (taux de conversion, etc.).
- **Gestion des candidatures** : système de suivi (en attente, en cours, acceptée, refusée).
- **Planification d'entretiens** avec système d'évaluation intégré.
- **Messagerie directe** avec les candidats.

## Technologies Utilisées

- **Framework Backend :** Laravel (PHP)
- **Base de données :** MySQL
- **Frontend :** Blade Templates, Tailwind CSS, Alpine.js (ou Vanilla JS)
- **Design UI/UX :** Interface moderne "Glassmorphism", icônes FontAwesome, animations (Animate.css)

## Prérequis

- PHP >= 8.2
- Composer
- Serveur de base de données (MySQL)
- Node.js & NPM (pour la compilation des assets)

## Installation

1. Cloner le dépôt :
   ```bash
   git clone [URL_DU_DEPOT]
   cd APP_recrutement
   ```

2. Installer les dépendances PHP et Node :
   ```bash
   composer install
   npm install && npm run build
   ```

3. Configuration de l'environnement :
   Copiez le fichier `.env.example` en `.env` et configurez vos accès à la base de données :
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Migrations et données de test :
   ```bash
   php artisan migrate --seed
   ```

5. Lancer le serveur de développement :
   ```bash
   php artisan serve
   ```

L'application sera accessible sur `http://localhost:8000`.

## Licence

Ce projet est sous licence MIT.
"# APP_recrutement"  
