<?php
// OJO: Ahora cargamos el modelo Oferta
require_once __DIR__ . '/../models/Oferta.php';

class PromocionesController {
    
    public function index() {
        $view = 'views/promociones/index.php';
        require_once __DIR__ . '/../views/main.php';
    }

    public function validar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $codigo = isset($data['codigo']) ? $data['codigo'] : '';

        require_once __DIR__ . '/../models/OfertaDAO.php';
        $ofertaDao = new OfertaDAO();
        $oferta = $ofertaDao->buscarPorCodigo($codigo);

        if ($oferta) {
            // ¡ENCONTRADO Y VÁLIDO!
            
            // Guardamos en sesión usando tus nombres de columna
            $_SESSION['descuento_activo'] = [
                'codigo' => $oferta->getCodigo_opcional(),
                'valor' => $oferta->getDescuento_porcentaje(),
                'tipo'  => 'porcentaje'
            ];
            // Formateamos el mensaje según el valor
            // Nota: asumo que 'valor' es un porcentaje, ej: 20
            echo json_encode([
                'success' => true, 
                'mensaje' => "¡Código {$oferta->getCodigo_opcional()} aplicado! Tienes un {$oferta->getDescuento_porcentaje()}% de descuento."
            ]);
        } else {
            // NO EXISTE O CADUCÓ
            echo json_encode([
                'success' => false, 
                'mensaje' => 'El código no existe o ha caducado.'
            ]);
        }
    }  // ... aquí tendrás seguramente tu función 'validar' ...

    public function quitar() {
        // 1. Comprobamos si existe el descuento y lo borramos
        if(isset($_SESSION['descuento_activo'])){
            unset($_SESSION['descuento_activo']);
        }

        // 2. Redirigimos de vuelta al checkout/carrito
        // Esto hará que la página se recargue y se recalculen los precios sin el descuento
        header("Location: index.php?controller=Carrito&action=checkout");
        exit();
    }
}
?>