// public/js/admin/app.js

import ProductManager from './modules/ProductManager.js';
import OrderManager from './modules/OrderManager.js';
import CurrencyManager from './modules/CurrencyManager.js';

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Seleccionamos los botones
    const btnProductos = document.getElementById('btn-productos');
    const btnPedidos = document.getElementById('btn-pedidos');
    

    // 2. Evento para Botón PRODUCTOS
    if (btnProductos) {
        btnProductos.addEventListener('click', (e) => {
            e.preventDefault();
            loadModule('productos');
        });
    }

    // 3. Evento para Botón PEDIDOS (¡Esto es lo que te faltaba!)
    // ... dentro del addEventListener ...
    if (btnPedidos) {
        btnPedidos.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("¡CLICK DETECTADO EN PEDIDOS!"); // <--- AÑADE ESTO
            loadModule('pedidos');
        });
    }

    // 4. Cargar productos por defecto al entrar
    loadModule('dashboard');
    
    
});

// --- FUNCIÓN QUE CARGA EL MÓDULO ---
function loadModule(section) {
    console.log("Cambiando a sección:", section);
    
    let manager = null;

    switch(section) {
        case 'dashboard':
            manager = new CurrencyManager(); // Carga la API externa al inicio
            break;
        case 'productos':
            manager = new ProductManager();
            break;
            
        case 'pedidos':
            manager = new OrderManager();
            break;
            
        default:
            console.error("Sección desconocida:", section);
            return;
    }

    // Iniciamos el módulo elegido
    if (manager) {
        manager.init();
    }
}
// Añade esto a tu app.js o crea un AuditManager.js
document.getElementById('btn-logs')?.addEventListener('click', () => {
    fetch('index.php?controller=Admin&action=apiListarLogs')
        .then(res => res.json())
        .then(data => {
            let html = `
                <h3 class="mb-4">🛡️ Auditoría de Sistema</h3>
                <table class="table table-sm table-hover shadow-sm bg-white">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Entidad</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>`;
            
            data.forEach(log => {
                const badgeColor = log.accion === 'DELETE' ? 'bg-danger' : 'bg-primary';
                html += `
                    <tr>
                        <td><small>${log.fecha_hora}</small></td>
                        <td><span class="badge bg-light text-dark border">${log.nombre_usuario || 'Sistema'}</span></td>
                        <td><span class="badge ${badgeColor}">${log.accion}</span></td>
                        <td><b class="text-uppercase">${log.entidad_afectada}</b> (ID: ${log.id_entidad})</td>
                        <td>${log.descripcion}</td>
                    </tr>`;
            });

            html += `</tbody></table>`;
            document.getElementById('admin-content').innerHTML = html;
        });
});
// Dentro del DOMContentLoaded de app.js
const btnInicio = document.getElementById('btn-inicio');

if (btnInicio) {
    btnInicio.addEventListener('click', () => loadModule('dashboard'));
}