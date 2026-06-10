

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4" style="color:#6799ab;">Iniciar Sesión</h2>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) >= 1): ?>
                        <div class="alert alert-info text-center shadow-sm">
                            🛒 Tienes productos en la cesta.<br>
                            <strong>Inicia sesión para finalizar tu pedido.</strong>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?controller=Auth&action=login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" id="password" class="form-control" required>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary fw-bold" style="background:#6799ab; border:none;">
                                ENTRAR
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p>¿Eres nuevo? <a href="index.php?controller=Auth&action=showRegister" style="color:#6799ab; font-weight:bold;">Crea tu cuenta aquí</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>