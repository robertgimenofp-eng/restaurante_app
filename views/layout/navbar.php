<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2">
    <div class="container-fluid px-lg-5">

        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="/restaurante_app/public/img/logovivaeats.svg" alt="Logo" class="nav-logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            
            <ul class="navbar-nav mx-auto align-items-center">
                <li class="nav-item"><a class="nav-link fw-semibold px-3" href="index.php?controller=Restaurantes&action=index">Restaurantes</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold px-3" href="index.php?controller=Menu&action=index">Menús y Packs</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold px-3" href="index.php?controller=Producto&action=index">Productos</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold px-3" href="index.php?controller=EstiloVida&action=index">Estilo de Vida VivaEats</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold px-3" href="index.php?controller=Promociones&action=index">Promociones</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                
                <?php if(isset($_SESSION['identity']) && $_SESSION['identity']->rol == 'admin'): ?>
                    <a class="nav-link text-warning fw-bold d-flex align-items-center small" href="index.php?controller=Admin&action=index" style="color: #6c757d !important; white-space: nowrap;">
                        <span class="me-1">⚙️</span> Panel Admin
                    </a>
                <?php endif; ?>

                <a href="index.php?controller=Producto&action=index" class="btn btn-pide-ahora px-4 fw-bold text-white text-uppercase" style="background:#6799ab; border-radius: 12px; font-size: 0.9rem; letter-spacing: 0.5px;">
                    PIDE AHORA
                </a>

                <div class="dropdown">
                    <a class="nav-link text-dark p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-3">
                        <li class="mb-2">
                            <span class="text-secondary fw-bold small">Hola, <?php echo $_SESSION['identity']->nombre ?? 'Gourmet'; ?></span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger d-flex align-items-center p-0 pt-2" href="index.php?controller=Auth&action=logout">
                                <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                                Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div> </div>
    </div>
</nav>