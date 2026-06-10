// public/js/admin/modules/CurrencyManager.js

export default class CurrencyManager {
    constructor() {
        this.containerId = 'admin-content';
    }

    init() {
        this.renderLayout();
        this.fetchRates();
    }

    renderLayout() {
        const html = `
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light border-0 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">📊 Dashboard de Control</h4>
                                <p class="text-muted mb-0">Bienvenido al sistema de gestión del restaurante.</p>
                            </div>
                            <div id="currency-widget" class="p-3 bg-white rounded shadow-sm border" style="min-width: 250px;">
                                <h6 class="small text-uppercase fw-bold text-muted mb-2">Cambio de Divisas (EUR)</h6>
                                <div id="rates-display" class="d-flex justify-content-around">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-info">
                Selecciona una sección en el menú lateral para gestionar Productos, Pedidos o revisar la Auditoría.
            </div>
        `;
        document.getElementById(this.containerId).innerHTML = html;
    }

    fetchRates() {
        api.get('https://api.frankfurter.app/latest?from=EUR&to=USD,GBP,MXN')
            .then(data => {
                const display = document.getElementById('rates-display');
                if (display) {
                    display.innerHTML = `
                        <span class="badge bg-soft-primary text-primary border">🇺🇸 USD: ${data.rates.USD}</span>
                        <span class="badge bg-soft-success text-success border ms-2">🇬🇧 GBP: ${data.rates.GBP}</span>
                        <span class="badge bg-soft-warning text-warning border ms-2">🇲🇽 MXN: ${data.rates.MXN}</span>
                    `;
                }
            })
            .catch(err => {
                console.error("Error API:", err);
                document.getElementById('rates-display').innerHTML = '<small class="text-danger">Error al cargar divisas</small>';
            });
    }
}
