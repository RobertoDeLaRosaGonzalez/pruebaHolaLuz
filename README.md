## Project set up

Install and run the application.
```
docker compose up -d --build
docker compose exec php bash
composer install
```

Examples of use of the application.(Charge and discharge cant send json on swagger right now, use postman)
```
http://localhost:8080/api/doc
```

Run tests
```
docker compose exec php bash
php bin/phpunit
```
