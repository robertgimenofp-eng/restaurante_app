/**
 * Clase UserManager
 * -----------------
 * Gestiona el panel de administración de usuarios.
 * Se encarga de mostrar la lista de usuarios, filtrarlos,
 * y mostrar el formulario para crear o editar usuarios.
 * Utiliza la API para comunicarse con el backend (usuarios.php).
 */
export default class UserManager {
    constructor() {
        this.containerId = 'admin-content';
        this.usuarios = [];
    }

    init() {
        this.renderLayout();
        this.fetchUsuarios();
    }

    renderLayout() {
        const container = document.getElementById(this.containerId);
        container.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Gestión de Usuarios</h3>
                <div class="d-flex gap-2">
                    <input type="text" id="filter-usuarios" class="form-control" placeholder="Filtrar por nombre o email...">
                    <button class="btn btn-success text-nowrap" id="btn-create-user">Crear Usuario</button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover bg-white shadow-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usuarios-tbody">
                        <tr><td colspan="6" class="text-center py-4">Cargando usuarios...</td></tr>
                    </tbody>
                </table>
            </div>

            </div>
        `;

        document.getElementById('filter-usuarios').addEventListener('input', (e) => {
            this.filterTable(e.target.value);
        });

        document.getElementById('btn-create-user').addEventListener('click', () => {
            this.renderForm();
        });
    }

    fetchUsuarios() {
        api.get('usuarios.php')
            .then(data => {
                this.usuarios = data;
                this.renderTable(data);
            })
            .catch(error => {
                console.error("Error cargando usuarios:", error);
                document.getElementById('usuarios-tbody').innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">Error al cargar usuarios</td></tr>`;
            });
    }

    filterTable(term) {
        term = term.toLowerCase();
        const filtered = this.usuarios.filter(u =>
            u.nombre.toLowerCase().includes(term) ||
            u.email.toLowerCase().includes(term) ||
            u.rol.toLowerCase().includes(term)
        );
        this.renderTable(filtered);
    }

    renderTable(data) {
        const tbody = document.getElementById('usuarios-tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4">No hay usuarios.</td></tr>`;
            return;
        }

        data.forEach(u => {
            const tr = document.createElement('tr');

            let rolBadge = u.rol === 'admin'
                ? '<span class="badge bg-danger">Admin</span>'
                : '<span class="badge bg-secondary">Cliente</span>';

            tr.innerHTML = `
                <td><strong>#${u.id_usuario}</strong></td>
                <td>${u.nombre}</td>
                <td>${u.email}</td>
                <td>${u.telefono || '-'}</td>
                <td>${rolBadge}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${u.id_usuario}">Editar</button>
                    <button class="btn btn-sm btn-outline-danger btn-del" data-id="${u.id_usuario}">Borrar</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Eventos
        tbody.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => this.renderForm(e.target.dataset.id));
        });

        tbody.querySelectorAll('.btn-del').forEach(btn => {
            btn.addEventListener('click', (e) => this.deleteUser(e.target.dataset.id));
        });
    }

    renderForm(id = null) {
        let user = null;
        if (id) {
            user = this.usuarios.find(u => u.id_usuario == id);
            if (!user) return;
        }

        const titulo = user ? 'Editar Usuario' : 'Crear Usuario';
        const displayPassword = user ? 'none' : 'block';

        const container = document.getElementById(this.containerId);
        container.innerHTML = `
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">${titulo}</h3>
                </div>
                <div class="card-body">
                    <form id="form-user">
                        <input type="hidden" id="edit-id" value="${user ? user.id_usuario : ''}">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" id="edit-nombre" class="form-control" value="${user ? user.nombre : ''}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="edit-email" class="form-control" value="${user ? user.email : ''}" required>
                        </div>
                        <div class="mb-3" id="edit-password-container" style="display: ${displayPassword};">
                            <label class="form-label">Contraseña</label>
                            <input type="password" id="edit-password" class="form-control" ${!user ? 'required' : ''}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" id="edit-telefono" class="form-control" value="${user && user.telefono ? user.telefono : ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" id="edit-direccion" class="form-control" value="${user && user.direccion ? user.direccion : ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select id="edit-rol" class="form-select">
                                <option value="cliente" ${user && user.rol === 'cliente' ? 'selected' : ''}>Cliente</option>
                                <option value="admin" ${user && user.rol === 'admin' ? 'selected' : ''}>Admin</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-secondary me-2" id="btn-cancel-user">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="btn-save-user">Guardar</button>
                    </form>
                </div>
            </div>
        `;

        document.getElementById('btn-cancel-user').addEventListener('click', () => {
            this.init(); // Volver a pintar la tabla
        });

        document.getElementById('form-user').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveUser();
        });
    }

    saveUser() {
        const id = document.getElementById('edit-id').value;
        const payload = {
            action: id ? 'update' : 'create',
            id_usuario: id,
            nombre: document.getElementById('edit-nombre').value,
            email: document.getElementById('edit-email').value,
            telefono: document.getElementById('edit-telefono').value,
            direccion: document.getElementById('edit-direccion').value,
            rol: document.getElementById('edit-rol').value
        };

        if (!id) {
            payload.password = document.getElementById('edit-password').value;
        }

        api.post('usuarios.php', payload)
            .then(data => {
                if (data.success) {
                    Swal.fire('Guardado', 'Usuario guardado', 'success');
                    this.init();
                } else {
                    Swal.fire('Error', 'No se pudo guardar', 'error');
                }
            })
            .catch(error => console.error("Error guardando:", error));
    }

    deleteUser(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, borrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                api.post('usuarios.php', { action: 'delete', id_usuario: id })
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Borrado', 'Usuario eliminado', 'success');
                            this.fetchUsuarios();
                        } else {
                            Swal.fire('Error', 'No se pudo borrar', 'error');
                        }
                    });
            }
        });
    }
}
