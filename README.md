### INSTALL LIBRARIES
 - **composer install**
 - go to public
 	- **npm install**
 
### SET UP .ENV
 - DATABASE_URL 
 - MAILER_URL
 	- for gmail: gmail://username:password@localhost?encryption=tls&auth_mode=oauth

### PREPARE DATABASE
 - php bin/console doctrine:database:create
 - php doctrine:migrations:migrate


### PREPARE APP
 - php bin/console server:run
 	- start the app
 - php bin/console app:init
 	- creates default Settings Entity
 	- requires CompanyName (string) and Version (1.0.0)
 	- run only once!
 - php bin/console app:create-user
 	- create your first user
 	- then you will receive on given email verification link