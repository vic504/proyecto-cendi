<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/session.php';

require_login('trabajador');

$user = current_user();
$pdo = db();

function cendi_catalogo(): array {
    return [
        'CENDI "Amalia Solorzano de Cardenas"' => ['direccion' => 'Dirección no disponible', 'telefono' => ''],
        'CENDI "Clementina Batalla de Bassols"' => ['direccion' => 'Dirección no disponible', 'telefono' => ''],
        'CENDI "Eva Samano de Lopez Mateos"' => ['direccion' => 'Dirección no disponible', 'telefono' => ''],
        'CENDI "Laura Perez de Batiz"' => ['direccion' => 'Dirección no disponible', 'telefono' => ''],
        'CENDI "Margarita Salazar de Erro"' => ['direccion' => 'Dirección no disponible', 'telefono' => ''],
    ];
}

$child = null;
try {
    $stmt = $pdo->prepare('SELECT n.id_nino, n.curp, n.nombres, n.apPat, n.apMat, n.fecha_nac, n.id_cendi, n.id_grupo, g.grupo, c.cendi
        FROM nino n
        LEFT JOIN grupo g ON g.id_grupo = n.id_grupo
        LEFT JOIN cendi c ON c.id_cendi = n.id_cendi
        WHERE n.id_trabajador = :id
        ORDER BY n.id_nino DESC
        LIMIT 1');
    $stmt->execute(['id' => $user['id']]);
    $child = $stmt->fetch();
} catch (Throwable $e) {
    $child = null;
}

$cendiInfo = null;
if ($child && $child['cendi']) {
    $catalogo = cendi_catalogo();
    $cendiInfo = $catalogo[$child['cendi']] ?? ['direccion' => 'Dirección no disponible', 'telefono' => ''];
}

$pageTitle = 'Panel';
include __DIR__ . '/../partials/head.php';
$success = flash('success');
$errors = flash('form_errors');
if ($errors === null) {
    $errors = $_SESSION['form_errors'] ?? [];
    unset($_SESSION['form_errors']);
}
?>
<div class="container my-5">
    <h1 class="mb-4 text-center" id="titulo-form">Bienvenido, <?= htmlspecialchars($user['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($child): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold">Datos del registro</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Hija/o:</strong> <?= htmlspecialchars($child['nombres'] . ' ' . $child['apPat'] . ' ' . $child['apMat'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><strong>CURP:</strong> <?= htmlspecialchars($child['curp'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="mb-1"><strong>Fecha de nacimiento:</strong> <?= htmlspecialchars($child['fecha_nac'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>CENDI asignado:</strong> <span class="badge bg-primary"><?= htmlspecialchars($child['cendi'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                        <p class="mb-1"><strong>Grupo:</strong> <span class="badge bg-success"><?= htmlspecialchars($child['grupo'] ?? 'Pendiente', ENT_QUOTES, 'UTF-8'); ?></span></p>
                        <p class="mb-1"><strong>Dirección del CENDI:</strong> <?= htmlspecialchars($cendiInfo['direccion'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-3">
            <a class="btn btn-primary" href="<?= asset('actions/acuse.php'); ?>" target="_blank">Generar Acuse</a>
            <a class="btn btn-outline-danger" href="<?= asset('actions/logout.php'); ?>">Cerrar sesión</a>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No encontramos registros asociados a tu cuenta.</div>
        <a class="btn btn-secondary" href="<?= page_url('inscripcion.php'); ?>">Registrar</a>
        <a class="btn btn-outline-danger" href="<?= asset('actions/logout.php'); ?>">Cerrar sesión</a>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
