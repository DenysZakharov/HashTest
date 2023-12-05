# HashTest
Basic Symfony Controller with tests

## Setup

### Docker

**Before run project in docker needed:**
- Install [docker](https://docs.docker.com/install/) and [docker-compose](https://docs.docker.com/compose/install/).
- Clone project.
- [_Skip for macOS users_] Create the docker group if it does not exist `sudo groupadd docker`
- [_Skip for macOS users_] Add your user to the docker group. `sudo usermod -aG docker $USER`
- [_Skip for macOS users_] Run the following command or Logout and login again and run (that doesn't work you may need to reboot your machine first) `newgrp docker`
- Run `docker-compose up -d`.
- Run `docker-compose run php composer install` to install required packages.
- Run `docker-compose run php bin/console doctrine:migrations:migrate --no-interaction` to setup db.

### Tests
#create test database

`docker-compose run php bin/console --env=test doctrine:database:create`


#create tables in test database

`docker-compose run php bin/console --env=test doctrine:schema:create`


#run fixtures in test database

`docker-compose run php bin/console --env=test doctrine:fixtures:load`

#run tests
`docker-compose run php bin/phpunit tests/`