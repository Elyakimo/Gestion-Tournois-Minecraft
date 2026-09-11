# Gestion de tournois Minecraft

### Setup l'environnement de dev:

D'abord il faut fork le repo en utilisant le bouton 'fork' sur [le repo](https://github.com/ReuS-SIO/Gestion-Tournois-Minecraft). Ensuite: 

```bash
# Clone le repo
git clone https://github.com/ReuS-SIO/Gestion-Tournois-Minecraft
cd Gestion-Tournois-Minecraft

# Définir la remote sur le fork
git remote add fork <URL DE TON REPO FORKé>
# Ouvrir vs code dans le bon dossier: 
code .
```

Pour push ton code sur ton fork utilise:
```bash
git push fork
```
ss
Ensuite il faut créer une pull request pour que Malo puisse accepter ou non les changements.

**ATTENTION**: 
Dès qu'un changement à lieu sur le repo original (ReuS-SIO/Gestion-Tournois-Minecraft), il faut impérativement utiliser la commande:
```bash 
git pull origin
```
Pour récupérer le code des collègues.  

#### Créer la base de données pour l'environnement de dev

Créer la base de données:
```bash
createdb -U postgres -E UTF8 gestion_tournois_mc
```

Créer les tables:
```bash
psql -U postgres -d gestion_tournois_mc -f create_database.sql
```

Vérifier:
```bash
psql -U postgres -d gestion_tournois_mc
# \dt              -- liste les 7 tables
# \d match         -- détail d'une table + contraintes
# \di              -- les index
```
Pour connecter la bdd:
# Base de données — Gestion Tournois Minecraft

Ce document décrit la mise en place de la base de données PostgreSQL pour le projet.

## Prérequis

- PostgreSQL 14+ (testé avec PostgreSQL 18)
- Composer (pour la librairie `phpdotenv`, utilisée par `connexion.php`)

## 1. Démarrer PostgreSQL

Sous WSL Ubuntu :

```bash
sudo systemctl start postgresql
# ou, si systemd n'est pas activé :
sudo service postgresql start
```

Vérifiez que le service tourne :

```bash
sudo systemctl status postgresql
```

## 2. Configurer le rôle `postgres`

Définissez un mot de passe pour le rôle `postgres` (nécessaire pour l'authentification en connexion TCP `localhost`) :

```bash
sudo -u postgres psql -c "ALTER ROLE postgres WITH PASSWORD 'votre_mot_de_passe';"
```

## 3. Créer la base de données

```bash
sudo -u postgres createdb bdd_tournoi_minecraft
```

Vérifiez qu'elle existe :

```bash
sudo -u postgres psql -c "\l"
```

## 4. Importer le schéma

```bash
sudo -u postgres psql -d bdd_tournoi_minecraft -f create_database.sql
```

Vérifiez que les tables ont bien été créées :

```bash
sudo -u postgres psql -d bdd_tournoi_minecraft -c "\dt"
```

Vous devriez voir : `equipe`, `joueur`, `tournoi`, `carte`, `arbitre`, `match`, `inscription`, `participation`, `resultat_match`.

## 5. Configurer les variables de connexion

Copiez le fichier d'exemple et remplissez-le avec vos identifiants locaux :

```bash
cp .env.exemple .env
```

Contenu de `.env` :

```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=bdd_tournoi_minecraft
DB_USER=postgres
DB_PASSWORD=votre_mot_de_passe
```

⚠️ Le fichier `.env` est ignoré par Git — ne le poussez jamais sur GitHub, il contient vos identifiants personnels. Chaque personne du projet doit avoir son propre `.env`.

## 6. Tester la connexion

En ligne de commande, indépendamment du code PHP :

```bash
psql -U postgres -h 127.0.0.1 -p 5432 -d bdd_tournoi_minecraft
```

Ou via le script PHP fourni (`connexion.php`), en lançant le serveur de dev :

```bash
php -S localhost:8000
```
puis en ouvrant `http://localhost:8000/connexion.php`.

## Schéma de la base

Le fichier `create_database.sql` définit les tables suivantes :

| Table | Rôle |
|---|---|
| `equipe` | Équipes participantes |
| `joueur` | Joueurs, rattachés à une équipe |
| `tournoi` | Tournois organisés |
| `carte` | Cartes/terrains de jeu |
| `arbitre` | Arbitres des matchs |
| `match` | Matchs d'un tournoi |
| `inscription` | Inscription d'une équipe à un tournoi (jonction) |
| `participation` | Participation d'une équipe à un match (jonction) |
| `resultat_match` | Résultats détaillés d'un match |

## Dépannage

**`fe_sendauth: no password supplied`**
Mot de passe vide envoyé par le script alors que PostgreSQL en exige un. Renseignez `DB_PASSWORD` dans `.env`.

**`password authentication failed for user "..."`**
Le mot de passe dans `.env` ne correspond pas à celui du rôle PostgreSQL. Réinitialisez-le :
```bash
sudo -u postgres psql -c "ALTER ROLE postgres WITH PASSWORD 'nouveau_mot_de_passe';"
```

**`database "create_database" does not exist`**
La base n'a pas été créée (voir étape 3).

**`could not find driver`**
L'extension PHP `pdo_pgsql` n'est pas installée :
```bash
sudo apt install php-pgsql
```

**`Connection refused`**
PostgreSQL n'est pas démarré (voir étape 1), ou n'écoute pas sur le port 5432 :
```bash
sudo ss -tlnp | grep postgres
```
*
### Les rôles
- Chef de projet: Damien
- Git Master: Malo
- BDD: Tehie, Ricky, Sean
- Back-end: Ethan M, Elyakim, Ethan N, Gaël
- Front-end: Axel, Nicolas, Walu, Sosefo
- Testeur + Doc: Daniel

coucou