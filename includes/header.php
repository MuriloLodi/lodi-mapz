<section class="navbar header">
    <div class="container nav-inner">
        <a href="index.php"><img class="logo rounded-5" src="assets/img/logo.jpeg" alt=""></a>

        <button class="nav-toggle" aria-label="Abrir menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="nav-menu">
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">Página inicial</a></li>
                    <li><a href="loja.php">Produtos</a></li>
                    <li><a href="quemsomos.php">Quem somos</a></li>
                    <li><a href="termos.php">Termos</a></li>
                </ul>
            </nav>

            <div class="nav-actions">
                <ul class="nav-actions-list">
                    <?php if (!empty($_SESSION['auth'])):
                        $u = $_SESSION['auth'];
                        $avatarUrl = !empty($u['avatar'])
                            ? "https://cdn.discordapp.com/avatars/{$u['discord_id']}/{$u['avatar']}.png?size=64"
                            : "https://cdn.discordapp.com/embed/avatars/0.png";
                    ?>
                        <div class="btn-group">
                            <button class="btn btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="avatar" width="28" height="28" class="rounded-circle me-2">
                                <span class=" me-2"><?= htmlspecialchars($u['global_name'] ?: $u['username']) ?></span>
                            </button>
                            <ul class="dropdown-menu">
                                <a href="logout.php" class="btn text-black  btn-sm btn-outline-light">Sair</a>
                            </ul>
                        </div>
                    <?php else: ?>
                        <li>
                            <a class="flogin" href="login_discord.php">
                                <i class="fa-solid fa-circle-user"></i>
                                <span>Fazer Login</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li><a class="cart" href=""><i class="fa-solid fa-cart-shopping"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>