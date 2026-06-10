<link rel="stylesheet" href="assets/css/admin.css">
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-2" id="sidebar-wrapper" style="border-radius: 10px;">
            <div class="list-group mt-3">
                <button id="btn-inicio" class="list-group-item list-group-item-action bg-primary text-white" style="background-color: #ff4e00 !important; border-color: #ff4e00 !important;">
                    Admin Home
                </button>
                <button id="btn-productos" class="list-group-item list-group-item-action">
                    Gestion Productos
                </button>
                <button id="btn-pedidos" class="list-group-item list-group-item-action">
                    Gestion Pedidos
                </button>
                <button id="btn-usuarios" class="list-group-item list-group-item-action">
                    Gestion Usuarios
                </button>
                <button id="btn-logs" class="list-group-item list-group-item-action">
                    Logs
                </button>
            </div>
        </div>

        <div class="col-md-10">
            <div id="admin-content" class="p-4 border bg-white">
                <h3>Bienvenido al Panel</h3>
                <p>Selecciona una opción...</p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="assets/js/admin/app.js?v=2"></script>