/**
 * Clase ApiService
 * ----------------
 * Patrón Singleton que centraliza todas las peticiones HTTP (Fetch API) del frontend.
 * Abstrae la lógica de enviar peticiones GET, POST, y POST con FormData (imágenes),
 * manejando automáticamente los encabezados JSON y los errores de red.
 */
class ApiService {
    constructor(baseUrl = 'api/') {
        this.baseUrl = baseUrl;
    }

    // GET genérico con parámetros opcionales
    async get(endpoint, params = {}) {
        // Ignorar baseUrl si la URL es absoluta (ej. frankfurter API)
        let url = endpoint.startsWith('http') ? endpoint : this.baseUrl + endpoint;

        const queryParams = Object.entries(params)
            .filter(([, value]) => value !== undefined && value !== null)
            .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
            .join('&');

        if (queryParams) {
            url += (url.includes('?') ? '&' : '?') + queryParams;
        }

        const response = await fetch(url);
        return response.json();
    }

    // POST genérico
    async post(endpoint, data) {
        let url = endpoint.startsWith('http') ? endpoint : this.baseUrl + endpoint;
        
        let options = {
            method: 'POST'
        };

        // Si es FormData (ej. imágenes o formularios nativos), el navegador pone los headers
        if (data instanceof FormData) {
            options.body = data;
        } else {
            // Si es objeto, lo enviamos como JSON
            options.headers = { 'Content-Type': 'application/json' };
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);
        return response.json();
    }

    // PUT genérico
    async put(endpoint, data) {
        let url = endpoint.startsWith('http') ? endpoint : this.baseUrl + endpoint;
        const response = await fetch(url, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        return response.json();
    }

    // DELETE genérico
    async delete(endpoint, data = null) {
        let url = endpoint.startsWith('http') ? endpoint : this.baseUrl + endpoint;
        let options = {
            method: 'DELETE'
        };

        if (data) {
            options.headers = { 'Content-Type': 'application/json' };
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);
        return response.json();
    }
}

// Instancia global para que todos los módulos la usen
const api = new ApiService();
