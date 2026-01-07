<?php
$pageTitle = 'Mensaje';
include __DIR__ . '/../partials/head.php';

$preview = $_SESSION['solicitud_preview'] ?? null;

// Si ya hay éxito y no hay preview, redirige a acceso.
if (!$preview && flash_exists('success')) {
    header('Location: ' . page_url('acceso.php'));
    exit;
}
$success = flash('success');
$errors = flash('form_errors');
?>

<div class="container my-5">
    <?php if ($success): ?>
        <div class="alert alert-success" role="alert"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors) && is_array($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($preview): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">Resumen de inscripción</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h5>Niña/Niño</h5>
                        <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars($preview['nino_nombre'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><strong>CURP:</strong> <?= htmlspecialchars($preview['nino_curp'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><strong>Grupo asignado:</strong> <?= htmlspecialchars($preview['grupo_asignado'] ?? 'Pendiente', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><strong>CENDI:</strong> <?= htmlspecialchars($preview['cendi'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><strong>ID registro:</strong> <?= htmlspecialchars((string)($preview['nino_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Trabajadora/or</h5>
                        <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars($preview['trabajador_nombre'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><strong>CURP:</strong> <?= htmlspecialchars($preview['trabajador_curp'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><strong>Número de empleado:</strong> <?= htmlspecialchars($preview['num_empleado'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            No hay datos de inscripción para mostrar.
        </div>
    <?php endif; ?>

    <div class="d-flex gap-2">
        <a class="btn btn-primary" href="<?= page_url('inscripcion.php'); ?>">Nueva inscripción</a>
        <a class="btn btn-outline-secondary" href="<?= page_url('index.php'); ?>">Inicio</a>
    </div>
</div>

<?php
// Limpiar vista previa para no repetir en refresh
unset($_SESSION['solicitud_preview']);
include __DIR__ . '/../partials/footer.php';
?>