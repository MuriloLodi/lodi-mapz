<?php 
include 'conexao.php';
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'?>
</head>
<body>
    <?php include 'includes/header.php'?>
  <!-- Bandeiras -->
  <div class="flags">
    <img src="assets/img/br.png" alt="Português" onclick="showLang('pt')">
    <img src="assets/img/us.png" alt="English" onclick="showLang('en')">
    <img src="assets/img/es.png" alt="Español" onclick="showLang('es')">
  </div>

  <!-- Conteúdo inicial -->
  <div id="conteudo-termos">
    <?php include "includes/idioma/termos-pt.php"; ?>
  </div>

  <?php include 'includes/footer.php'?>
  <?php include 'includes/scripts.php'?>
</body>
</html>