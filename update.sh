#!/usr/bin/env bash

git pull
git up
./db_migrate.sh
composer.phar install
