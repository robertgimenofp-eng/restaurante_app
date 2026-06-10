<?php


class MenuController {

    public function index() {
        require_once __DIR__ . '/../models/ProductoDAO.php';
        $productoModel = new ProductoDAO();
        $todos = $productoModel->getAll();

        // 1. Preparamos las cestas vacías
        $burgers = [];
        $wraps = [];
        $bowls = [];
        $snacks = [];
        $bebidas = [];
        $packs = []; // Aquí guardaremos la info del pack (precio, nombre)

        // 2. Clasificamos
        foreach($todos as $prod) {
            $cat = strtolower(trim($prod->getCategoria()));
            $nom = strtolower(trim($prod->getNombre()));

            // A) Packs (IDs 34 y 35 o categoría Menús)
           // Añadimos || $prod->getId_producto() == 36 para asegurar que pilla el Vegano
                if ($prod->getId_producto() == 34 || $prod->getId_producto() == 35 || $prod->getId_producto() == 36 || $cat == 'menús' || $cat == 'menus' || $cat == 'packs') {
                // Excluimos el menú de 12€ (ID 33) de esta lista
                if ($prod->getId_producto() != 33) {
                    $packs[$prod->getId_producto()] = $prod; // Usamos el ID como clave para buscarlo fácil luego
                }
                continue;
            }
            
            // B) Ignoramos el Menú Personalizado (ID 33)
            if ($prod->getId_producto() == 33) continue;

            // C) Ingredientes Individuales
            
            // -- Burgers --
            if (strpos($cat, 'burger') !== false || strpos($nom, 'burger') !== false) {
                $burgers[] = $prod;
            }
            // -- Wraps --
            elseif (strpos($cat, 'wrap') !== false || strpos($nom, 'wrap') !== false) {
                $wraps[] = $prod;
            }
            // -- Bowls --
            elseif (strpos($cat, 'bowl') !== false || strpos($nom, 'bowl') !== false) {
                $bowls[] = $prod;
            }
            // -- Snacks --
            elseif (strpos($cat, 'snack') !== false || strpos($cat, 'postre') !== false) {
                $snacks[] = $prod;
            }
            // -- Bebidas --
            elseif (strpos($cat, 'bebida') !== false || strpos($cat, 'refresco') !== false) {
                $bebidas[] = $prod;
            }
        }
        
        // Creamos una variable combinada para el Menú de 13,5€ (que usa todo junto)
        $principales = array_merge($burgers, $wraps, $bowls);

        $view = 'views/menus/index.php';
        require_once __DIR__ . '/../views/main.php';
    }
}