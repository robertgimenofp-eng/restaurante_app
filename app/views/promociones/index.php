<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Promociones y Descuentos</h1>
        <p class="text-muted lead">¡Ahorra en tus pedidos favoritos con VivaEats!</p>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">¿Tienes un código promocional?</h5>
                    
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" id="input-promo-code" class="form-control" placeholder="Ej: VIVAYUMMY" style="text-transform: uppercase;">
                        <button class="btn btn-naranja text-white fw-bold px-4" type="button" id="btn-canjear">
                            Canjear
                        </button>
                    </div>
                    
                    <div id="mensaje-promo" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="fw-bold mb-4 border-bottom pb-2">Ofertas Disponibles</h3>
    
    <div class="row g-4">
        
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm coupon-card position-relative">
                <div class="card-body d-flex flex-column p-4 border-dashed">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">ENVÍO GRATIS</span>
                        <i class="fas fa-ticket-alt text-muted fs-4"></i>
                    </div>
                    <h4 class="fw-bold">Envíos gratis > 15€</h4>
                    <p class="text-muted small flex-grow-1">Válido para todos los pedidos superiores a 15€ en la zona centro.</p>
                    
                    <div class="d-flex align-items-center justify-content-between mt-3 bg-light p-2 rounded">
                        <code class="fw-bold text-dark fs-5 ps-2">ENVIOFREE</code>
                        <button class="btn btn-sm btn-outline-secondary btn-copiar" data-code="ENVIOFREE">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm coupon-card">
                <div class="card-body d-flex flex-column p-4 border-dashed">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-naranja bg-opacity-10 px-3 py-2 rounded-pill fw-bold">-20% DTO</span>
                        <i class="fas fa-percent text-muted fs-4"></i>
                    </div>
                    <h4 class="fw-bold">Descuento Bienvenida</h4>
                    <p class="text-muted small flex-grow-1">Solo para usuarios nuevos en su primer pedido.</p>
                    
                    <div class="d-flex align-items-center justify-content-between mt-3 bg-light p-2 rounded">
                        <code class="fw-bold text-dark fs-5 ps-2">HOLA20</code>
                        <button class="btn btn-sm btn-outline-secondary btn-copiar" data-code="HOLA20">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm coupon-card">
                <div class="card-body d-flex flex-column p-4 border-dashed">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold">2x1</span>
                        <i class="fas fa-hamburger text-muted fs-4"></i>
                    </div>
                    <h4 class="fw-bold">Jueves de 2x1</h4>
                    <p class="text-muted small flex-grow-1">Paga una Burger Fit y llévate otra gratis. Solo los jueves.</p>
                    
                    <div class="d-flex align-items-center justify-content-between mt-3 bg-light p-2 rounded">
                        <code class="fw-bold text-dark fs-5 ps-2">JUEVESFIT</code>
                        <button class="btn btn-sm btn-outline-secondary btn-copiar" data-code="JUEVESFIT">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src ="public/js/promociones.js"></script>