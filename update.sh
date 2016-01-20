#!/usr/bin/env bash

git pull
./db_migrate.sh
composer.phar install
