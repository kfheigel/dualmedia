run: build up composer-install cache-clear seed
test: phpcs lint phpspec unit-tests integration-tests phpstan-run

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

bash:
	docker exec -it app /bin/bash

nginx:
	docker exec -it nginx /bin/bash

composer-install:
	docker exec -it app composer install

sleep:
	sleep 5

seed:
	docker exec -it app bin/console doctrine:database:create
	docker exec -it app bin/console doctrine:migrations:migrate --no-interaction
	docker exec -it app bin/console support:fixture:salary
	docker exec -it app bin/console support:fixture:department
	docker exec -it app bin/console support:fixture:employee
	docker exec -it app bin/console doctrine:database:create --env=test --no-interaction
	docker exec -it app bin/console doctrine:migrations:migrate --env=test --no-interaction

cache-clear:
	docker exec -it app bin/console cache:clear

consume-async:
	docker exec -it app bin/console messenger:consume async -vv

phpcs:
	docker exec -it app vendor/bin/phpcs

lint:
	docker exec -it app bin/console lint:yaml config --parse-tags

phpspec:
	docker exec -it app vendor/bin/phpspec run --format=pretty

unit-tests:
	docker exec -it app ./bin/phpunit -c phpunit.xml --testdox --testsuite unit

integration-tests:
	docker exec -it app ./bin/phpunit -c phpunit.xml --testdox --testsuite integration

phpstan-run:
	docker exec -it app vendor/bin/phpstan analyse -c phpstan.neon
