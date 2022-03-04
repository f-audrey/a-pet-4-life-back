# projet-4-apet4life-back

## Installer le projet back

- Dans le terminal: Git clone git@github.com:O-clock-Yuna/projet-4-apet4life-back.git
- Dans le terminal: cd projet-4-apet4life-back
- Dans le terminal: code .
- Dans le terminal: composer install
- Créer un fichier .env.local
- Taper cette ligne dans le fichier: `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.5.8"`
- Remplacer par le user, mdp et nom de base de donnée
- Créer la bdd dans adminer (qui correspondent bien avec les noms mis dans le .env.local)
- Optionnel: Faire un privilège pour la base de donnée
- Taper dans le terminal: php bin/console d:m:m
- Taper dans le terminal: php bin/console d:f:l
- php bin/console lexik:jwt:generate-keypair (génère la clé d'accès pour l'api)
- Lancer le serveur php: php -S 0.0.0.0:8080 -t public
