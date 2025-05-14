<?php
// Cargar los datos de los servicios desde el JSON
$datos = file_get_contents('data/datos.json');
$servicios = json_decode($datos, true);
?>

<div class="servicios-lista">
    <?php foreach ($servicios as $servicio): ?>
        <div class="servicio">
            <h2><?= htmlspecialchars($servicio['servicio']) ?></h2>
            <p><?= htmlspecialchars($servicio['descripcion']) ?></p>
            <div class="planes">
                <?php foreach ($servicio['planes'] as $plan): ?>
                    <div class="plan">
                        <h3><?= htmlspecialchars($plan['nombre']) ?></h3>
                        <p class="precio">Precio: $<?= htmlspecialchars($plan['precio']) ?> <?= htmlspecialchars($plan['moneda']) ?></p>
                        <strong>Incluye:</strong>
                        <ul>
                            <?php foreach ($plan['incluye'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <strong>Requisitos:</strong>
                        <ul>
                            <?php foreach ($plan['requisitos'] as $req): ?>
                                <li><?= htmlspecialchars($req) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <form action="form.html" method="get">
                            <input type="hidden" name="servicio" value="<?= htmlspecialchars($servicio['servicio']) ?>">
                            <input type="hidden" name="plan" value="<?= htmlspecialchars($plan['nombre']) ?>">
                            <button type="submit" class="btn-solicitar">Solicitar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
