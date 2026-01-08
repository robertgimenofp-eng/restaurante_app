export default class OrderManager {
    constructor() {
        this.container = document.getElementById('admin-content');
        this.targetCurrency = 'USD'; // Moneda inicial
        this.rates = {};             // Almacén de tasas de cambio
        this.pedidos = [];           // Almacén de pedidos
    }

    async init() {
        this.container.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Sincronizando pedidos y divisas internacionales...</p>
            </div>`;
        await this.loadData();
    }

    // --- 1. CARGAR DATOS (API Interna + API Externa) ---
    async loadData() {
        try {
            const [resPedidos, resMoneda] = await Promise.all([
                fetch('index.php?controller=Pedido&action=apiListar'),
                fetch('https://api.frankfurter.app/latest?from=EUR&to=USD,GBP,MXN')
            ]);

            this.pedidos = await resPedidos.json();
            const monedaData = await resMoneda.json();
            this.rates = monedaData.rates;

            this.render();
        } catch (error) {
            console.error("Error en carga de datos:", error);
            this.container.innerHTML = '<div class="alert alert-danger">Error al conectar con los servidores.</div>';
        }
    }

    // --- 2. RENDERIZADO DE LA INTERFAZ ---
    render() {
        if (this.pedidos.length === 0) {
            this.container.innerHTML = '<div class="alert alert-info">No hay pedidos registrados.</div>';
            return;
        }

        const tasaActual = this.rates[this.targetCurrency];
        const simbolo = this.getSimbolo(this.targetCurrency);

        let html = `
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                <h2 class="mb-0">📦 Gestión de Pedidos</h2>
                
                <div class="d-flex align-items-center bg-white p-2 border rounded shadow-sm">
                    <label class="me-2 mb-0 small fw-bold text-muted text-uppercase">Moneda:</label>
                    <select id="currency-selector" class="form-select form-select-sm" style="width: auto;">
                        <option value="USD" ${this.targetCurrency === 'USD' ? 'selected' : ''}>🇺🇸 USD - Dólar</option>
                        <option value="GBP" ${this.targetCurrency === 'GBP' ? 'selected' : ''}>🇬🇧 GBP - Libra</option>
                        <option value="MXN" ${this.targetCurrency === 'MXN' ? 'selected' : ''}>🇲🇽 MXN - Peso</option>
                    </select>
                    <div class="ms-3 ps-3 border-start text-primary fw-bold">
                        <small>1€ = ${tasaActual} ${this.targetCurrency}</small>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover shadow-sm bg-white align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total (EUR)</th>
                            <th class="table-info text-dark text-center">Total (${this.targetCurrency})</th>
                            <th>Estado Actual</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        this.pedidos.forEach(p => {
            const totalEUR = parseFloat(p.total);
            const totalConvertido = (totalEUR * tasaActual).toFixed(2);

            html += `
                <tr>
                    <td><strong>#${p.id_pedido}</strong></td>
                    <td>${p.fecha}</td>
                    <td>
                        <div class="fw-bold">${p.nombre_usuario}</div>
                        <small class="text-muted">${p.direccion || 'Sin dirección'}</small>
                    </td>
                    <td class="fw-bold">${totalEUR.toFixed(2)} €</td>
                    <td class="text-center fw-bold text-primary bg-light">
                        ${simbolo} ${totalConvertido}
                    </td>
                    <td>
                        <select class="form-select form-select-sm status-selector" 
                                data-id="${p.id_pedido}" 
                                style="border-left: 5px solid ${this.getColorEstado(p.id_estado)}">
                            <option value="1" ${p.id_estado == 1 ? 'selected' : ''}>📝 Confirmado</option>
                            <option value="2" ${p.id_estado == 2 ? 'selected' : ''}>👨‍🍳 En Cocina</option>
                            <option value="3" ${p.id_estado == 3 ? 'selected' : ''}>🛵 En Reparto</option>
                            <option value="4" ${p.id_estado == 4 ? 'selected' : ''}>✅ Entregado</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info text-white btn-detalles" data-id="${p.id_pedido}">
                            👁️ Ver Pedido
                        </button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
        this.container.innerHTML = html;

        this.attachEvents();
    }

    // --- 3. EVENTOS ---
    attachEvents() {
        // Selector de moneda
        document.getElementById('currency-selector').addEventListener('change', (e) => {
            this.targetCurrency = e.target.value;
            this.render(); // Repintamos solo con los datos que ya tenemos
        });

        // Cambiar estado
        document.querySelectorAll('.status-selector').forEach(sel => {
            sel.addEventListener('change', (e) => {
                this.updateStatus(e.target.dataset.id, e.target.value);
            });
        });

        // Ver detalles
        document.querySelectorAll('.btn-detalles').forEach(btn => {
            btn.addEventListener('click', () => {
                this.showDetails(btn.dataset.id);
            });
        });
    }

    // --- 4. LÓGICA DE ACTUALIZACIÓN (RESTAURADA) ---
    async updateStatus(id, idEstado) {
        try {
            const res = await fetch('index.php?controller=Pedido&action=apiCambiarEstado', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id_pedido: id, id_estado: idEstado })
            });
            const data = await res.json();

            if(data.status === 'success') {
                window.Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: 'Estado actualizado correctamente', showConfirmButton: false, timer: 2000
                });
                // Recargamos datos de la base de datos para refrescar la tabla
                await this.loadData(); 
            }
        } catch (error) {
            console.error("Error al actualizar estado:", error);
        }
    }

    // --- 5. LÓGICA DE DETALLES (RESTAURADA) ---
    async showDetails(id) {
        try {
            const res = await fetch(`index.php?controller=Pedido&action=apiDetalles&id=${id}`);
            const lineas = await res.json();

            let htmlDetalles = `
                <table class="table table-sm text-start mt-3">
                    <thead class="table-light">
                        <tr><th>Producto</th><th class="text-center">Cant.</th><th class="text-end">Precio</th></tr>
                    </thead>
                    <tbody>`;
            
            lineas.forEach(linea => {
                htmlDetalles += `
                    <tr>
                        <td>${linea.nombre_producto}</td>
                        <td class="text-center">x${linea.cantidad}</td>
                        <td class="text-end">${parseFloat(linea.precio_unitario).toFixed(2)}€</td>
                    </tr>`;
            });
            htmlDetalles += '</tbody></table>';

            window.Swal.fire({
                title: `Detalles del Pedido #${id}`,
                html: htmlDetalles,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#0dcaf0'
            });

        } catch (error) {
            console.error("Error al cargar detalles:", error);
        }
    }

    // --- UTILIDADES ---
    getSimbolo(moneda) {
        const simbolos = { 'USD': '$', 'GBP': '£', 'MXN': 'MX$' };
        return simbolos[moneda] || '$';
    }

    getColorEstado(id) {
        const colores = { 1: '#6c757d', 2: '#ffc107', 3: '#0dcaf0', 4: '#198754' };
        return colores[id] || '#ccc';
    }
}