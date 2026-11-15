# Vite & Gourmand

Application de traiteur événementiel (Bordeaux). Backend Symfony 8.1 +
Doctrine/MySQL : menus, commandes, comptes clients, espace employé et espace
administrateur.

## Prérequis

- **PHP 8.2 ou supérieur**, avec les extensions `ctype`, `iconv`, `mbstring`
  et `pdo_mysql` activées.
- **Composer** (https://getcomposer.org).
- **MySQL ou MariaDB** — le plus simple est d'installer
  [XAMPP](https://www.apachefriends.org/fr/index.html) et de démarrer le
  module MySQL depuis son panneau de contrôle. Aucun conteneur Docker n'est
  nécessaire (le fichier `compose.yaml` présent dans le dépôt est un reliquat
  de la recette Symfony Flex et n'est pas utilisé par ce projet).
- **Symfony CLI** (recommandé pour lancer le serveur local) :
  https://symfony.com/download — sinon le serveur PHP intégré fonctionne
  aussi (voir plus bas).

## Installation

1. **Cloner le dépôt**

   ```bash
   git clone https://github.com/rinou2/mon_projet.git
   cd vite_et_gourmand
   ```

2. **Installer les dépendances PHP**

   ```bash
   composer install
   ```

3. **Configurer la base de données**

   Le fichier `.env` (versionné) contient déjà une configuration par défaut
   pour une base MySQL locale (XAMPP, utilisateur `root` sans mot de passe).
   Si votre installation diffère (mot de passe, port, version de
   MySQL/MariaDB…), copiez `.env.local.example` en `.env.local` (ce dernier
   n'est pas versionné et surcharge `.env`) puis adaptez-le :

   ```bash
   cp .env.local.example .env.local
   ```

   Contenu de `.env.local` à adapter :

   ```
   DATABASE_URL="mysql://root@127.0.0.1:3306/mon_projet?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
   ```

   - `root` / (rien) : utilisateur et mot de passe MySQL — remplacez si le
     vôtre est différent (`mysql://user:password@...`).
   - `127.0.0.1:3306` : hôte et port de votre serveur MySQL/MariaDB.
   - `vite_et_gourmand` : nom de la base (sera créée à l'étape suivante).
   - `serverVersion` : version de votre MySQL/MariaDB, visible en haut de la
     page d'accueil de phpMyAdmin (ex. `10.4.32-MariaDB`).

4. **Créer la base et exécuter les migrations**

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Charger les données de démonstration** (menus, plats, horaires, comptes
   de test…)

   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. **Lancer le serveur local**

   Avec Symfony CLI (recommandé) :

   ```bash
   symfony server:start
   ```

   Ou avec le serveur PHP intégré :

   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

7. Ouvrir **http://localhost:8000** dans le navigateur.

## Comptes de démonstration

Tous les comptes créés par les fixtures utilisent le mot de passe
**`Password123!`** :

| Rôle                  | E-mail                              |
| --------------------- | ------------------------------------ |
| Client                | client1@vite-gourmand.test           |
| Administrateur        | admin@vite-gourmand.test             |
| Employé               | employe1@vite-gourmand.test          |    

## Commandes utiles

```bash
# Vider le cache après une modification de configuration
php bin/console cache:clear

# Après une modification d'entité : générer puis appliquer une migration
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Recharger des données de démonstration propres (supprime les données existantes)
php bin/console doctrine:fixtures:load
```

## Notes

- site online with : https://monprojet-sooty.vercel.app/
- deployed on vercel 
 
