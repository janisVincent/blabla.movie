## Blabla.movie

### Pré-requis
- Docker
- Docker compose 

### Intialisation du projet
```shell script
docker-compose up --build -d
```
- Installer les composants requis au fonctionnement de l'application :
```shell script
docker exec -it bbmov_php composer install
```
- Générer le schéma de la base de données :
```shell script
docker exec -it bbmov_php php bin/console doctrine:schema:update --force
```
- Générer les certificats JWT : 
```shell script
docker exec -it bbmov_php mkdir -p config/jwt
docker exec -it bbmov_php openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
docker exec -it bbmov_php openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
Attention à reporter la passphrase choisie (`JWT_PASSPHRASE`) dans le fichier `.env`

- Ajouter ensuite le host :
```text
127.0.0.1	blabla.movie
```
Un PhpMyAdmin sera lui exposé sur le port 8080.

### Utilisation de l'API
Consulter la [documentation de l'API](http://blabla.movie)

#### Authentification
L'utilisation des différents webservices est soumis à la présence d'un token d'authentification de type `Bearer`.
 
Seules les webservices de création de compte `POST /api/users` et de génération d'un token `POST /authentication_token` sont exempts de cette règle.

#### Formats de sortie
Les WS proposent deux formats de sortie à renseigner dans le header `Content-Type` de la requête :
- `application/json` pour une sortie JSON classique
- `application/ld+json` pour une sortie [JSON-LD](https://en.wikipedia.org/wiki/JSON-LD)

#### Et concrètement ?
1. Création d'un compte : `POST /api/users`
1. Récupération d'un token : `POST /authentication_token`
1. Appeler les autres WS avec le header `Authorization: Bearer {{token}}` (Attention ! Ne pas oublier de renseigner ce header via le bouton "Authorize" pour pouvoir utiliser la fonction "Try out" de la documentation)
