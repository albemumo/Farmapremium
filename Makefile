all: help

## help:			Help
.PHONY : help
help : Makefile
	@sed -n 's/^##//p' $<

##  configure:        Initial configuration
.PHONY : configure
configure: composer-install configure-db

## composer-install:		 Initial Configuration, install dependencies.
.PHONY : composer-install
composer-install:
	docker-compose exec app composer install

##  configure-db:        Create database, execute migrations and create default user
.PHONY : configure-db
configure-db:
	docker-compose exec app bin/console doctrine:database:create --if-not-exists
	docker-compose exec app bin/console doctrine:migrations:migrate --no-interaction

##  load-fixtures:        Load fixtures
.PHONY : load-fixtures
load-fixtures:
	docker-compose exec app bin/console doctrine:fixtures:load

## build:		 Initial Build image.
.PHONY : build
build:
	docker-compose build app


## start:		 Start application containers
.PHONY : start
start:
	docker-compose up -d

## stop:		 stop application containers
.PHONY : stop
stop:
	docker-compose stop

## down:		 go down application containers
.PHONY : down
down:
	docker-compose down

## destroy:		 destroy application containers
.PHONY : destroy
destroy:
	docker-compose down -v

## logs:		 show applicaiton logs
.PHONY : logs
logs:
	docker-compose logs -f

## status:		 show application containers status
.PHONY : status
status:
	docker-compose ps

## shell:		 enters app service container shell
.PHONY : shell
shell:
	docker-compose exec app /bin/bash

## test:		 runs app UNIT test
.PHONY : test
test:
	docker-compose exec app bin/phpunit -c phpunit.xml.dist

