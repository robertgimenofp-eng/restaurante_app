<?php
// OJO: Ahora cargamos el modelo Oferta
require_once 'models/Oferta.php';

class PromocionesController {
    
    public function index() {
        $view = 'views/promociones/index.php';
        require_once 'views/main.php';
    }

    public function validar() {
        $data = json_decode(file_get_contents('php://input'), true);
        $codigo = isset($data['codigo']) ? $data['codigo'] : '';

        // Usamos el nuevo modelo Oferta
        $oferta = Oferta::buscarPorCodigo($codigo);

        if ($oferta) {
            // Â¡ENCONTRADO Y VÃLIDO!
            
            // Guardamos en sesiÃ³n usando tus nombres de columna
            $_SESSION['descuento_activo'] = [
                'codigo' => $oferta->getCodigo_opcional(), // Tu columna de la BBDD
                'valor' => $oferta->valor,            // Tu columna de la BBDD
                'tipo'  => $oferta->tipo              // Tu columna (por si es % o â‚¬ fijos)
            ];
            // Formateamos el mensaje segÃºn el valor
            // Nota: asumo que 'valor' es un porcentaje, ej: 20
            echo json_encode([
                'success' => true, 
                'mensaje' => "Â¡CÃ³digo {$oferta->getCodigo_opcional()} aplicado! Tienes un {$oferta->valor}% de descuento."
            ]);
        } else {
            // NO EXISTE O CADUCÃ“
            echo json_encode([
                'success' => false, 
                'mensaje' => 'El cÃ³digo no existe o ha caducado.'
            ]);
        }
    }  // ... aquÃ­ tendrÃ¡s seguramente tu funciÃ³n 'validar' ...

    public function quitar() {
        // 1. Comprobamos si existe el descuento y lo borramos
        if(isset($_SESSION['descuento_activo'])){
            unset($_SESSION['descuento_activo']);
        }

        // 2. Redirigimos de vuelta al checkout/carrito
        // Esto harÃ¡ que la pÃ¡gina se recargue y se recalculen los precios sin el descuento
        header("Location: index.php?controller=Carrito&action=checkout");
        exit();
    }
}
?>