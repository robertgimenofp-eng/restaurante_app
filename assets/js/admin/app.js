// public/js/admin/app.js

import ProductManager from './modules/ProductManager.js';
import OrderManager from './modules/OrderManager.js';
import CurrencyManager from './modules/CurrencyManager.js';
import UserManager from './modules/UserManager.js';

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Seleccionamos los botones
    const btnProductos = document.getElementById('btn-productos');
    const btnPedidos = document.getElementById('btn-pedidos');
    const btnUsuarios = document.getElementById('btn-usuarios');
    

    // 2. Evento para Botón PRODUCTOS
    if (btnProductos) {
        btnProductos.addEventListener('click', (e) => {
            e.preventDefault();
            loadModule('productos');
        });
    }

    // 3. Evento para Botón PEDIDOS
    if (btnPedidos) {
        btnPedidos.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("¡CLICK DETECTADO EN PEDIDOS!"); 
            loadModule('pedidos');
        });
    }

    if (btnUsuarios) {
        btnUsuarios.addEventListener('click', (e) => {
            e.preventDefault();
            loadModule('usuarios');
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
            
        case 'usuarios':
            manager = new UserManager();
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

document.getElementById('btn-logs')?.addEventListener('click', () => {
    api.get('logs.php')
        .then(data => {
            const container = document.getElementById('admin-content');
            container.textContent = ''; // Limpiar

            const title = document.createElement('h3');
            title.className = 'mb-4';
            title.textContent = 'Auditoría de Sistema';
            container.appendChild(title);

            const table = document.createElement('table');
            table.className = 'table table-sm table-hover shadow-sm bg-white';

            const thead = document.createElement('thead');
            thead.className = 'table-dark';
            const trHead = document.createElement('tr');
            ['Fecha', 'Usuario', 'Acción', 'Entidad', 'Descripción'].forEach(text => {
                const th = document.createElement('th');
                th.textContent = text;
                trHead.appendChild(th);
            });
            thead.appendChild(trHead);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            
            data.forEach(log => {
                const tr = document.createElement('tr');

                const tdFecha = document.createElement('td');
                const smallFecha = document.createElement('small');
                smallFecha.textContent = log.fecha_hora;
                tdFecha.appendChild(smallFecha);
                tr.appendChild(tdFecha);

                const tdUser = document.createElement('td');
                const spanUser = document.createElement('span');
                spanUser.className = 'badge bg-light text-dark border';
                spanUser.textContent = log.nombre_usuario || 'Sistema';
                tdUser.appendChild(spanUser);
                tr.appendChild(tdUser);

                const tdAccion = document.createElement('td');
                const spanAccion = document.createElement('span');
                spanAccion.className = log.accion === 'DELETE' ? 'badge bg-danger' : 'badge bg-primary';
                spanAccion.textContent = log.accion;
                tdAccion.appendChild(spanAccion);
                tr.appendChild(tdAccion);

                const tdEntidad = document.createElement('td');
                const bEntidad = document.createElement('b');
                bEntidad.className = 'text-uppercase';
                bEntidad.textContent = log.entidad_afectada;
                tdEntidad.appendChild(bEntidad);
                tdEntidad.appendChild(document.createTextNode(` (ID: ${log.id_entidad})`));
                tr.appendChild(tdEntidad);

                const tdDesc = document.createElement('td');
                tdDesc.innerHTML = log.descripcion;
                tr.appendChild(tdDesc);

                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            container.appendChild(table);
        });
});

const btnInicio = document.getElementById('btn-inicio');

if (btnInicio) {
    btnInicio.addEventListener('click', () => loadModule('dashboard'));
}