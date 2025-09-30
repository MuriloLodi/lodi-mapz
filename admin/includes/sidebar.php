    <div class="vh-100 d-flex flex-nowrap">
        <div
            class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark"
            style="width: 280px">
            <a
                href="#"
                class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <img src="assets/img/logo.jpeg" alt="" style="margin-right: 5px; width: 30px; border-radius: 30px">
                <span class=" ml- fs-4">Lodz Network</span>
            </a>
            <hr />
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="home.php" class="nav-link <?php echo ($current_page == 'home.php') ? 'active' : 'text-white'; ?>" aria-current="page">
                        Home
                    </a>
                </li>
                <li>
                    <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : 'text-white'; ?>">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="produtos.php" class="nav-link <?php echo ($current_page == 'produtos.php') ? 'active' : 'text-white'; ?>">
                        Produtos
                    </a>
                </li>
                <li>
                    <a href="usuarios.php" class="nav-link <?php echo ($current_page == 'usuarios.php') ? 'active' : 'text-white'; ?>">
                        Usuários
                    </a>
                </li>
                <li>
                    <a href="cupons.php" class="nav-link <?php echo ($current_page == 'cupons.php') ? 'active' : 'text-white'; ?>">
                        Cupons
                    </a>
                </li>
            </ul>
            <hr />
            <div class="dropdown">
                <a
                    href="#"
                    class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <strong><?php echo htmlspecialchars($_SESSION['admin_nome']); ?></strong>

                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>