.PHONY: main nd php-bjsztql rm

main: php-bjsztql
	docker-compose up -d --remove-orphans

nd: php-bjsztql
	docker-compose up --remove-orphans

php-bjsztql:
	docker build . -t php-bjsztql

rm:
	docker-compose down
	docker-compose rm

