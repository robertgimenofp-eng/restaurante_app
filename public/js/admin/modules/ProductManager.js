// public/js/admin/modules/ProductManager.js

export default class ProductManager {
    constructor() {
        this.container = document.getElementById('admin-content');
    }

    async init() {
        this.container.innerHTML = '<div class="spinner-border text-primary"></div> Cargando...';
        await this.renderTable();
    }

    // --- 1. VISTA TABLA (Listar productos) ---
    async renderTable() {
        try {
            const response = await fetch('index.php?controller=Producto&action=apiListar');
            const productos = await response.json();

            let html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Productos</h2>
                    <button id="btn-crear" class="btn btn-primary">+ Nuevo Producto</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover shadow-sm bg-white">
                        <thead class="table-dark">
                            <tr><th>Img</th><th>Nombre</th><th>Precio</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
            `;

            productos.forEach(prod => {
                html += `
                    <tr>
                        <td>
                            <img src="public/img/productos/${prod.imagen_url}" 
                                onerror="this.src='public/img/productos/no-image.webp'" 
                                width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                        </td>
                        <td>${prod.nombre}</td>
                        <td>${prod.precio} €</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-editar" data-id="${prod.id_producto}">✏️</button>
                            <button class="btn btn-sm btn-danger btn-borrar" data-id="${prod.id_producto}">🗑️</button>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table></div>';
            this.container.innerHTML = html;

            this.attachTableEvents(); // Asignar clicks

        } catch (error) {
            console.error(error);
            this.container.innerHTML = '<div class="alert alert-danger">Error cargando datos</div>';
        }
    }

    // --- 2. GESTIÓN DE EVENTOS (Clicks en botones) ---
    attachTableEvents() {
        // Botón CREAR
        const btnCrear = document.getElementById('btn-crear');
        if (btnCrear) {
            btnCrear.addEventListener('click', () => this.renderForm()); // Formulario vacío
        }

        // Botones EDITAR
        const botonesEditar = document.querySelectorAll('.btn-editar');
        botonesEditar.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                this.editProduct(id); // Cargar datos y abrir form
            });
        });

        // Botones BORRAR
        const botonesBorrar = document.querySelectorAll('.btn-borrar');
        botonesBorrar.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                this.deleteProduct(id);
            });
        });
    }

    // --- 3. LOGICA EDITAR (Pedir datos a PHP) ---
    async editProduct(id) {
        try {
            const res = await fetch(`index.php?controller=Producto&action=apiObtener&id=${id}`);
            const producto = await res.json();
            
            if(producto) {
                this.renderForm(producto); // Abrir formulario CON datos
            } else {
                alert("Error: No se encontraron los datos.");
            }
        } catch (error) {
            console.error(error);
            alert("Error de conexión al cargar el producto");
        }
    }

    // --- 4. VISTA FORMULARIO (Sirve para Crear y Editar) ---
    renderForm(producto = null) {
        // Si hay producto es EDITAR, si no es NUEVO
        const titulo = producto ? `Editar: ${producto.nombre}` : 'Nuevo Producto';
        
        // Valores (si es nuevo están vacíos, si es editar tienen datos)
        const idVal = producto ? producto.id_producto : '';
        const nomVal = producto ? producto.nombre : '';
        const precVal = producto ? producto.precio : '';
        const descVal = producto ? producto.descripcion : '';
        const stockVal = producto ? producto.stock : '10';
        const catVal = producto ? producto.categoria : 'Fit Burgers';
        
        // Mostrar imagen antigua si existe
        let imgHtml = '';
        if(producto && producto.imagen_url) {
            imgHtml = `<div class="mt-2"><small>Actual:</small><br><img src="public/img/productos/${producto.imagen_url}" width="60"></div>`;
        }

        this.container.innerHTML = `
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">${titulo}</h3>
                </div>
                <div class="card-body">
                    <form id="form-producto">
                        <input type="hidden" name="id_producto" value="${idVal}">

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="${nomVal}" required>
                        </div>
                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control">${descVal}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Precio</label>
                                <input type="number" step="0.01" name="precio" class="form-control" value="${precVal}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Stock</label>
                                <input type="number" name="stock" class="form-control" value="${stockVal}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Categoría</label>
                            <select name="categoria" id="cat-select" class="form-select">
                                <option value="Fit Burgers">Fit Burgers</option>
                                <option value="Wraps & Bowls">Wraps & Bowls</option>
                                <option value="Snacks Saludables">Snacks Saludables</option>
                                <option value="Bebidas">Bebidas</option>
                                <option value="Menús">Menús</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Imagen</label>
                            <input type="file" name="imagen" class="form-control">
                            ${imgHtml}
                        </div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" id="btn-cancelar" class="btn btn-secondary">Cancelar</button>
                    </form>
                </div>
            </div>
        `;

        // Seleccionar la categoría correcta
        if(catVal) document.getElementById('cat-select').value = catVal;

        // Eventos del formulario
        document.getElementById('form-producto').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveProduct(new FormData(e.target));
        });

        document.getElementById('btn-cancelar').addEventListener('click', () => {
            this.renderTable();
        });
    }

    // --- 5. GUARDAR (Crear o Editar) ---
    async saveProduct(formData) {
        try {
            const res = await fetch('index.php?controller=Producto&action=save', {
                method: 'POST',
                body: formData
            });

            // Forzamos lectura segura
            // POR ESTO (Más seguro y acorde a tu PHP):
            const data = await res.json(); 

            if (data.status === 'success') {
                this.mostrarAlerta('¡Hecho!', 'Operación realizada correctamente', 'success');
                this.renderTable(); 
            } else {
                this.mostrarAlerta('Error', data.message || 'Error desconocido', 'error');
            }

        } catch (error) {
            console.error(error);
            this.mostrarAlerta('Error', 'Fallo de conexión', 'error');
        }
    }

    // --- 6. BORRAR ---
    async deleteProduct(id) {
        if (!confirm('¿Seguro que quieres borrar este producto?')) return;

        try {
            const res = await fetch(`index.php?controller=Producto&action=borrar&id=${id}`);
            const data = await res.json();
            
            if (data.status === 'success') {
                this.renderTable();
                this.mostrarAlerta('Borrado', 'Producto eliminado', 'success');
            } else {
                alert('Error al borrar');
            }
        } catch (error) {
            console.error(error);
            alert('Error de conexión');
        }
    }

    // --- UTILIDAD: ALERTAS ---
    mostrarAlerta(titulo, mensaje, tipo) {
        if (window.Swal) {
            window.Swal.fire({
                title: titulo,
                text: mensaje,
                icon: tipo,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            alert(`${titulo}: ${mensaje}`);
        }
    }
}