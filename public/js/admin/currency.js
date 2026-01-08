// Función para consultar la API externa
function actualizarTasasDeCambio() {
    const host = 'api.frankfurter.app';
    
    // Consultamos el cambio de Euro a USD y GBP
    fetch(`https://${host}/latest?from=EUR&to=USD,GBP`)
        .then(resp => resp.json())
        .then((data) => {
            // 1. Ocultar el cargador
            document.getElementById('rates-loading').classList.add('d-none');
            document.getElementById('rates-content').classList.remove('d-none');

            // 2. Insertar los datos de la API
            document.getElementById('rate-usd').innerText = `$ ${data.rates.USD}`;
            document.getElementById('rate-gbp').innerText = `£ ${data.rates.GBP}`;
            
            console.log("API Externa consumida con éxito:", data);
        })
        .catch(error => {
            console.error("Error en la API externa:", error);
            document.getElementById('rates-loading').innerText = "Error al cargar divisas";
        });
}

// Llamamos a la función al cargar el script
actualizarTasasDeCambio();