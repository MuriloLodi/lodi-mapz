<?php
// libs/discord_oauth.php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../conexao.php';

function discord_oauth_url(): string {
  $state = bin2hex(random_bytes(16));
  $_SESSION['oauth2_state'] = $state;

  $params = http_build_query([
    'client_id'     => DISCORD_CLIENT_ID,
    'redirect_uri'  => DISCORD_REDIRECT_URI,
    'response_type' => 'code',
    'scope'         => DISCORD_OAUTH_SCOPES,
    'state'         => $state,
    'prompt'        => 'consent'
  ]);
  return "https://discord.com/api/oauth2/authorize?{$params}";
}

function discord_exchange_code(string $code): array {
  $data = [
    'client_id'     => DISCORD_CLIENT_ID,
    'client_secret' => DISCORD_CLIENT_SECRET,
    'grant_type'    => 'authorization_code',
    'code'          => $code,
    'redirect_uri'  => DISCORD_REDIRECT_URI,
  ];
  return discord_token_request($data);
}

function discord_refresh_token(string $refreshToken): array {
  $data = [
    'client_id'     => DISCORD_CLIENT_ID,
    'client_secret' => DISCORD_CLIENT_SECRET,
    'grant_type'    => 'refresh_token',
    'refresh_token' => $refreshToken,
    'redirect_uri'  => DISCORD_REDIRECT_URI,
  ];
  return discord_token_request($data);
}

function discord_token_request(array $data): array {
  $ch = curl_init('https://discord.com/api/oauth2/token');
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($data),
    CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
  ]);
  $res = curl_exec($ch);
  if ($res === false) throw new Exception('Erro na requisição de token: '.curl_error($ch));
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $json = json_decode($res, true) ?? [];
  if ($status < 200 || $status >= 300) {
    throw new Exception('Falha ao obter token do Discord: HTTP '.$status.' - '.($json['error'] ?? ''));
  }
  return $json;
}

function discord_api_me(string $accessToken): array {
  $ch = curl_init('https://discord.com/api/users/@me');
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$accessToken]
  ]);
  $res = curl_exec($ch);
  if ($res === false) throw new Exception('Erro ao buscar /users/@me: '.curl_error($ch));
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $json = json_decode($res, true) ?? [];
  if ($status < 200 || $status >= 300) {
    throw new Exception('Falha ao buscar perfil Discord: HTTP '.$status);
  }
  return $json;
}

function upsert_discord_user(mysqli $conn, array $me, array $token): array {
  $discordId = $conn->real_escape_string($me['id']);
  $username  = $conn->real_escape_string($me['username'] ?? '');
  $global    = $conn->real_escape_string($me['global_name'] ?? '');
  $email     = $conn->real_escape_string($me['email'] ?? '');
  $avatar    = $conn->real_escape_string($me['avatar'] ?? '');
  $locale    = $conn->real_escape_string($me['locale'] ?? '');
  $mfa       = !empty($me['mfa_enabled']) ? 1 : 0;

  $accessToken  = $conn->real_escape_string($token['access_token'] ?? '');
  $refreshToken = $conn->real_escape_string($token['refresh_token'] ?? '');
  $expiresIn    = (int)($token['expires_in'] ?? 0);
  $expiresAt    = $expiresIn ? date('Y-m-d H:i:s', time() + $expiresIn) : null;

  $expiresSql   = $expiresAt ? "'".$conn->real_escape_string($expiresAt)."'" : "NULL";

  $sql = "
    INSERT INTO users_discord (discord_id, username, global_name, email, avatar, locale, mfa_enabled, access_token, refresh_token, token_expires_at)
    VALUES ($discordId, '$username', '$global', '$email', '$avatar', '$locale', $mfa, '$accessToken', '$refreshToken', $expiresSql)
    ON DUPLICATE KEY UPDATE
      username = VALUES(username),
      global_name = VALUES(global_name),
      email = VALUES(email),
      avatar = VALUES(avatar),
      locale = VALUES(locale),
      mfa_enabled = VALUES(mfa_enabled),
      access_token = VALUES(access_token),
      refresh_token = VALUES(refresh_token),
      token_expires_at = VALUES(token_expires_at)
  ";
  if (!$conn->query($sql)) throw new Exception('Erro ao salvar usuário: '.$conn->error);

  $res = $conn->query("SELECT * FROM users_discord WHERE discord_id = $discordId LIMIT 1");
  return $res ? $res->fetch_assoc() : [];
}
