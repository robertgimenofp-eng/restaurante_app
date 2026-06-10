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
        const container = document.getElementById(this.containerId);
        container.textContent = ''; // Limpiar

        const row = document.createElement('div');
        row.className = 'row mb-4';

        const col = document.createElement('div');
        col.className = 'col-12';

        const card = document.createElement('div');
        card.className = 'card bg-light border-0 shadow-sm';

        const cardBody = document.createElement('div');
        cardBody.className = 'card-body d-flex justify-content-between align-items-center';

        const textDiv = document.createElement('div');
        
        const h4 = document.createElement('h4');
        h4.className = 'mb-0';
        h4.textContent = 'Dashboard de Control';
        textDiv.appendChild(h4);

        const p = document.createElement('p');
        p.className = 'text-muted mb-0';
        p.textContent = 'Bienvenido al sistema de gestión del restaurante.';
        textDiv.appendChild(p);
        
        cardBody.appendChild(textDiv);

        const widget = document.createElement('div');
        widget.id = 'currency-widget';
        widget.className = 'p-3 bg-white rounded shadow-sm border';
        widget.style.minWidth = '250px';

        const h6 = document.createElement('h6');
        h6.className = 'small text-uppercase fw-bold text-muted mb-2';
        h6.textContent = 'Cambio de Divisas (EUR)';
        widget.appendChild(h6);

        const ratesDisplay = document.createElement('div');
        ratesDisplay.id = 'rates-display';
        ratesDisplay.className = 'd-flex justify-content-around';
        
        const spinner = document.createElement('div');
        spinner.className = 'spinner-border spinner-border-sm text-primary';
        spinner.setAttribute('role', 'status');
        ratesDisplay.appendChild(spinner);
        
        widget.appendChild(ratesDisplay);
        cardBody.appendChild(widget);
        card.appendChild(cardBody);
        col.appendChild(card);
        row.appendChild(col);
        
        container.appendChild(row);

        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-info';
        alertDiv.textContent = 'Selecciona una sección en el menú lateral para gestionar Productos, Pedidos o revisar la Auditoría.';
        container.appendChild(alertDiv);
    }

    fetchRates() {
        api.get('https://api.frankfurter.app/latest?from=EUR&to=USD,GBP,MXN')
            .then(data => {
                const display = document.getElementById('rates-display');
                if (display) {
                    display.textContent = '';
                    
                    const spanUSD = document.createElement('span');
                    spanUSD.className = 'badge bg-soft-primary text-primary border';
                    spanUSD.textContent = `🇺🇸 USD: ${data.rates.USD}`;
                    display.appendChild(spanUSD);

                    const spanGBP = document.createElement('span');
                    spanGBP.className = 'badge bg-soft-success text-success border ms-2';
                    spanGBP.textContent = `🇬🇧 GBP: ${data.rates.GBP}`;
                    display.appendChild(spanGBP);

                    const spanMXN = document.createElement('span');
                    spanMXN.className = 'badge bg-soft-warning text-warning border ms-2';
                    spanMXN.textContent = `🇲🇽 MXN: ${data.rates.MXN}`;
                    display.appendChild(spanMXN);
                }
            })
            .catch(err => {
                console.error("Error API:", err);
                const display = document.getElementById('rates-display');
                if(display) {
                    display.textContent = '';
                }
            });
    }
}
