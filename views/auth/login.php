

<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <?php if(isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="index.php?controller=Auth&action=login" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required class="form-control">
        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="contraseña" id="password" required class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>
<p class="mt-3 text-center">
    ¿Eres nuevo?
    <a href="index.php?controller=Auth&action=showRegister">Crea tu cuenta aquí</a>
</p>