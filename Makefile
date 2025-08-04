start:
	docker compose up -d

stop:
	docker compose down

build:
	docker compose build

composer-install:
	docker compose exec php composer install

phpunit:
	docker compose exec php ./vendor/bin/phpunit

bash:
	docker compose exec php bash

nginx-logs:
	docker compose logs -f nginx