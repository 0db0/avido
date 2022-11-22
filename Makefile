UID := $(shell id -u)

up:
	#cp .env.example .env
	docker-compose build --build-arg uid=$(UID)
	docker-compose up -d
	./scripts/composer.sh install

start:
	docker-compose up -d

stop:
	docker-compose stop

down:
	docker-compose down --remove-orphans

init:
	docker-compose exec db psql -U developer -c 'CREATE IF NOT EXISTS avido_test'

ps:
	docker-compose ps

composer:
	./scripts/composer.sh $(c)

console:
	./scripts/console.sh $(c)
