<?php

declare(strict_types=1);

namespace Ecxod\Prepend;

ini_set("memory_limit", "512M");
ini_set("default_charset", "utf-8");
header("Cache-Control: max-age=0"); // =60*60*24 = 86400
header("Access-Control-Allow-Origin: *");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

# Env Vars
$_ENV['DOTENV'] = strval(realpath($_SERVER['DOCUMENT_ROOT'] . '/../'));
$_ENV['TWIG'] = strval(realpath($_SERVER['DOCUMENT_ROOT'] . '/../templates'));
$_ENV['AUTOLOAD'] = strval(realpath($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php'));

if (is_readable(filename: $_ENV["AUTOLOAD"])) {
    require_once($_ENV["AUTOLOAD"]);
} else {
    die("Ganz Schlimmer Fehler!");
}

$dotenv = \Dotenv\Dotenv::createImmutable(paths: $_ENV['DOTENV']);
$dotenv->load();
$dotenv->required('CHARSET')->allowedValues(['UTF-8', 'UTF8', 'utf8', 'utf-8']);
$dotenv->required('AUTOLOAD')->notEmpty();
$dotenv->required('TERM')->notEmpty();
$dotenv->required('BS')->notEmpty();
$dotenv->required('CSS')->notEmpty();

$dotenv->required('DIST')->notEmpty();

new \Ecxod\Symlink\symlink;

if (function_exists("Sentry\init") or !empty($_ENV['SENTRY_DSN'])) {
    \Sentry\init(['dsn' => $_ENV['SENTRY_DSN']]);
} else {
    die('ERROR 102');
}


