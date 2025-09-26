<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

function isLoggedIn(): bool {
  return !empty($_SESSION['auth']);
}

function requireLogin(): void {
  if (!isLoggedIn()) {
    header('Location: /lodz-mapz/login_discord.php');
    exit;
  }
}
