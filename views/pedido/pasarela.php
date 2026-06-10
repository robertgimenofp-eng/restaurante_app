<div class="container py-5" style="max-width: 900px;">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-size: 2.5rem; color: #000;">Pasarela de Pago 🔒</h2>
        <p class="text-muted">Estás a un paso de disfrutar tu pedido</p>
    </div>

    <form action="index.php?controller=Pedido&action=hacer" method="POST">
        <div class="row g-4">
            <!-- Columna Izquierda: Datos del usuario -->
            <div class="col-md-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Datos de Envío</h4>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre" value="<?= $_SESSION['identity']->getNombre() ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" value="<?= $_SESSION['identity']->getEmail() ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dirección de Entrega</label>
                            <input type="text" class="form-control" name="direccion" value="<?= $_SESSION['identity']->getDireccion() ?? '' ?>" placeholder="Calle, Número, Piso..." required>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Método de Pago</h4>
                        
                        <!-- Opciones de pago -->
                        <div class="d-flex flex-column gap-3">
                            <label class="border rounded p-3 d-flex align-items-center cursor-pointer payment-method">
                                <input class="form-check-input me-3 mt-0" type="radio" name="metodo_pago" value="visa" checked>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" height="20" alt="Visa" class="me-3">
                                <span class="fw-bold">Tarjeta de Crédito / Débito</span>
                            </label>

                            <label class="border rounded p-3 d-flex align-items-center cursor-pointer payment-method">
                                <input class="form-check-input me-3 mt-0" type="radio" name="metodo_pago" value="applepay">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b0/Apple_Pay_logo.svg" height="20" alt="Apple Pay" class="me-3">
                                <span class="fw-bold">Apple Pay</span>
                            </label>

                            <label class="border rounded p-3 d-flex align-items-center cursor-pointer payment-method">
                                <input class="form-check-input me-3 mt-0" type="radio" name="metodo_pago" value="paypal">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" height="20" alt="PayPal" class="me-3">
                                <span class="fw-bold">PayPal</span>
                            </label>

                            <label class="border rounded p-3 d-flex align-items-center cursor-pointer payment-method">
                                <input class="form-check-input me-3 mt-0" type="radio" name="metodo_pago" value="bizum">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/af/Bizum.svg" height="20" alt="Bizum" class="me-3">
                                <span class="fw-bold">Bizum</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen -->
            <div class="col-md-5">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-body p-4 bg-light rounded">
                        <h4 class="fw-bold mb-4 text-center">Resumen del Pedido</h4>
                        
                        <div class="d-flex justify-content-between mb-2 text-muted fw-bold">
                            <span>Subtotal</span>
                            <span><?= number_format($subtotal_productos, 2) ?>€</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2 text-muted fw-bold">
                            <span>Gastos de envío</span>
                            <span><?= number_format($gastos_envio, 2) ?>€</span>
                        </div>
                        
                        <?php if($descuento_total > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success fw-bold border-bottom pb-3">
                            <span>Descuento aplicado</span>
                            <span>- <?= number_format($descuento_total, 2) ?>€</span>
                        </div>
                        <?php else: ?>
                        <div class="border-bottom mb-3"></div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between mt-3 mb-4">
                            <span class="fw-bold fs-3 text-dark">Total</span>
                            <span class="fw-bold fs-3 text-dark"><?= number_format($total_pedido, 2) ?>€</span>
                        </div>

                        <button type="submit" class="btn btn-viva-finalizar w-100 py-3 fw-bold fs-5 shadow-sm text-white" style="background-color: #ff4e00; border:none; border-radius: 8px;">
                            Finalizar Pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.payment-method:hover {
    background-color: #f8f9fa;
    border-color: #ff4e00 !important;
}
.payment-method input:checked {
    background-color: #ff4e00;
    border-color: #ff4e00;
}
.payment-method:has(input:checked) {
    border-color: #ff4e00 !important;
    background-color: #fffaf7;
}
</style>
