<div id="carruselVivaEats" class="carousel slide mb-5" data-bs-ride="carousel">
    
    <div class="carousel-inner">
        
        <div class="carousel-item active">
            <div class="hero-carousel-item" style="background-image: url('/assets/img/fondo1.webp');">
                <div style="background: rgba(0,0,0,0.2); width:100%; height:100%;"></div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="hero-carousel-item" style="background-image: url('/assets/img/fondo2.webp');">
            </div>
        </div>
        
    </div>

    <button class="carousel-custom-btn start-0 ms-4" type="button" data-bs-target="#carruselVivaEats" data-bs-slide="prev">
        <img src="assets/img/flecha.svg" class="arrow-svg mirror-x" alt="Anterior">
        <span class="visually-hidden">Anterior</span>
    </button>

    <button class="carousel-custom-btn end-0 me-4" type="button" data-bs-target="#carruselVivaEats" data-bs-slide="next">
        <img src="assets/img/flecha.svg" class="arrow-svg" alt="Siguiente">
        <span class="visually-hidden">Siguiente</span>
    </button>

    <div class="search-overlay">
        <div class="bg-white p-4 rounded-3 shadow-sm border">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <h3 class="fw-bold mb-0" style="color: #333;">
                        <span style="color:#ff6b00;">Encuentra</span> tu restaurante
                    </h3>
                    <small class="text-muted">¡Elige entre más de 200 en toda España!</small>
                </div>
                <div class="col-md-7">
                    <form action="index.php" method="GET" class="position-relative">
                        <input type="hidden" name="controller" value="Producto">
                        <input type="hidden" name="action" value="index">
                        
                        <div class="input-group">
                            <input type="text" class="form-control rounded-pill py-2 ps-4" placeholder="Buscar..." name="search" style="border-color: #ff6b00;">
                            <button class="btn position-absolute end-0 top-0 me-2 mt-1 rounded-circle" type="submit" style="color: #ff6b00;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="mt-2 ms-2">
                            <span class="badge bg-white text-muted border rounded-pill fw-normal">Barcelona</span>
                            <span class="badge bg-white text-muted border rounded-pill fw-normal">Madrid</span>
                            <span class="badge bg-white text-muted border rounded-pill fw-normal">Valencia</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="margin-bottom: 80px;"></div>
<section class="container py-5">
    
    <div class="mb-5 text-start">
        <h2 class="fw-bold display-5 mb-1">¡Únete a VivaEats!</h2>
        
        <p class="fs-4 fst-italic fw-medium" style="color: #212529;">
            Donde comer lo cambia todo
        </p>
    </div>

    <h3 class="fw-bold mb-5 text-center" style="color: #444;">¿Qué nos diferencia?</h3>

    <div class="row g-4 text-center">
        
        <div class="col-md-3">
            <div class="feature-img-wrapper">
                <img src="assets/img/feature1.webp" class="feature-img" alt="Hamburguesa"
                     onerror="this.src='https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=500&q=60'">
                <div class="feature-icon-box bg-viva-orange">
                    <img src="assets/img/ic1.svg" class="viva-icon-svg" alt="Sabor">
                </div>
            </div>
            <h5 class="fw-bold text-uppercase mt-2">Sabor brutal,<br>sin culpa</h5>
            <p class="text-muted small px-2">Comida que parece cheat meal, pero sin remordimientos. Ingredientes reales y sabor que engancha.</p>
        </div>

        <div class="col-md-3">
            <div class="feature-img-wrapper">
                <img src="assets/img/feature2.webp" class="feature-img" alt="Bowls"
                     onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=500&q=60'">
                <div class="feature-icon-box bg-viva-blue">
                    <img src="assets/img/ic2.svg" class="viva-icon-svg" alt="Sabor">
                </div>
            </div>
            <h5 class="fw-bold text-uppercase mt-2">Flexibilidad<br>total</h5>
            <p class="text-muted small px-2">Pide lo que quieras, cuando quieras. Todo tipo de comidas a tu disposición.</p>
        </div>

        <div class="col-md-3">
            <div class="feature-img-wrapper">
                <img src="assets/img/feature3.webp" class="feature-img" alt="Ingredientes"
                     onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=500&q=60'">
                <div class="feature-icon-box bg-viva-orange">
                    <img src="assets/img/ic4.svg" class="viva-icon-svg" alt="Sabor">
                </div>
            </div>
            <h5 class="fw-bold text-uppercase mt-2">Personaliza tu<br>combo</h5>
            <p class="text-muted small px-2">Elige tu base, tu proteína, tus salsas y tus toppings. Tú mandas, nosotros cocinamos.</p>
        </div>

        <div class="col-md-3">
            <div class="feature-img-wrapper">
                <img src="assets/img/feature4.webp" class="feature-img" alt="Deporte"
                     onerror="this.src='https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=500&q=60'">
                <div class="feature-icon-box bg-viva-blue">
                    <img src="assets/img/ic3.svg" class="viva-icon-svg" alt="Sabor">
                </div>
            </div>
            <h5 class="fw-bold text-uppercase mt-2">Energía<br>non-stop</h5>
            <p class="text-muted small px-2">Comer sano no tiene horario. Sigue al ritmo de tu cuerpo (y tus ganas).</p>
        </div>

    </div>
</section>
<section class="newsletter-section">
    <div class="container">
        
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="newsletter-title">¿Quieres estar al día de todas las novedades y promociones?</h2>
                <p class="newsletter-subtitle">
                    Déjanos tu contacto y hazte además con un producto gratis en tu primer pedido.
                </p>
            </div>
        </div>

        <form action="#" method="POST">
            <div class="row g-3"> <div class="col-md-6">
                    <label class="form-label custom-label">Nombre <span class="asterisk">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" class="form-control custom-input" required>
                        <i class="far fa-question-circle help-icon"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label custom-label">Región <span class="asterisk">*</span></label>
                    <select class="form-select custom-input" required>
                        <option selected disabled>Selecciona tu región o provincia</option>
                        <option value="madrid">Madrid</option>
                        <option value="bcn">Barcelona</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label custom-label">Email <span class="asterisk">*</span></label>
                    <div class="input-wrapper">
                        <input type="email" class="form-control custom-input" required>
                        <i class="far fa-question-circle help-icon"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label custom-label">Restaurante <span class="asterisk">*</span></label>
                    <select class="form-select custom-input" required>
                        <option selected disabled>Selecciona tu restaurante habitual</option>
                        <option value="1">Centro Comercial</option>
                        <option value="2">Calle Principal</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label custom-label">Teléfono Móvil <span class="asterisk">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text custom-prefix">
                            <span class="me-1">🇪🇸</span> ▾ +34
                        </span>
                        <input type="tel" class="form-control custom-input" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="form-check mb-2">
                        <input class="form-check-input custom-check" type="checkbox" id="checkPrivacy" required>
                        <label class="form-check-label text-muted small" for="checkPrivacy">
                            He leído y acepto la <a href="#" class="text-dark text-decoration-underline">Política de Privacidad y Protección de Datos (RGPD)</a>. <span class="asterisk">*</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input custom-check" type="checkbox" id="checkNews" required>
                        <label class="form-check-label text-muted small" for="checkNews">
                            Quiero estar al día de todas las noticias, eventos y promociones de mi club. <span class="asterisk">*</span>
                        </label>
                    </div>
                </div>

                <div class="col-12 text-end mt-4">
                    <button type="submit" class="btn btn-submit-orange">Enviar</button>
                </div>
            </div>
        </form>
    </div>
</section>
<section class="app-section">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-md-5 text-center mb-5 mb-md-0">
                <img src="assets/img/appviva.webp" alt="App VivaEats" class="img-fluid phone-mockup">
            </div>

            <div class="col-md-7 ps-md-5">
                <h2 class="app-title">VivaEats estrena App</h2>
                <p class="app-subtitle">Tu menú favorito va contigo donde estés</p>

                <ul class="app-features-list">
                    <li>
                        <img src="assets/img/ic5.svg" class="list-icon" alt="icon">
                        Reserva tus comidas favoritas
                    </li>
                    <li>
                        <img src="assets/img/ic5.svg" class="list-icon" alt="icon">
                        Come donde quieras: disfruta de tu comida saludable en casa, en la oficina o recógela al momento.
                    </li>
                    <li>
                        <img src="assets/img/ic5.svg" class="list-icon" alt="icon">
                        Accede a planes nutricionales y recetas exclusivas
                    </li>
                    <li>
                        <img src="assets/img/ic5.svg" class="list-icon" alt="icon">
                        Tips, retos y consejos para sacar lo mejor de cada comida.
                    </li>
                    <li>
                        <img src="assets/img/ic5.svg" class="list-icon" alt="icon">
                        Vive nuevas experiencias gastronómicas
                    </li>
                    <li>
                        <img src="assets/img/ic5.svg" class="list-icon" alt="icon">
                        Consulta tu área personal
                    </li>
                    <li>
                        <img src="assets/img/ic5.svg" class="list-icon" alt="icon">
                        Controla tus pedidos, tus favoritos y tus recompensas.
                    </li>
                    <li class="mt-3 fw-bold">
                        <img src="assets/img/ic5.svg" class="list-icon" alt="icon">
                        ¡Y mucho más!
                    </li>
                </ul>

                <div class="store-buttons mt-4">
                    <a href="#" class="store-btn">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play">
                    </a>
                    <a href="#" class="store-btn">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store">
                    </a>
                    <a href="#" class="store-btn">
                         <img src="https://upload.wikimedia.org/wikipedia/commons/9/9e/Huawei_AppGallery_Badge_Black_EN.svg" alt="App Gallery"> 
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>