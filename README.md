# Projet CRUD POO en PHP

Ce projet est un exercice de **Programmation Orientée Objet (POO)** en PHP, implémentant un système **CRUD** (Create, Read, Update, Delete) pour la gestion des utilisateurs. Il met en pratique les concepts de la POO tout en utilisant une base de données MySQL pour stocker les informations des utilisateurs.

## Fonctionnalités

- **Créer** un nouvel utilisateur
- **Lire** les informations d'un utilisateur
- **Mettre à jour** les informations d'un utilisateur
- **Supprimer** un utilisateur
- **Authentification** des utilisateurs (login/logout)

## Prérequis

- PHP 7.4 ou supérieur
- MySQL
- Serveur web (Apache, Nginx, etc.)
- Composer (gestionnaire de dépendances PHP)

## Installation

1. **Cloner le dépôt :**
    ```bash
    git clone https://github.com/votre-utilisateur/exo-crud-poo-php-2e.git
    cd exo-crud-poo-php-2e
    ```

2. **Installer les dépendances avec Composer :**
    ```bash
    composer install
    ```

3. **Configurer votre base de données MySQL :**
    - Créez une base de données et un utilisateur MySQL.
    - Importez le fichier de migration pour créer les tables nécessaires :
      ```bash
      mysql -u votre-utilisateur -p votre-base-de-donnees < migrations/users.sql
      ```

4. **Configurer les paramètres de connexion à la base de données :**
    Modifiez le fichier `config/database.php` avec vos informations de connexion :
    ```php
    <?php
    return [
         'host' => 'localhost',
         'dbname' => 'votre-base-de-donnees',
         'user' => 'votre-utilisateur',
         'password' => 'votre-mot-de-passe',
    ];
    ```

5. **Lancer l'application :**
    Utilisez un serveur web local ou démarrez un serveur intégré PHP :
    ```bash
    php -S localhost:8000
    ```

## Structure du Projet

- `App/Entity/User.php` : Classe représentant un utilisateur.
- `App/Repository/UserRepository.php` : Classe gérant les opérations CRUD sur les utilisateurs.
- `autoload.php` : Fichier d'autoload pour le chargement automatique des classes.
- `index.php` : Page d'accueil listant les utilisateurs.
- `create.php` : Page pour créer un nouvel utilisateur.
- `update.php` : Page pour modifier un utilisateur existant.
- `delete.php` : Page pour supprimer un utilisateur.
- `login.php` : Page de connexion.
- `logout.php` : Page de déconnexion.
- `migrations/users.sql` : Script SQL pour créer la base de données et les tables.

## Contribution

Les contributions sont les bienvenues ! Si vous avez des suggestions ou des améliorations, n'hésitez pas à créer une issue ou à soumettre une pull request.