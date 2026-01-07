<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . page_url('acceso.php'));
    exit;
}

$usuario = trim((string)($_POST['usuario'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$errores = [];

if ($usuario === '' || $password === '') {
    $errores[] = 'Usuario y contraseña son obligatorios.';
}

if ($errores) {
    $_SESSION['form_errors'] = $errores;
    header('Location: ' . page_url('acceso.php'));
    exit;
}

try {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id_trabajador, correo_institucional, num_empleado, password_hash, nombres, apPat, apMat FROM trabajador WHERE correo_institucional = :usuario OR num_empleado = :usuario LIMIT 1');
    $stmt->execute(['usuario' => $usuario]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || !password_verify($password, (string)$row['password_hash'])) {
        $_SESSION['form_errors'] = ['Credenciales inválidas. Verifica tu correo/número y contraseña.'];
        header('Location: ' . page_url('acceso.php'));
        exit;
    }

    login_user([
        'id' => $row['id_trabajador'],
        'email' => $row['correo_institucional'],
        'rol' => 'trabajador',
        'nombre' => trim(($row['nombres'] ?? '') . ' ' . ($row['apPat'] ?? '') . ' ' . ($row['apMat'] ?? '')),
        'num_empleado' => $row['num_empleado'] ?? null,
    ]);

    flash('success', 'Bienvenido, acceso concedido.');
    header('Location: ' . page_url('panel.php'));
    exit;
} catch (Throwable $e) {
    $_SESSION['form_errors'] = ['Error al iniciar sesión: ' . $e->getMessage()];
    header('Location: ' . page_url('acceso.php'));
    exit;
}
