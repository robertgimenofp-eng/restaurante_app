<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante | Inicio</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body>
<?php include 'layout/navbar.php'; ?>

<div class="contenido">
    <?php include $view; ?>
</div>

<?php include 'layout/footer.php'; ?>
</body>
</html>
