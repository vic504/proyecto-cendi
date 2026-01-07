<?php
$pageTitle = 'Confirmar inscripción';
include __DIR__ . '/../partials/head.php';

$formData = $_SESSION['form_data'] ?? null;
$preview = $_SESSION['solicitud_preview'] ?? null;

if (!$formData) {
    header('Location: ' . page_url('inscripcion.php'));
    exit;
}

?>
<div class="container my-5">
    <h1 class="mb-4 text-center" id="titulo-form">Revisa tu registro</h1>

    <div class="alert alert-info">Verifica que la información sea correcta antes de enviar.</div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Datos de la niña o el niño</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars(($preview['nino_nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>CURP:</strong> <?= htmlspecialchars(($formData['CURPNINO'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>Fecha de nacimiento:</strong> <?= htmlspecialchars(($formData['FECHANNINO'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>Grupo asignado:</strong> <?= htmlspecialchars(($preview['grupo_asignado'] ?? 'Pendiente'), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>CENDI de adscripción:</strong> <?= htmlspecialchars(($preview['cendi'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Datos del/de la trabajadora</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars(($preview['trabajador_nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>CURP:</strong> <?= htmlspecialchars(($preview['trabajador_curp'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>Número de empleado:</strong> <?= htmlspecialchars(($preview['num_empleado'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>Horario laboral:</strong> <?= htmlspecialchars(($preview['horario'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>Correo institucional:</strong> <?= htmlspecialchars(($formData['CIT'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="mb-1"><strong>Correo personal:</strong> <?= htmlspecialchars(($formData['CPT'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-6 d-grid">
            <form method="POST" action="<?= asset('actions/inscripcion_guardar.php'); ?>">
                <input type="hidden" name="confirmar" value="1">
                <button class="btn btn-success btn-lg" type="submit">Enviar y guardar</button>
            </form>
        </div>
        <div class="col-md-6 d-grid">
            <a class="btn btn-secondary btn-lg" href="<?= page_url('inscripcion.php?return=1'); ?>">Modificar datos</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
