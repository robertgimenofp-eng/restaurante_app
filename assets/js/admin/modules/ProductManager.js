export default class ProductManager {
    constructor() {
        this.container = document.getElementById('admin-content');
    }

    async init() {
        this.container.textContent = '';
        const spinner = document.createElement('div');
        spinner.className = 'spinner-border text-primary';
        this.container.appendChild(spinner);
        this.container.appendChild(document.createTextNode(' Cargando...'));
        await this.renderTable();
    }

    // --- 1. VISTA TABLA (Listar productos) ---
    async renderTable() {
        try {
            const productos = await api.get('productos.php');
            this.container.textContent = '';

            const headerDiv = document.createElement('div');
            headerDiv.className = 'd-flex justify-content-between align-items-center mb-4';
            
            const h2 = document.createElement('h2');
            h2.textContent = 'Gestión de Productos';
            headerDiv.appendChild(h2);

            const btnCrear = document.createElement('button');
            btnCrear.id = 'btn-crear';
            btnCrear.className = 'btn btn-success text-nowrap';
            btnCrear.textContent = 'Crear Producto';
            headerDiv.appendChild(btnCrear);
            this.container.appendChild(headerDiv);

            const tableResp = document.createElement('div');
            tableResp.className = 'table-responsive';

            const table = document.createElement('table');
            table.className = 'table table-hover shadow-sm bg-white';

            const thead = document.createElement('thead');
            thead.className = 'table-dark';
            const trHead = document.createElement('tr');
            ['Img', 'Nombre', 'Precio', 'Acciones'].forEach(txt => {
                const th = document.createElement('th');
                th.textContent = txt;
                if (txt === 'Acciones') th.className = 'text-end';
                trHead.appendChild(th);
            });
            thead.appendChild(trHead);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            productos.forEach(prod => {
                const tr = document.createElement('tr');

                const tdImg = document.createElement('td');
                const img = document.createElement('img');
                img.src = `assets/img/productos/${prod.imagen_url}`;
                img.onerror = function() { this.src = 'assets/img/productos/no-image.webp'; };
                img.width = 50;
                img.height = 50;
                img.style.objectFit = 'cover';
                img.style.borderRadius = '5px';
                tdImg.appendChild(img);
                tr.appendChild(tdImg);

                const tdNombre = document.createElement('td');
                tdNombre.textContent = prod.nombre;
                tr.appendChild(tdNombre);

                const tdPrecio = document.createElement('td');
                tdPrecio.textContent = `${prod.precio} €`;
                tr.appendChild(tdPrecio);

                const tdAcciones = document.createElement('td');
                tdAcciones.className = 'text-end';
                const btnEditar = document.createElement('button');
                btnEditar.className = 'btn btn-sm btn-outline-primary btn-editar me-1';
                btnEditar.dataset.id = prod.id_producto;
                btnEditar.textContent = 'Editar';
                tdAcciones.appendChild(btnEditar);

                const btnBorrar = document.createElement('button');
                btnBorrar.className = 'btn btn-sm btn-outline-danger btn-borrar';
                btnBorrar.dataset.id = prod.id_producto;
                btnBorrar.textContent = 'Borrar';
                tdAcciones.appendChild(btnBorrar);
                tr.appendChild(tdAcciones);

                tbody.appendChild(tr);
            });
            table.appendChild(tbody);
            tableResp.appendChild(table);
            this.container.appendChild(tableResp);

            this.attachTableEvents();

        } catch (error) {
            console.error(error);
            this.container.textContent = '';
            const errDiv = document.createElement('div');
            errDiv.className = 'alert alert-danger';
            errDiv.textContent = 'Error cargando datos';
            this.container.appendChild(errDiv);
        }
    }

    // --- 2. GESTIÓN DE EVENTOS (Clicks en botones) ---
    attachTableEvents() {
        const btnCrear = document.getElementById('btn-crear');
        if (btnCrear) {
            btnCrear.addEventListener('click', () => this.renderForm());
        }

        const botonesEditar = document.querySelectorAll('.btn-editar');
        botonesEditar.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                this.editProduct(id);
            });
        });

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
            const producto = await api.get(`productos.php?id=${id}`);
            if(producto) {
                this.renderForm(producto);
            } else {
                alert("Error: No se encontraron los datos.");
            }
        } catch (error) {
            console.error(error);
            alert("Error de conexión al cargar el producto");
        }
    }

    // --- 4. VISTA FORMULARIO ---
    renderForm(producto = null) {
        this.container.textContent = '';
        const titulo = producto ? `Editar: ${producto.nombre}` : 'Nuevo Producto';
        
        const idVal = producto ? producto.id_producto : '';
        const nomVal = producto ? producto.nombre : '';
        const precVal = producto ? producto.precio : '';
        const descVal = producto ? producto.descripcion : '';
        const stockVal = producto ? producto.stock : '10';
        const catVal = producto ? producto.categoria : 'Fit Burgers';

        const card = document.createElement('div');
        card.className = 'card shadow-sm';

        const cardHeader = document.createElement('div');
        cardHeader.className = 'card-header bg-primary text-white';
        const h3 = document.createElement('h3');
        h3.className = 'card-title mb-0';
        h3.textContent = titulo;
        cardHeader.appendChild(h3);
        card.appendChild(cardHeader);

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body';
        
        const form = document.createElement('form');
        form.id = 'form-producto';

        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id_producto';
        inputId.value = idVal;
        form.appendChild(inputId);

        // Nombre
        const mb1 = document.createElement('div');
        mb1.className = 'mb-3';
        const lblNom = document.createElement('label');
        lblNom.textContent = 'Nombre';
        const inpNom = document.createElement('input');
        inpNom.type = 'text';
        inpNom.name = 'nombre';
        inpNom.className = 'form-control';
        inpNom.value = nomVal;
        inpNom.required = true;
        mb1.appendChild(lblNom);
        mb1.appendChild(inpNom);
        form.appendChild(mb1);

        // Descripcion
        const mb2 = document.createElement('div');
        mb2.className = 'mb-3';
        const lblDesc = document.createElement('label');
        lblDesc.textContent = 'Descripción';
        const txtDesc = document.createElement('textarea');
        txtDesc.name = 'descripcion';
        txtDesc.className = 'form-control';
        txtDesc.textContent = descVal;
        mb2.appendChild(lblDesc);
        mb2.appendChild(txtDesc);
        form.appendChild(mb2);

        // Row Precio y Stock
        const row = document.createElement('div');
        row.className = 'row';

        const col1 = document.createElement('div');
        col1.className = 'col-md-6 mb-3';
        const lblPrec = document.createElement('label');
        lblPrec.textContent = 'Precio';
        const inpPrec = document.createElement('input');
        inpPrec.type = 'number';
        inpPrec.step = '0.01';
        inpPrec.name = 'precio';
        inpPrec.className = 'form-control';
        inpPrec.value = precVal;
        inpPrec.required = true;
        col1.appendChild(lblPrec);
        col1.appendChild(inpPrec);
        row.appendChild(col1);

        const col2 = document.createElement('div');
        col2.className = 'col-md-6 mb-3';
        const lblStock = document.createElement('label');
        lblStock.textContent = 'Stock';
        const inpStock = document.createElement('input');
        inpStock.type = 'number';
        inpStock.name = 'stock';
        inpStock.className = 'form-control';
        inpStock.value = stockVal;
        col2.appendChild(lblStock);
        col2.appendChild(inpStock);
        row.appendChild(col2);
        
        form.appendChild(row);

        // Categoria
        const mb3 = document.createElement('div');
        mb3.className = 'mb-3';
        const lblCat = document.createElement('label');
        lblCat.textContent = 'Categoría';
        const selCat = document.createElement('select');
        selCat.name = 'categoria';
        selCat.id = 'cat-select';
        selCat.className = 'form-select';
        ['Fit Burgers', 'Wraps & Bowls', 'Snacks Saludables', 'Bebidas', 'Menús'].forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat;
            opt.textContent = cat;
            if(cat === catVal) opt.selected = true;
            selCat.appendChild(opt);
        });
        mb3.appendChild(lblCat);
        mb3.appendChild(selCat);
        form.appendChild(mb3);

        // Imagen
        const mb4 = document.createElement('div');
        mb4.className = 'mb-3';
        const lblImg = document.createElement('label');
        lblImg.textContent = 'Imagen';
        const inpImg = document.createElement('input');
        inpImg.type = 'file';
        inpImg.name = 'imagen';
        inpImg.className = 'form-control';
        mb4.appendChild(lblImg);
        mb4.appendChild(inpImg);

        if(producto && producto.imagen_url) {
            const dImg = document.createElement('div');
            dImg.className = 'mt-2';
            const sImg = document.createElement('small');
            sImg.textContent = 'Actual:';
            dImg.appendChild(sImg);
            dImg.appendChild(document.createElement('br'));
            const pImg = document.createElement('img');
            pImg.src = `assets/img/productos/${producto.imagen_url}`;
            pImg.width = 60;
            dImg.appendChild(pImg);
            mb4.appendChild(dImg);
        }
        form.appendChild(mb4);

        const btnSave = document.createElement('button');
        btnSave.type = 'submit';
        btnSave.className = 'btn btn-success me-2';
        btnSave.textContent = 'Guardar';
        form.appendChild(btnSave);

        const btnCancel = document.createElement('button');
        btnCancel.type = 'button';
        btnCancel.id = 'btn-cancelar';
        btnCancel.className = 'btn btn-secondary';
        btnCancel.textContent = 'Cancelar';
        form.appendChild(btnCancel);

        cardBody.appendChild(form);
        card.appendChild(cardBody);
        this.container.appendChild(card);

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveProduct(new FormData(e.target));
        });

        btnCancel.addEventListener('click', () => {
            this.renderTable();
        });
    }

    // --- 5. GUARDAR (Crear o Editar) ---
    async saveProduct(formData) {
        try {
            const data = await api.post('productos.php?action=save', formData);
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
            const data = await api.get(`productos.php?action=borrar&id=${id}`);
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