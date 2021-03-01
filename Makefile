all: help

## help:			Help
.PHONY : help
help : Makefile
	@sed -n 's/^##//p' $<

## configure:		 Initial Configuration, install dependencies.
.PHONY : configure
configure:
	docker-compose exec app composer install


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
	docker-compose exec php /vendor/bin/phpunit -c phpunit.xml.dist

