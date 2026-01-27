# Relation Project

## 🚀 Installation avec Docker

### Prérequis
- Docker Desktop installé et lancé
- Git

### Lancement du projet

1. **Cloner le dépôt**
```bash
git clone [URL_DU_DEPOT]
cd RelationProject
```

2. **Lancer Docker**
```bash
docker-compose up -d
```

3. **Accéder au projet**
- Application : http://localhost:8080
- PHPMyAdmin : http://localhost:8081
  - Serveur : `db`
  - Utilisateur : `user`
  - Mot de passe : `secret`

### Commandes utiles

```bash
# Arrêter le projet
docker-compose down

# Voir les logs
docker-compose logs -f

# Reconstruire les conteneurs
docker-compose up -d --build

# Accéder au terminal PHP
docker exec -it php_app bash
```

## 📁 Structure du projet
- `/public` - Point d'entrée web
- `/src` - Code source (Controllers, Models, Services, Views)
- `/SQL/fixture` - Fichiers SQL d'initialisation
- `/docker` - Configuration Docker

## 🗄️ Base de données
La base de données est automatiquement créée et peuplée au premier lancement grâce au fichier `SQL/fixture/implementalltable_new.sql`.
