<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pagamento Falhou ❌</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex align-items-center justify-content-center vh-100">
  <div class="text-center">
    <h1 class="text-danger mb-3">❌ Ocorreu um erro</h1>
    <p>Infelizmente não foi possível processar seu pagamento.</p>
    <a href="loja.php" class="btn btn-secondary mt-3">Tentar novamente</a>
  </div>
</body>
</html>
