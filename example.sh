#!/usr/bin/env bash

docker-compose up -d
docker exec assigner bash -c "composer install && php example.php"