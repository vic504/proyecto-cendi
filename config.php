<?php
declare(strict_types=1);

// URL base del proyecto (ajustar si se despliega en un subdirectorio distinto)
if (!defined('BASE_URL')) {
    define('BASE_URL', '/proyecto-cendi');
}

// Configuración de base de datos (ajusta a tus credenciales locales)
if (!defined('DB_HOST')) {
    define('DB_HOST', '127.0.0.1');
}
if (!defined('DB_NAME')) {
    // Usa el esquema importado desde sql/inscripcion.sql
    define('DB_NAME', 'cendi_db');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}

/**
 * Devuelve la ruta absoluta (respecto al servidor web) para un recurso estático.
 */
function asset(string $path): string
{
    $clean = ltrim($path, '/');
    return rtrim(BASE_URL, '/') . '/' . $clean;
}

/**
 * Devuelve la ruta absoluta para una página dentro de /pages.
 */
function page_url(string $page): string
{
    $clean = ltrim($page, '/');
    return rtrim(BASE_URL, '/') . '/pages/' . $clean;
}
