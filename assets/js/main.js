/* public/js/main.js - VERSIÓN FINAL */

document.addEventListener("DOMContentLoaded", function() {
    
    // Referencias a los elementos del HTML
    const cookieBanner = document.getElementById('cookie-banner');
    const btnAceptar = document.getElementById('btn-aceptar-cookies');
    const btnRechazar = document.getElementById('btn-rechazar-cookies');

    // Comprobamos que los elementos existan para evitar errores
    if(cookieBanner && btnAceptar && btnRechazar){
        
        // 1. Leemos si ya existe la cookie guardada
        const yaAceptado = localStorage.getItem('vivaeats_cookies_accepted');

        // 2. Si NO está aceptado, mostramos el banner
        if (!yaAceptado) {
            // Un pequeño retraso para que la animación de entrada se vea suave
            setTimeout(() => {
                cookieBanner.classList.add('show');
            }, 500);
        }

        // 3. Botón ACEPTAR: Guarda en memoria y cierra
        btnAceptar.addEventListener('click', () => {
            localStorage.setItem('vivaeats_cookies_accepted', 'true');
            cookieBanner.classList.remove('show');
        });

        // 4. Botón RECHAZAR: Solo cierra (no guarda nada, volverá a salir al recargar)
        btnRechazar.addEventListener('click', () => {
            cookieBanner.classList.remove('show');
        });
    }
});