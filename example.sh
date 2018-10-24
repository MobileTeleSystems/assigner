#!/usr/bin/env bash

docker-compose up -d
docker exec assigner bash -c "php example.php"