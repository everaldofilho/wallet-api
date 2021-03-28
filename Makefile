setup:
	docker-compose up -d --build
	docker-compose exec -T app composer1 install -n
	docker-compose exec -T app chmod -R 777 var
	docker-compose exec -T app php bin/console lexik:jwt:generate-keypair
	docker-compose exec -T app php bin/console doctrine:database:create -n
	make migrate
	make seed

	# Habilita transferencia entre filas
	docker-compose exec -T rabbitmq rabbitmq-plugins enable rabbitmq_shovel rabbitmq_shovel_management

	# Cria estrutura de fila
	docker-compose exec -T app php bin/console rabbitmq:setup-fabric
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
routers:
	docker-compose exec app php bin/console debug:router
reset:
	docker-compose exec app rm migrations/*.php
	docker-compose exec app php bin/console doctrine:schema:drop --force -n
	docker-compose exec app php bin/console make:migration -n
	make migrate
	make seed

queue-transaction:
	docker-compose exec app php bin/console rabbitmq:consumer transaction
queue-notification:
	docker-compose exec app php bin/console rabbitmq:consumer notification
queue-transaction-dead:
	docker-compose exec app php bin/console rabbitmq:consumer transaction_dead
queue-notification-dead:
	docker-compose exec app php bin/console rabbitmq:consumer notification_dead