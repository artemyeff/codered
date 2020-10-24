#!/bin/bash

echo -e "\n \e[96mСоздание public/files \033[0m \n"
mkdir -p ../public/files
docker-compose exec php chown -R www-data:www-data public/files

echo -e "\n \e[96mУстановка composer зависимостей \033[0m \n"
docker-compose exec php composer install

echo -e "\n \e[96mПрименение миграций \033[0m \n"
docker-compose exec php php bin/console --no-interaction doctrine:migrations:migrate
docker-compose exec php php bin/console --no-interaction doctrine:fixtures:load --append
