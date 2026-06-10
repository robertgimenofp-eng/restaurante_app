<div class="container py-5">
    <div class="row align-items-center">
        
        <div class="col-lg-5 mb-5 mb-lg-0">
            <h1 class="display-4 fw-bold mb-4">Hablemos 💬</h1>
            <p class="lead text-muted mb-4">¿Tienes alguna sugerencia sobre el menú? ¿Hubo un problema con tu pedido? Estamos aquí para escucharte.</p>
            
            <div class="d-flex align-items-center mb-3">
                <div class="bg-black text-white rounded-circle p-3 me-3">
                    <i class="fas fa-envelope fs-4"></i>
                </div>
                <div>
                    <strong class="d-block">Email</strong>
                    <span>hola@vivaeats.es</span>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="bg-viva text-white rounded-circle p-3 me-3">
                    <i class="fas fa-phone fs-4"></i>
                </div>
                <div>
                    <strong class="d-block">Teléfono</strong>
                    <span>900 123 456</span>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card card-viva p-5 bg-white">
                <form action="index.php?controller=Contacto&action=enviar" method="POST">
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" class="form-control" placeholder="Tu nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" placeholder="tu@email.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Asunto</label>
                        <select class="form-select">
                            <option>Consulta general</option>
                            <option>Problema con un pedido</option>
                            <option>Sugerencia</option>
                            <option>Trabaja con nosotros</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Mensaje</label>
                        <textarea class="form-control" rows="5" placeholder="Cuéntanos..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-viva-primary w-100 py-3 fw-bold fs-5">Enviar Mensaje</button>
                </form>
            </div>
        </div>

    </div>
</div>