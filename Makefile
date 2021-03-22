setup:
	docker-compose up -d --build
	docker-compose exec -T app composer1 install -n
	docker-compose exec -T app chmod -R 777 var
test:
	docker-compose exec -T app php ./bin/phpunit --testdox
bash:
	docker-compose exec app bash