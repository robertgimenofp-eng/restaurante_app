export default class OrderManager {
    constructor() {
        this.container = document.getElementById('admin-content');
        this.targetCurrency = localStorage.getItem('adminTargetCurrency') || 'USD'; // Moneda desde localStorage o por defecto
        this.rates = {};             // Almacén de tasas de cambio
        this.pedidos = [];           // Almacén de pedidos
        this.filtros = {
            usuario: '',
            estado: '',
            fecha: '',
            ordenPrecio: '' // 'asc' o 'desc'
        };
    }

    async init() {
        this.container.textContent = ''; // Limpiar el contenedor
        const loadDiv = document.createElement('div');
        loadDiv.className = 'text-center p-5';
        
        const spinner = document.createElement('div');
        spinner.className = 'spinner-border text-primary';
        spinner.setAttribute('role', 'status');
        
        const loadMsg = document.createElement('p');
        loadMsg.className = 'mt-2 text-muted';
        loadMsg.textContent = 'Sincronizando pedidos y divisas internacionales...';
        
        loadDiv.appendChild(spinner);
        loadDiv.appendChild(loadMsg);
        this.container.appendChild(loadDiv);

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
            this.container.textContent = '';
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger';
            errorDiv.textContent = 'Error al conectar con los servidores.';
            this.container.appendChild(errorDiv);
        }
    }

    // --- 2. LÓGICA DE FILTRADO Y ORDENACIÓN ---
    filtrarYOrdenar() {
        // Uso de Filter sobre el array original (Requisito)
        let resultado = this.pedidos.filter(p => {
            const nombreUser = p.nombre_usuario || '';
            const matchUsuario = nombreUser.toLowerCase().includes(this.filtros.usuario.toLowerCase());
            const matchEstado = this.filtros.estado === '' || p.id_estado.toString() === this.filtros.estado;
            const matchFecha = this.filtros.fecha === '' || p.fecha === this.filtros.fecha;
            return matchUsuario && matchEstado && matchFecha;
        });

        // Uso de Sort (Requisito)
        if (this.filtros.ordenPrecio === 'asc') {
            resultado.sort((a, b) => parseFloat(a.total) - parseFloat(b.total));
        } else if (this.filtros.ordenPrecio === 'desc') {
            resultado.sort((a, b) => parseFloat(b.total) - parseFloat(a.total));
        }

        return resultado;
    }

    // --- 3. RENDERIZADO DE LA INTERFAZ ---
    render() {
        this.container.textContent = ''; // Limpiar contenedor sin usar innerHTML

        if (this.pedidos.length === 0) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-info';
            alertDiv.textContent = 'No hay pedidos registrados.';
            this.container.appendChild(alertDiv);
            return;
        }

        // Header and Currency selector
        const headerDiv = document.createElement('div');
        headerDiv.className = 'd-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3';
        
        const title = document.createElement('h2');
        title.className = 'mb-0';
        title.textContent = '📦 Gestión de Pedidos';
        headerDiv.appendChild(title);

        const currencyDiv = document.createElement('div');
        currencyDiv.className = 'd-flex align-items-center bg-white p-2 border rounded shadow-sm';
        
        const currencyLabel = document.createElement('label');
        currencyLabel.className = 'me-2 mb-0 small fw-bold text-muted text-uppercase';
        currencyLabel.textContent = 'Moneda:';
        currencyDiv.appendChild(currencyLabel);

        const currencySelect = document.createElement('select');
        currencySelect.id = 'currency-selector';
        currencySelect.className = 'form-select form-select-sm';
        currencySelect.style.width = 'auto';
        
        const optionsMoneda = [
            {val: 'USD', text: '🇺🇸 USD - Dólar'},
            {val: 'GBP', text: '🇬🇧 GBP - Libra'},
            {val: 'MXN', text: '🇲🇽 MXN - Peso'}
        ];
        
        optionsMoneda.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt.val;
            option.textContent = opt.text;
            if (this.targetCurrency === opt.val) option.selected = true;
            currencySelect.appendChild(option);
        });
        
        currencySelect.addEventListener('change', (e) => {
            this.targetCurrency = e.target.value;
            localStorage.setItem('adminTargetCurrency', this.targetCurrency); // Guardar en localStorage
            this.renderTableBody(); // Actualiza solo la tabla
        });
        currencyDiv.appendChild(currencySelect);

        const tasaActual = this.rates[this.targetCurrency];
        const rateInfo = document.createElement('div');
        rateInfo.className = 'ms-3 ps-3 border-start text-primary fw-bold';
        const rateSmall = document.createElement('small');
        rateSmall.id = 'rate-info-text';
        rateSmall.textContent = `1€ = ${tasaActual} ${this.targetCurrency}`;
        rateInfo.appendChild(rateSmall);
        currencyDiv.appendChild(rateInfo);

        headerDiv.appendChild(currencyDiv);
        this.container.appendChild(headerDiv);

        // Filters UI
        const filtersRow = document.createElement('div');
        filtersRow.className = 'row mb-4 bg-light p-3 rounded shadow-sm';

        // User Filter
        const colUser = document.createElement('div');
        colUser.className = 'col-md-3 mb-2 mb-md-0';
        const inputUser = document.createElement('input');
        inputUser.type = 'text';
        inputUser.className = 'form-control form-control-sm';
        inputUser.placeholder = 'Buscar por cliente...';
        inputUser.value = this.filtros.usuario;
        inputUser.addEventListener('input', (e) => {
            this.filtros.usuario = e.target.value;
            this.renderTableBody();
        });
        colUser.appendChild(inputUser);

        // Date Filter
        const colDate = document.createElement('div');
        colDate.className = 'col-md-3 mb-2 mb-md-0';
        const inputDate = document.createElement('input');
        inputDate.type = 'date';
        inputDate.className = 'form-control form-control-sm';
        inputDate.value = this.filtros.fecha;
        inputDate.addEventListener('change', (e) => {
            this.filtros.fecha = e.target.value;
            this.renderTableBody();
        });
        colDate.appendChild(inputDate);

        // Status Filter
        const colStatus = document.createElement('div');
        colStatus.className = 'col-md-3 mb-2 mb-md-0';
        const selectStatus = document.createElement('select');
        selectStatus.className = 'form-select form-select-sm';
        const optTodos = document.createElement('option');
        optTodos.value = '';
        optTodos.textContent = 'Todos los estados';
        selectStatus.appendChild(optTodos);
        const optEstados = [
            {val: '1', text: '📝 Confirmado'},
            {val: '2', text: '👨‍🍳 En Cocina'},
            {val: '3', text: '🛵 En Reparto'},
            {val: '4', text: '✅ Entregado'}
        ];
        optEstados.forEach(opt => {
            const o = document.createElement('option');
            o.value = opt.val;
            o.textContent = opt.text;
            if (this.filtros.estado === opt.val) o.selected = true;
            selectStatus.appendChild(o);
        });
        selectStatus.addEventListener('change', (e) => {
            this.filtros.estado = e.target.value;
            this.renderTableBody();
        });
        colStatus.appendChild(selectStatus);

        // Price Sort Filter
        const colPrice = document.createElement('div');
        colPrice.className = 'col-md-3';
        const selectPrice = document.createElement('select');
        selectPrice.className = 'form-select form-select-sm';
        const optNoSort = document.createElement('option');
        optNoSort.value = '';
        optNoSort.textContent = 'Orden por defecto';
        selectPrice.appendChild(optNoSort);
        const optAsc = document.createElement('option');
        optAsc.value = 'asc';
        optAsc.textContent = 'Menor a Mayor Precio';
        if (this.filtros.ordenPrecio === 'asc') optAsc.selected = true;
        selectPrice.appendChild(optAsc);
        const optDesc = document.createElement('option');
        optDesc.value = 'desc';
        optDesc.textContent = 'Mayor a Menor Precio';
        if (this.filtros.ordenPrecio === 'desc') optDesc.selected = true;
        selectPrice.appendChild(optDesc);
        selectPrice.addEventListener('change', (e) => {
            this.filtros.ordenPrecio = e.target.value;
            this.renderTableBody();
        });
        colPrice.appendChild(selectPrice);

        filtersRow.appendChild(colUser);
        filtersRow.appendChild(colDate);
        filtersRow.appendChild(colStatus);
        filtersRow.appendChild(colPrice);
        this.container.appendChild(filtersRow);

        // Table Base
        const tableResp = document.createElement('div');
        tableResp.className = 'table-responsive';
        
        const table = document.createElement('table');
        table.className = 'table table-hover shadow-sm bg-white align-middle';
        
        const thead = document.createElement('thead');
        thead.className = 'table-dark';
        const trHead = document.createElement('tr');
        ['#ID', 'Fecha', 'Cliente', 'Total (EUR)', 'Total (Divisa)', 'Estado Actual', 'Acciones'].forEach((thText, idx) => {
            const th = document.createElement('th');
            th.textContent = thText;
            if (idx === 4) {
                th.className = 'table-info text-dark text-center';
                th.id = 'th-divisa';
                th.textContent = `Total (${this.targetCurrency})`;
            }
            trHead.appendChild(th);
        });
        thead.appendChild(trHead);
        table.appendChild(thead);

        this.tbody = document.createElement('tbody');
        table.appendChild(this.tbody);
        
        tableResp.appendChild(table);
        this.container.appendChild(tableResp);

        this.renderTableBody();
    }

    renderTableBody() {
        this.tbody.textContent = ''; // Limpiar filas previas
        const pedidosFiltrados = this.filtrarYOrdenar();
        const tasaActual = this.rates[this.targetCurrency];
        const simbolo = this.getSimbolo(this.targetCurrency);

        // Actualizar header de tabla y de tasa
        const thDivisa = document.getElementById('th-divisa');
        if(thDivisa) thDivisa.textContent = `Total (${this.targetCurrency})`;
        const rateInfoText = document.getElementById('rate-info-text');
        if(rateInfoText) rateInfoText.textContent = `1€ = ${tasaActual} ${this.targetCurrency}`;

        if (pedidosFiltrados.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 7;
            td.className = 'text-center text-muted py-3';
            td.textContent = 'No se encontraron pedidos con los filtros actuales.';
            tr.appendChild(td);
            this.tbody.appendChild(tr);
            return;
        }

        pedidosFiltrados.forEach(p => {
            const tr = document.createElement('tr');

            // ID
            const tdId = document.createElement('td');
            const strongId = document.createElement('strong');
            strongId.textContent = `#${p.id_pedido}`;
            tdId.appendChild(strongId);
            tr.appendChild(tdId);

            // Fecha
            const tdFecha = document.createElement('td');
            tdFecha.textContent = p.fecha;
            tr.appendChild(tdFecha);

            // Cliente
            const tdCliente = document.createElement('td');
            const divNombre = document.createElement('div');
            divNombre.className = 'fw-bold';
            divNombre.textContent = p.nombre_usuario;
            const divDir = document.createElement('small');
            divDir.className = 'text-muted';
            divDir.textContent = p.direccion || 'Sin dirección';
            tdCliente.appendChild(divNombre);
            tdCliente.appendChild(divDir);
            tr.appendChild(tdCliente);

            // Total EUR
            const totalEUR = parseFloat(p.total);
            const tdTotal = document.createElement('td');
            tdTotal.className = 'fw-bold';
            tdTotal.textContent = `${totalEUR.toFixed(2)} €`;
            tr.appendChild(tdTotal);

            // Total Divisa
            const tdDivisa = document.createElement('td');
            tdDivisa.className = 'text-center fw-bold text-primary bg-light';
            const totalConvertido = (totalEUR * tasaActual).toFixed(2);
            tdDivisa.textContent = `${simbolo} ${totalConvertido}`;
            tr.appendChild(tdDivisa);

            // Estado
            const tdEstado = document.createElement('td');
            const selEstado = document.createElement('select');
            selEstado.className = 'form-select form-select-sm status-selector';
            selEstado.style.borderLeft = `5px solid ${this.getColorEstado(p.id_estado)}`;
            
            const optEstados = [
                {val: '1', text: '📝 Confirmado'},
                {val: '2', text: '👨‍🍳 En Cocina'},
                {val: '3', text: '🛵 En Reparto'},
                {val: '4', text: '✅ Entregado'}
            ];
            optEstados.forEach(opt => {
                const o = document.createElement('option');
                o.value = opt.val;
                o.textContent = opt.text;
                if (p.id_estado == opt.val) o.selected = true;
                selEstado.appendChild(o);
            });
            selEstado.addEventListener('change', (e) => {
                this.updateStatus(p.id_pedido, e.target.value);
            });
            tdEstado.appendChild(selEstado);
            tr.appendChild(tdEstado);

            // Acciones
            const tdAcciones = document.createElement('td');
            tdAcciones.className = 'd-flex gap-2 flex-wrap';

            const btnVer = document.createElement('button');
            btnVer.className = 'btn btn-sm btn-info text-white';
            btnVer.textContent = '👁️ Ver';
            btnVer.addEventListener('click', () => this.showDetails(p.id_pedido));

            const btnEliminar = document.createElement('button');
            btnEliminar.className = 'btn btn-sm btn-danger';
            btnEliminar.textContent = '🗑️ Borrar';
            btnEliminar.addEventListener('click', () => this.deleteOrder(p.id_pedido));

            tdAcciones.appendChild(btnVer);
            tdAcciones.appendChild(btnEliminar);
            tr.appendChild(tdAcciones);

            this.tbody.appendChild(tr);
        });
    }

    // --- 4. LÓGICA DE ACTUALIZACIÓN ---
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
                await this.loadData(); 
            }
        } catch (error) {
            console.error("Error al actualizar estado:", error);
        }
    }

    async deleteOrder(id) {
        const confirmar = await window.Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminará el pedido de forma permanente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (confirmar.isConfirmed) {
            try {
                const res = await fetch('index.php?controller=Pedido&action=apiEliminar', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ id_pedido: id })
                });
                const data = await res.json();

                if(data.status === 'success') {
                    window.Swal.fire('Eliminado!', 'El pedido ha sido eliminado.', 'success');
                    await this.loadData(); 
                } else {
                    window.Swal.fire('Error', data.message || 'No se pudo eliminar el pedido', 'error');
                }
            } catch (error) {
                console.error("Error al eliminar pedido:", error);
                window.Swal.fire('Error', 'Hubo un problema de conexión', 'error');
            }
        }
    }

    // --- 5. LÓGICA DE DETALLES ---
    async showDetails(id) {
        try {
            const res = await fetch(`index.php?controller=Pedido&action=apiDetalles&id=${id}`);
            const lineas = await res.json();

            const table = document.createElement('table');
            table.className = 'table table-sm text-start mt-3';
            
            const thead = document.createElement('thead');
            thead.className = 'table-light';
            const trHead = document.createElement('tr');
            ['Producto', 'Cant.', 'Precio'].forEach((text, idx) => {
                const th = document.createElement('th');
                th.textContent = text;
                if(idx === 1) th.className = 'text-center';
                if(idx === 2) th.className = 'text-end';
                trHead.appendChild(th);
            });
            thead.appendChild(trHead);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            lineas.forEach(linea => {
                const tr = document.createElement('tr');
                
                const td1 = document.createElement('td');
                td1.textContent = linea.nombre_producto;
                
                const td2 = document.createElement('td');
                td2.className = 'text-center';
                td2.textContent = `x${linea.cantidad}`;
                
                const td3 = document.createElement('td');
                td3.className = 'text-end';
                td3.textContent = `${parseFloat(linea.precio_unitario).toFixed(2)}€`;

                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);

            // Container for Swal html wrapper
            const container = document.createElement('div');
            container.appendChild(table);

            window.Swal.fire({
                title: `Detalles del Pedido #${id}`,
                html: container,
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