setup:
	docker-compose up -d --build
	docker-compose exec app composer1 install -n
	docker-compose exec app chmod -R 777 var
test:
	docker-compose exec app php ./bin/phpunit --testdox