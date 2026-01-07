<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/db.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = db();
    seed_catalogs();
    $row = $pdo->query('SELECT 1 AS ok')->fetch();
    if (!$row || (int) $row['ok'] !== 1) {
        http_response_code(500);
        exit('Fallo en consulta sencilla');
    }

    echo "OK: conexion y consulta exitosa\n";
    echo "BD: " . DB_NAME . "\n";

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    if (!$tables) {
        echo "\n(no hay tablas en la BD)\n";
        exit;
    }

    foreach ($tables as $table) {
        echo "\n-- " . $table . " --\n";
        $count = (int) $pdo->query('SELECT COUNT(*) FROM `' . $table . '`')->fetchColumn();
        echo 'total filas: ' . $count . "\n";

        if ($count === 0) {
            continue;
        }

        $stmt = $pdo->query('SELECT * FROM `' . $table . '` LIMIT 5');
        $rows = $stmt->fetchAll();
        $i = 1;
        foreach ($rows as $r) {
            echo '# ' . $i . ': ';
            $pairs = [];
            foreach ($r as $k => $v) {
                $pairs[] = $k . '=' . (is_null($v) ? 'NULL' : (string) $v);
            }
            echo implode(' | ', $pairs) . "\n";
            $i++;
        }
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error de conexion o consulta: ' . $e->getMessage();
}
