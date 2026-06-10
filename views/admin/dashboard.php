<link rel="stylesheet" href="assets/css/admin.css">
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-2">
            <div class="list-group">
                <button id="btn-inicio" class="list-group-item list-group-item-action bg-primary text-white">
                    🏠 Inicio
                </button>
                <button id="btn-productos" class="list-group-item list-group-item-action">
                    🍔 Productos
                </button>
                <button id="btn-pedidos" class="list-group-item list-group-item-action">
                    📦 Pedidos
                </button>
                <button id="btn-logs" class="list-group-item list-group-item-action">
                    🛡️ Auditoría (Logs)
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
<script src="assets/js/api/ApiService.js"></script>
<script type="module" src="assets/js/admin/app.js"></script>