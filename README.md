# Togolese Soccer Tournament Host City Generator

Application web développée pour la Fédération Togolaise de Football (FTF) pour la sélection impartiale et aléatoire des villes hôtes des matchs du tournoi national de football.

---

## Objectif de l'application

Cette application vise à :  
1. Générer des paires de villes pour les matchs de **Kpessekou** (playoffs) en respectant des règles d'exclusivité par région et ville.  
2. Permettre aux utilisateurs de sélectionner des régions pour les matchs de **Zobibi** (demi-finales et finale), avec attribution aléatoire des villes au sein des régions sélectionnées.  

L'objectif est de garantir une répartition équitable et aléatoire des matchs dans les différentes villes et régions du Togo.

---

## Fonctionnalités

- **Kpessekou (Playoffs)** :  
  - Génération aléatoire de paires de villes.  
  - Garantit que chaque ville et région est utilisée au moins une fois avant répétition.

- **Zobibi (Semi-Finales et Finale)** :  
  - Sélection des régions par l'utilisateur.  
  - Attribution aléatoire des villes dans les régions sélectionnées.

---

## Installation du projet (Laravel)

### Prérequis

1. PHP >= 8.1  
2. Composer installé  
3. Serveur web local (comme XAMPP, Laragon, ou WAMP)  
4. MySQL ou tout autre SGBD compatible Laravel  

### Étapes d'installation

1. **Clonez le projet :**  
   ```bash
   git clone https://github.com/Salimkaneki/tournois-football.git
   cd tournois-football

    **Copiez et configurez le fichier .env :**

    ```bash
    cp .env.example .env

2. **Modifiez le fichier .env pour y ajouter vos informations de base de données :**

    ```bash
    cp .env.example .env

        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=ftf_tournament
        DB_USERNAME=root
        DB_PASSWORD=
3. **Installez les dépendances :**

    ```bash
    composer install

4. **Exécutez les migrations et seeders pour initialiser la base de données :**

    ```bash
    php artisan migrate
    php artisan db:seed

5. Lancez le serveur local :

    ```bash
    php artisan serve

Accédez à l'application via http://localhost:8000.


## Technologies utilisées 
- Backend : Laravel 10
- Base de données : MySQL
- Frontend : Blade Templating (avec Tailwind pour le style, Highlight.js)
- Gestion des dépendances : Composer
## Comment utiliser l'application

- Accédez à l'interface utilisateur via http://localhost:8000.
- Choisissez le type de match :
- Kpessekou : La génération des paires de villes se fait automatiquement.
- Zobibi : Sélectionnez les régions pour chaque match, et les villes seront générées aléatoirement.
- Visualisez les résultats directement dans l'interface.
