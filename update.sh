#!/usr/bin/env bash

hg pull
hg up
./db_migrate.sh
composer.phar install
