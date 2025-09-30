<?php

function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}

loadEnv(__DIR__ . '/../.env');

define('DISCORD_CLIENT_ID', $_ENV['DISCORD_CLIENT_ID']);
define('DISCORD_CLIENT_SECRET', $_ENV['DISCORD_CLIENT_SECRET']);
define('DISCORD_REDIRECT_URI', $_ENV['DISCORD_REDIRECT_URI']);
define('DISCORD_OAUTH_SCOPES', $_ENV['DISCORD_OAUTH_SCOPES']);
