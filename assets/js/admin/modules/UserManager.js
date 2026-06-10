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

            <!-- Modal Editar -->
            <div class="modal fade" id="modalEditUser" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form-edit-user">
                                <input type="hidden" id="edit-id">
                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" id="edit-nombre" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" id="edit-email" class="form-control" required>
                                </div>
                                <div class="mb-3" id="edit-password-container">
                                    <label class="form-label">Contraseña (Solo al crear)</label>
                                    <input type="password" id="edit-password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" id="edit-telefono" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" id="edit-direccion" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Rol</label>
                                    <select id="edit-rol" class="form-select">
                                        <option value="cliente">Cliente</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="btn-save-user">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('filter-usuarios').addEventListener('input', (e) => {
            this.filterTable(e.target.value);
        });

        document.getElementById('btn-create-user').addEventListener('click', () => {
            this.openCreateModal();
        });

        document.getElementById('btn-save-user').addEventListener('click', () => {
            this.saveUser();
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
            btn.addEventListener('click', (e) => this.openEditModal(e.target.dataset.id));
        });

        tbody.querySelectorAll('.btn-del').forEach(btn => {
            btn.addEventListener('click', (e) => this.deleteUser(e.target.dataset.id));
        });
    }

    openCreateModal() {
        document.getElementById('form-edit-user').reset();
        document.getElementById('edit-id').value = '';
        document.querySelector('#modalEditUser .modal-title').textContent = 'Crear Usuario';
        document.getElementById('edit-password-container').style.display = 'block';
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditUser'));
        modal.show();
    }

    openEditModal(id) {
        const user = this.usuarios.find(u => u.id_usuario == id);
        if (!user) return;

        document.querySelector('#modalEditUser .modal-title').textContent = 'Editar Usuario';
        document.getElementById('edit-password-container').style.display = 'none';

        document.getElementById('edit-id').value = user.id_usuario;
        document.getElementById('edit-nombre').value = user.nombre;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-telefono').value = user.telefono || '';
        document.getElementById('edit-direccion').value = user.direccion || '';
        document.getElementById('edit-rol').value = user.rol;

        const modal = new bootstrap.Modal(document.getElementById('modalEditUser'));
        modal.show();
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
                    bootstrap.Modal.getInstance(document.getElementById('modalEditUser')).hide();
                    this.fetchUsuarios();
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
