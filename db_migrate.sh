#!/bin/bash

if [ -z "$environment" ]; then
    export environment=prod
fi

echo "==================================================================="
echo "== Database migrations                                           =="
echo "==================================================================="

getopts ":y" noConfirmation;

confirmed() {
    if [[ $noConfirmation =~ ^[y]$ ]]
    then
        return 0
    else
        read -p "Do you want to apply the changes? [y/n] " confirmation
        [[ $confirmation =~ ^[Yy]$ ]] && return 0 || return 1
    fi
}

php vendor/bin/ruckus.php db:status $@ || exit 1

if ( confirmed )
then
  php vendor/bin/ruckus.php db:migrate $@ && \
  echo "You can see all executed sqls in logs/<env>.log"
else
  echo ""
fi