<?php
$config['db']['dbname'] = 'dartboard_test';
$config['db']['user'] = 'ouzo';
$config['db']['pass'] = 'password';
$config['db']['driver'] = 'pgsql';
$config['db']['host'] = '127.0.0.1';
$config['db']['port'] = '5432';
$config['global']['prefix_system'] = '/dartboard';
$config['sql_dialect'] = '\\Ouzo\\Db\\Dialect\\PostgresDialect';

return $config;