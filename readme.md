# Open Backend
Backend service for [Open](https://github.com/rdelbaere/open) 

## Installation
### Installation in local environment

* Clone the project.
* Build and run the containers with `docker compose up -d`.
* Configure your .env.local file (for database, ...).
* Execute `docker compose exec php composer install`.
* Execute `docker compose exec php php bin/console doctrine:migrations:migrate`.


### Installation on webserver

> :warning: Do not use Docker configuration in a public environment

* Clone the project.
* Execute `composer install`.
* Configure your .env.local file (for database, ...).
* Execute `php bin/console doctrine:database:create`.
* Execute `php bin/console doctrine:migrations:migrate`.

