<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/libs/discord_oauth.php';

try {
  $authUrl = discord_oauth_url();
  header('Location: '.$authUrl);
  exit;
} catch (Throwable $e) {
  http_response_code(500);
  echo "Erro ao iniciar login com Discord: ".$e->getMessage();
}
