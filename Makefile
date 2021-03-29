setup:
	docker-compose up -d --build
	docker-compose exec -T app composer1 install -n
	docker-compose exec -T app chmod -R 777 var
	docker-compose exec -T app php bin/console lexik:jwt:generate-keypair

	make database-create
	make migrate
	make seed

	docker-compose exec -T app php bin/console rabbitmq:setup-fabric

database-create:
	docker-compose exec -T app php bin/console doctrine:database:create -n
migrate:
	docker-compose exec -T app php bin/console doctrine:migrations:migrate -n
seed:
	docker-compose exec -T app php bin/console doctrine:fixtures:load --purge-with-truncate -n
test:
	docker-compose exec -T app php ./bin/phpunit --testdox

coverage:
	docker-compose exec -T app php vendor/bin/php-coveralls -v
	
bash:
	docker-compose exec app bash

queue-transaction:
	docker-compose exec app php bin/console rabbitmq:consumer transaction
queue-notification:
	docker-compose exec app php bin/console rabbitmq:consumer notification
queue-transaction-dead:
	docker-compose exec app php bin/console rabbitmq:consumer transaction_dead
queue-notification-dead:
	docker-compose exec app php bin/console rabbitmq:consumer notification_dead