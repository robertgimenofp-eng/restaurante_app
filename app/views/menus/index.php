<div class="container py-5">
    
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Menús y Packs</h1>
        <p class="text-muted lead">Crea tu menú perfecto o elige un pack para compartir.</p>
    </div>

    <div class="card shadow mb-5 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-5 bg-naranja text-white p-4 d-flex flex-column justify-content-center align-items-center text-center">
                <h2 class="fw-bold">Menú Personalizado</h2>
                <h1 class="display-3 fw-bold my-3">13.50€</h1>
                <p class="fs-5">1 Principal + 1 Snack + 1 Bebida</p>
            </div>

            <div class="col-md-7 p-4 bg-white">
                <h4 class="fw-bold mb-4">Elige tus ingredientes:</h4>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">1. PLATO PRINCIPAL</label>
                    <select id="select-principal" class="form-select">
                        <option value="" selected disabled>Selecciona una opción...</option>
                        <?php foreach($principales as $p): ?>
                            <option value="<?= $p->id_producto ?>"><?= $p->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">2. SNACK</label>
                    <select id="select-snack" class="form-select">
                        <option value="" selected disabled>Selecciona una opción...</option>
                        <?php foreach($snacks as $s): ?>
                            <option value="<?= $s->id_producto ?>"><?= $s->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">3. BEBIDA</label>
                    <select id="select-bebida" class="form-select">
                        <option value="" selected disabled>Selecciona una opción...</option>
                        <?php foreach($bebidas as $b): ?>
                            <option value="<?= $b->id_producto ?>"><?= $b->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="button" 
                        id="btn-add-menu" 
                        class="btn btn-dark w-100 py-3 fw-bold mt-2"
                        data-url="index.php?controller=Carrito&action=addMenuCompleto">
                    AÑADIR MENÚ AL CARRITO 🛒
                </button>
                
                <div id="mensaje-menu" class="mt-2 text-center fw-bold small" style="display:none;"></div>
            </div>
        </div>
    </div>
    <h3 class="fw-bold mb-4 pt-4 border-top">Packs Ahorro</h3>
    
    <div class="row row-cols-1 row-cols-md-3 g-4">
        
        <?php if(isset($packs[34])): $p = $packs[34]; ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0"> <div class="card-body d-flex flex-column">
                    <h4 class="card-title fw-bold" style="color: #FF9D00;"><?= $p->nombre ?></h4>
                    <span class="badge bg-vg-orange fs-5 mb-3"><?= $p->precio ?> €</span>
                    <ul class="small text-muted mb-4">
                        <li>🍔 Spicy Grill + Fit Pollo</li>
                        <li>🌯 Chicken Protein Wrap</li>
                        <li>🥗 Power Green Bowl</li>
                        <li>🍫 Yogur Granola + Barritas</li>
                        <li>🥤 <b>2 Bebidas a elegir</b></li>
                    </ul>
                    <button type="button" class="btn btn-vg-orange w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#modalAmigos">
                        Elegir Bebidas y Añadir
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($packs[35])): $p = $packs[35]; ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <h4 class="card-title fw-bold" style="color: #5DADE2;"><?= $p->nombre ?></h4>
                    <span class="badge bg-vg-blue fs-5 mb-3"><?= $p->precio ?> €</span>
                    <ul class="small text-muted mb-4">
                        <li>🍔 3 Burgers Variadas</li>
                        <li>🌯 2 Wraps Variados</li>
                        <li>🥗 2 Bowls Variados</li>
                        <li>🍪 4 Snacks Variados</li>
                        <li>🥤 <b>4 Bebidas a elegir</b></li>
                    </ul>
                    <button type="button" class="btn btn-vg-blue w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#modalFamiliar">
                        Elegir Bebidas y Añadir
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($packs[36])): $p = $packs[36]; ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <h4 class="card-title fw-bold" style="color: #82E0AA;"><?= $p->nombre ?></h4>
                    <span class="badge bg-vg-green fs-5 mb-3"><?= $p->precio ?> €</span>
                    <ul class="small text-muted mb-4">
                        <li>🍔 Vegan Crunch Burger</li>
                        <li>🌯 Vegan Energy Wrap</li>
                        <li>🍫 Barra Proteica Vegana</li>
                        <li>💧 Agua (Incluida)</li>
                    </ul>
                    
                    <button type="button" 
                        class="btn btn-vg-green w-100 mt-auto btn-add-simple-pack"
                        data-url="index.php?controller=Carrito&action=add&id=<?= $p->id_producto ?>"> 
                        Añadir <?= $p->nombre ?> 🛒
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

<div class="modal fade" id="modalAmigos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header fw-bold bg-vg-orange text-white justify-content-between">
            <h5 class="modal-title fs-6">Pack Amigos: Elige 2 Bebidas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      <div class="modal-body">
        <form id="form-amigos">
            <input type="hidden" name="id_pack" value="34">
            
            <label class="form-label">Bebida 1</label>
            <select name="bebida1" class="form-select mb-3">
                <option value="">-- Elige --</option>
                <?php foreach($bebidas as $b) echo "<option value='$b->id_producto'>$b->nombre</option>"; ?>
            </select>

            <label class="form-label">Bebida 2</label>
            <select name="bebida2" class="form-select mb-3">
                <option value="">-- Elige --</option>
                <?php foreach($bebidas as $b) echo "<option value='$b->id_producto'>$b->nombre</option>"; ?>
            </select>
            
            <div id="msg-amigos" class="text-center fw-bold small mb-2" style="display:none;"></div>
            
            <button type="button" class="btn btn-dark w-100 btn-add-complex-pack" 
                data-form="form-amigos" 
                data-msg="msg-amigos"
                data-url="index.php?controller=Carrito&action=addPackComplejo">
                AÑADIR AL CARRITO
            </button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalFamiliar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header fw-bold bg-vg-blue text-white justify-content-between">
            <h5 class="modal-title fs-6">Pack Familiar: Elige 4 Bebidas</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form id="form-familiar">
            <input type="hidden" name="id_pack" value="35">
            
            <?php for($i=1; $i<=4; $i++): ?>
                <label class="form-label">Bebida <?=$i?></label>
                <select name="bebida<?=$i?>" class="form-select mb-2">
                    <option value="">-- Elige --</option>
                    <?php foreach($bebidas as $b) echo "<option value='$b->id_producto'>$b->nombre</option>"; ?>
                </select>
            <?php endfor; ?>
            
            <div id="msg-familiar" class="text-center fw-bold small mb-2 mt-3" style="display:none;"></div>
            
            <button type="button" class="btn btn-dark w-100 btn-add-complex-pack" 
                data-form="form-familiar" 
                data-msg="msg-familiar"
                data-url="index.php?controller=Carrito&action=addPackComplejo">
                AÑADIR AL CARRITO
            </button>
        </form>
      </div>
    </div>
  </div>
</div>