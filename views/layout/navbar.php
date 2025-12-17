<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center" href="/restaurante_app/public/views/home.php">
            <img src="/restaurante_app/public/img/logo.png" alt="Logo" height="40" class="me-2">
        </a>

        <!-- BOTÓN MOVIL -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENÚ -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="/restaurante_app/public/restaurantes">Restaurantes</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="/restaurante_app/public/menus">Menús y Packs</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="/restaurante_app/public/productos">Productos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="/restaurante_app/public/vivaeats">Estilo de Vida VivaEats</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="/restaurante_app/public/promociones">Promociones</a>
                </li>

                <!-- BOTÓN -->
                <li class="nav-item ms-3">
                    <a href="/restaurante_app/public/hacer-pedido" class="btn btn-primary px-4 fw-bold" style="background:#6799ab;border:none;">
                        PIDE AHORA
                    </a>
                </li>
                <!-- ICONO USUARIO -->
                <li class="nav-item ms-3 d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-secondary fw-bold" style="font-size: 0.9rem;">
                                Hola, <?php echo $_SESSION['user_name'] ?? 'Gourmet'; ?>
                            </span>

                            <a href="#" class="nav-icon-link text-dark" title="Mi Perfil">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </a>

                            <a href="index.php?controller=Auth&action=logout" class="text-danger" title="Cerrar Sesión">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                            </a>
                        </div>

                    <?php else: ?>
                        <a href="index.php?controller=Auth&action=showLogin" class="nav-icon-link text-dark" title="Iniciar Sesión">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                    <?php endif; ?>

                </li>
            </ul>
        </div>
    </div>
</nav>