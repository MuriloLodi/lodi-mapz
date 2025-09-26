<?php
session_start();
include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user["senha"])) {
        if ($user["tipo"] === "admin") {
            $_SESSION["admin_id"] = $user["id"];
            $_SESSION["admin_nome"] = $user["nome"];
            header("Location: home.php");
            exit;
        } else {
            $erro = "Acesso permitido apenas para administradores!";
        }
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel ADM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">


    <script src="assets/js/functions.js" defer></script>


    <title>Login Admin</title>
</head>

<body class="vh-100">
    <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">

                                    <div class="text-center">
                                        <img src="assets/img/logo.jpeg"
                                            style="border-radius: 30%; width: 85px;" alt="logo">
                                        <h4 class="mt-2 mb-5 pb-1">Nós somos a equipe Lodz Network</h4>
                                    </div>
                                    <?php if (!empty($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
                                    <form method="POST">
                                        <p>Por favor, faça login na sua conta</p>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="email" name="email" class="form-control" required />
                                            <label class="form-label">Email</label>
                                        </div>

                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="password" name="senha" class="form-control" required />
                                            <label class="form-label">Senha</label>
                                        </div>

                                        <div class="text-center pt-1 mb-5 pb-1">
                                            <button class="btn btn-primary btn-block fa-lg gradient-custom-1 entrar mb-3" type="submit">Entrar</button>
                                            <div>
                                                <a class="text-muted d-block" href="#!">Esqueceu a senha?</a>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                    <h4 class="mb-4">Somos mais do que uma empresa</h4>
                                    <p class="small mb-0"> Na Lodz Network, acreditamos em conectar pessoas e tecnologia de forma inovadora. Nossa equipe é dedicada a entregar soluções inteligentes, seguras e eficientes, ajudando nossos clientes a alcançar seus objetivos com confiança. Cada projeto é tratado com excelência, criatividade e atenção aos detalhes, porque nosso compromisso vai além do serviço: buscamos criar experiências que realmente fazem a diferença.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/scripts.php' ?>
</body>

</html>