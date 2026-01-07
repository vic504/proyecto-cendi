<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function login_user(array $user): void
{
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'rol' => $user['rol'],
        'nombre' => $user['nombre'] ?? null,
        'num_empleado' => $user['num_empleado'] ?? null,
    ];
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function require_login(string $role = null): void
{
    $user = current_user();
    if (!$user) {
        header('Location: ' . page_url($role === 'admin' ? 'admin.php' : 'acceso.php'));
        exit;
    }
    if ($role && $user['rol'] !== $role) {
        header('Location: ' . page_url('acceso.php'));
        exit;
    }
}

function flash(string $key, $value = null)
{
    if ($value === null) {
        if (!isset($_SESSION['flash'][$key])) return null;
        $val = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $val;
    }
    $_SESSION['flash'][$key] = $value;
}
