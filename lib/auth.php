<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session.php';

function seed_default_users(): void
{
    $pdo = db();
    $count = (int)$pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $stmt = $pdo->prepare('INSERT INTO usuarios (email, password_hash, rol, num_empleado, nombre) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute(['admin@ipn.mx', password_hash('Admin@123', PASSWORD_DEFAULT), 'admin', null, 'Administrador']);
    $stmt->execute(['trabajador@ipn.mx', password_hash('Trabajador@123', PASSWORD_DEFAULT), 'trabajador', '12345', 'Trabajador CENDI']);
}

function find_user_by_identifier(string $identifier): ?array
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :id OR num_empleado = :id LIMIT 1');
    $stmt->execute([':id' => $identifier]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function authenticate(string $identifier, string $password, string $role = null): ?array
{
    $user = find_user_by_identifier($identifier);
    if (!$user) {
        return null;
    }
    if ($role && $user['rol'] !== $role) {
        return null;
    }
    if (!password_verify($password, $user['password_hash'])) {
        return null;
    }
    return $user;
}
