

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4" style="color:#6799ab;">Crear Cuenta Gourmet</h2>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="index.php?controller=Auth&action=register" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="contraseña" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="number" name="telefono" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" placeholder="Para enviarte la comida...">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold" style="background:#6799ab; border:none;">
                                REGISTRARME
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>¿Ya tienes cuenta? <a href="index.php?controller=Auth&action=showLogin">Inicia sesión aquí</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>