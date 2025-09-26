<?php
// callback_discord.php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/libs/discord_oauth.php';
require_once __DIR__ . '/conexao.php'; // $conn

// Proteções básicas
if (!isset($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth2_state'] ?? null)) {
  http_response_code(400);
  exit('State inválido. Tente novamente.');
}
unset($_SESSION['oauth2_state']);

if (!isset($_GET['code'])) {
  http_response_code(400);
  exit('Code (OAuth) ausente.');
}

try {
  // Troca code por token
  $token = discord_exchange_code($_GET['code']);

  // Busca dados do usuário
  $me = discord_api_me($token['access_token']);

  // Upsert no banco
  $user = upsert_discord_user($conn, $me, $token);

  // Seta sessão da tua aplicação
  $_SESSION['auth'] = [
    'provider'    => 'discord',
    'discord_id'  => $user['discord_id'],
    'username'    => $user['username'],
    'global_name' => $user['global_name'],
    'email'       => $user['email'],
    'avatar'      => $user['avatar'],
  ];

  // Redireciona para home ou painel
  header('Location: /lodz-mapz/index.php');
  exit;

} catch (Throwable $e) {
  http_response_code(500);
  echo "Falha no login Discord: ".$e->getMessage();
}
