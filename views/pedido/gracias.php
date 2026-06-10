<div class="container-fluid d-flex justify-content-center align-items-center py-5" style="background-color: #f8f9fa; min-height: 80vh; font-family: 'Inter', sans-serif;">
    <div class="card border-0 p-5 shadow-sm" style="background-color: #ffffff; border-radius: 12px; max-width: 600px; width: 100%;">
        
        <div class="text-center mb-4">
            <!-- Icono Check -->
            <div class="d-inline-flex justify-content-center align-items-center rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px; background-color: #ff4e00;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#fff" class="bi bi-check-lg" viewBox="0 0 16 16">
                  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                </svg>
            </div>
            
            <h1 class="text-dark fw-bold mb-2" style="letter-spacing: 1px;">¡Pedido Realizado!</h1>
            <p class="text-muted fs-5">Tu pedido ha sido procesado correctamente.</p>
        </div>

        <div class="card mb-4 border-0 shadow-sm" style="background-color: #f8f9fa; border-radius: 8px;">
            <div class="card-body p-4 text-dark">
                <h4 class="fw-bold mb-4">Detalles del Pedido</h4>
                
                <p class="mb-3 fs-5"><span class="fw-bold text-dark">Número de pedido:</span> #<?= htmlspecialchars($pedido['id']) ?></p>
                <p class="mb-3 fs-5"><span class="fw-bold text-dark">Fecha:</span> <?= htmlspecialchars($pedido['fecha']) ?></p>
                <p class="mb-3 fs-5"><span class="fw-bold text-dark">Estado:</span> Pendiente</p>
                <p class="mb-0 fs-5"><span class="fw-bold text-dark">Total:</span> <?= number_format($pedido['total'], 2) ?> €</p>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="index.php" class="btn px-4 py-3 fw-bold border" style="background-color: #fff; color: #000; border-radius: 6px; letter-spacing: 1px;">
                VOLVER AL INICIO
            </a>
            <a href="index.php?controller=Menu&action=index" class="btn px-4 py-3 fw-bold text-white btn-viva-finalizar" style="background-color: #ff4e00; border-radius: 6px; letter-spacing: 1px;">
                VER PRODUCTOS
            </a>
        </div>
        
    </div>
</div>
