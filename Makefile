start:
	php artisan serve --host 0.0.0.0

start-frontend:
	php artisan inertia:start-ssr

setup: env-prepare install key prepare-db
	npm run build

env-prepare:
	cp -n .env.example .env

install:
	composer install
	npm ci

key:
	php artisan key:gen --ansi

prepare-db:
	php artisan migrate:fresh --force --seed

test:
	php artisan test

test-coverage:
	XDEBUG_MODE=coverage php artisan test --coverage-clover ./build/logs/clover.xml

lint:
	composer exec phpcs -v

lint-fix:
	composer exec phpcbf

compose:
	docker-compose up

compose-test:
	docker-compose run web make test

compose-bash:
	docker-compose run web bash

compose-setup: compose-build
	docker-compose run web make setup

compose-build:
	docker-compose build

compose-db:
	docker-compose exec db psql -U postgres

compose-down:
	docker-compose down -v
