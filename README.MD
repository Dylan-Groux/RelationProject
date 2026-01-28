# 📚 RelationProject - Plateforme d'Échange de Livres

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker)](https://www.docker.com/)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-12.4-green)](https://phpunit.de/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

> Une plateforme web moderne permettant aux utilisateurs d'échanger des livres, de gérer leurs collections et de communiquer entre eux via une messagerie intégrée.

---

## 🎯 À propos du projet

**RelationProject** est une application web PHP full-stack développée avec une architecture MVC personnalisée. Elle permet aux passionnés de lecture de :
- Partager leurs livres avec la communauté
- Découvrir de nouveaux ouvrages
- Échanger des messages avec d'autres lecteurs
- Gérer leur profil et leur collection personnelle

---

## ✨ Fonctionnalités principales

### 👤 Gestion des utilisateurs
- Inscription et connexion sécurisées avec CSRF protection
- Profils personnalisables (photo, biographie, informations)
- Gestion de compte (modification des données, upload d'image)
- Consultation des profils publics d'autres utilisateurs

### 📖 Gestion des livres
- Ajout de livres à sa collection (titre, auteur, description, catégorie, image)
- Édition et suppression de ses propres livres
- Navigation et recherche dans le catalogue complet
- Pagination intelligente des résultats
- Affichage des détails de chaque livre avec informations du propriétaire

### 💬 Messagerie intégrée
- Système de messagerie privée entre utilisateurs
- Interface responsive (mobile & desktop)
- Liste des conversations avec aperçu du dernier message
- **Indicateurs de messages non lus** (badge + texte en gras)
- Envoi de messages depuis la page d'un livre
- Auto-scroll vers le bas de la conversation
- Envoi rapide avec la touche Entrée

### 🔒 Sécurité
- Protection CSRF sur tous les formulaires
- Validation des accès utilisateur (AbstractController)
- Gestion des sessions PHP
- Vérification des permissions (un utilisateur ne peut modifier que ses propres données)

---

## 🚀 Installation avec Docker

### Prérequis
- [Docker](https://www.docker.com/get-started) (version 20.10+)
- [Docker Compose](https://docs.docker.com/compose/install/) (version 2.0+)

### Installation rapide

1. **Cloner le repository**
```bash
git clone https://github.com/Dylan-Groux/RelationProject.git
cd RelationProject
```

2. **Lancer les conteneurs Docker**
```bash
docker-compose up -d
```

3. **Accéder à l'application**
- Application principale : [http://localhost:8080/public](http://localhost:8080/public)
- phpMyAdmin : [http://localhost:8081](http://localhost:8081)
  - Utilisateur : `user`
  - Mot de passe : `secret`

### Configuration des services

Le projet utilise 3 conteneurs Docker :

| Service | Description | Port |
|---------|-------------|------|
| **app** | Serveur web PHP 8.1 avec Apache | 8080 |
| **db** | Base de données MySQL 8.0 | 3306 |
| **phpmyadmin** | Interface de gestion MySQL | 8081 |

### Commandes utiles

```bash
# Arrêter les conteneurs
docker-compose down

# Reconstruire les conteneurs
docker-compose up -d --build

# Voir les logs
docker-compose logs -f

# Accéder au conteneur PHP
docker exec -it php_app bash

# Lancer les tests
docker exec -it php_app ./vendor/bin/phpunit tests/
```

---

## 🛠️ Installation manuelle (sans Docker)

### Prérequis
- PHP 8.1 ou supérieur
- MySQL 8.0 ou supérieur
- Composer
- Serveur web (Apache/Nginx)

### Étapes

1. **Cloner le projet**
```bash
git clone https://github.com/votre-username/RelationProject.git
cd RelationProject
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Créer la base de données**
```bash
mysql -u root -p < SQL/fixture/implementalltable_new.sql
```

4. **Configurer l'environnement**
Créer un fichier `.env` à la racine :
```env
DB_HOST=localhost
DB_NAME=relation_db
DB_USER=user
DB_PASSWORD=secret
```

5. **Configurer le serveur web**
Pointer le DocumentRoot vers le dossier `public/`

6. **Accéder à l'application**
```
http://localhost/public
```

---

## 🏗️ Architecture du projet

```
RelationProject/
├── docker/                 # Configuration Docker
│   └── php/               # Dockerfile PHP personnalisé
├── lib/                   # Bibliothèques custom
│   ├── EasyHeader.php    # Gestion des headers HTML
│   └── Router.php        # Système de routage
├── public/                # Point d'entrée public
│   ├── index.php         # Front controller
│   ├── css/              # Styles CSS
│   ├── js/               # Scripts JavaScript
│   ├── assets/           # Images et fichiers statiques
│   └── uploads/          # Uploads utilisateurs
├── SQL/                   # Scripts de base de données
│   ├── fixture/          # Données de test
│   └── schéma/           # Schémas de tables
├── src/                   # Code source principal
│   ├── Controllers/      # Contrôleurs MVC
│   │   └── Abstract/     # Contrôleur abstrait
│   ├── Models/           # Modèles de données
│   │   ├── Database/     # Gestion BDD
│   │   ├── Entity/       # Entités métier
│   │   │   └── DTO/      # Data Transfer Objects
│   │   ├── Repository/   # Couche d'accès aux données
│   │   └── Exceptions/   # Exceptions custom
│   ├── Services/         # Logique métier
│   └── Views/            # Templates PHP
├── tests/                 # Tests unitaires PHPUnit
├── vendor/                # Dépendances Composer
├── composer.json          # Configuration Composer
└── docker-compose.yml     # Configuration Docker Compose
```

### Technologies utilisées

- **Backend** : PHP 8.1+ (POO, namespaces, attributes)
- **Base de données** : MySQL 8.0 avec PDO
- **Architecture** : MVC personnalisé avec routing par attributes
- **Design Patterns** : Repository, DTO, Service Layer
- **Frontend** : HTML5, CSS3 (Grid/Flexbox), JavaScript vanilla
- **Tests** : PHPUnit 12.4
- **Containerisation** : Docker & Docker Compose

---

## 🧪 Tests

Lancer les tests PHPUnit :

```bash
# Avec Docker
docker exec -it php_app ./vendor/bin/phpunit tests/

# Sans Docker
./vendor/bin/phpunit tests/
```

---

## 📝 Routes principales

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/` | Page d'accueil |
| GET | `/books` | Liste des livres |
| GET | `/book/{id}` | Détail d'un livre |
| GET | `/user/{id}` | Profil public d'un utilisateur |
| GET | `/login` | Page de connexion |
| GET | `/register` | Page d'inscription |
| POST | `/login/userLogin` | Authentification |
| POST | `/register/userRegister` | Création de compte |
| GET | `/messagerie/{userId}` | Liste des conversations |
| GET | `/messagerie/{userId}/conversation/{conversationId}` | Conversation spécifique |
| POST | `/messagerie/conversation/{conversationId}/send` | Envoyer un message |
| GET | `/book/edit/{id}` | Édition d'un livre |
| POST | `/book/update/{id}` | Mise à jour d'un livre |
| GET | `/book/delete/{id}` | Suppression d'un livre |
| GET | `/user/account/{id}` | Page de compte utilisateur |
| POST | `/user/update/{id}` | Mise à jour du profil |


## 👨‍💻 Auteur

**Dylan** - Développeur Full Stack

---

## 🙏 Remerciements

- OpenClassrooms pour la structure de base et le suivi
- La communauté PHP pour les ressources et documentation

---

<div align="center">

**⭐ Si ce projet vous plaît, n'hésitez pas à lui donner une étoile ! ⭐**

</div>

## 🗄️ Base de données
La base de données est automatiquement créée et peuplée au premier lancement grâce au fichier `SQL/fixture/implementalltable_new.sql`.
