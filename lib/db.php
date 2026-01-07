<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        // Si la BD no existe, intente crearla y reintente la conexión.
        if ((int) $e->getCode() === 1049) {
            create_database_if_missing();
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } else {
            throw $e;
        }
    }

    return $pdo;
}

function create_database_if_missing(): void
{
    $dsn = 'mysql:host=' . DB_HOST . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $dbName = str_replace('`', '', DB_NAME);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $dbName . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
}

/**
 * Crea tablas mínimas si no existen.
 */
function ensure_base_tables(): void
{
    $pdo = db();

    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        rol ENUM('trabajador','admin') NOT NULL DEFAULT 'trabajador',
        num_empleado VARCHAR(50) DEFAULT NULL,
        nombre VARCHAR(150) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS solicitudes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app VARCHAR(100),
        apm VARCHAR(100),
        nombres VARCHAR(150),
        curp_nino VARCHAR(18),
        fecha_nacimiento DATE,
        edad_meses SMALLINT,
        grupo_asignado VARCHAR(50),
        lugar_nacimiento VARCHAR(100),
        grupo_sanguineo VARCHAR(3),
        rh_factor VARCHAR(10),
        contacto VARCHAR(20),
        domicilio_calle VARCHAR(150),
        domicilio_numero VARCHAR(20),
        entidad VARCHAR(100),
        municipio VARCHAR(100),
        cp VARCHAR(10),
        cendi VARCHAR(100),
        appt VARCHAR(100),
        apmt VARCHAR(100),
        nombret VARCHAR(150),
        curp_trab VARCHAR(18),
        fecha_trab DATE,
        correo_inst VARCHAR(150),
        correo_per VARCHAR(150),
        ocupacion VARCHAR(50),
        num_empleado VARCHAR(50),
        escolaridad VARCHAR(50),
        tipo_institucion VARCHAR(50),
        adscripcion VARCHAR(150),
        horario VARCHAR(100),
        estado_civil VARCHAR(30),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}

/**
 * Inserta catálogos básicos en cendi_db si no existen.
 */
function seed_catalogs(): void
{
    $pdo = db();

    seed_simple_catalog($pdo, 'cendi', 'cendi', [
        'CENDI "Amalia Solorzano de Cardenas"',
        'CENDI "Clementina Batalla de Bassols"',
        'CENDI "Eva Samano de Lopez Mateos"',
        'CENDI "Laura Perez de Batiz"',
        'CENDI "Margarita Salazar de Erro"',
    ]);

    seed_simple_catalog($pdo, 'grupo', 'grupo', [
        'Lactantes',
        'Maternal A',
        'Maternal B',
        'Preescolar',
    ]);

    seed_simple_catalog($pdo, 'grupo_sang', 'grupo_sang', ['O', 'A', 'B', 'AB']);
    seed_simple_catalog($pdo, 'rh', 'rh', ['+', '-']);

    seed_simple_catalog($pdo, 'ocupacion', 'ocupacion', ['Docente', 'PAAE']);

    seed_simple_catalog($pdo, 'escolaridad', 'escolaridad', [
        'Sin escolaridad', 'Preescolar', 'Primaria', 'Secundaria', 'Bachillerato', 'Tecnico',
        'Tecnico Superior Universitario', 'Licenciatura', 'Especialidad', 'Maestria', 'Doctorado', 'Posdoctorado'
    ]);

    seed_simple_catalog($pdo, 'edo_civil', 'edo_civil', ['Casado', 'Soltero', 'Union Libre']);

    seed_simple_catalog($pdo, 'tipo_adscripcion', 'tipo_adscripcion', [
        'Nivel Medio Superior',
        'Nivel Superior',
        'Centro de Investigacion',
        'CENDI y Centros de Apoyo'
    ]);

    seed_municipios_cdmx($pdo);
}

function seed_simple_catalog(PDO $pdo, string $table, string $column, array $values): void
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = :val");
    $insert = $pdo->prepare("INSERT INTO {$table} ({$column}) VALUES (:val)");

    foreach ($values as $val) {
        $stmt->execute(['val' => $val]);
        if ((int) $stmt->fetchColumn() === 0) {
            $insert->execute(['val' => $val]);
        }
    }
}

function get_or_create_id(PDO $pdo, string $table, string $idColumn, string $valueColumn, string $value, array $extra = []): int
{
    $sel = $pdo->prepare("SELECT {$idColumn} FROM {$table} WHERE {$valueColumn} = :val LIMIT 1");
    $sel->execute(['val' => $value]);
    $existing = $sel->fetchColumn();
    if ($existing) {
        return (int) $existing;
    }

    $columns = [$valueColumn];
    $placeholders = [':val'];
    $params = ['val' => $value];
    foreach ($extra as $col => $val) {
        $columns[] = $col;
        $placeholders[] = ':' . $col;
        $params[$col] = $val;
    }

    $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
    $ins = $pdo->prepare($sql);
    $ins->execute($params);
    return (int) $pdo->lastInsertId();
}

function get_or_create_municipio(PDO $pdo, string $nombre, int $entidadId): int
{
    $nombre = trim($nombre);
    if ($nombre === '') {
        return 0;
    }
    $sel = $pdo->prepare('SELECT id_municipio FROM municipio WHERE municipio = :mun AND id_entidad = :ent LIMIT 1');
    $sel->execute(['mun' => $nombre, 'ent' => $entidadId]);
    $id = $sel->fetchColumn();
    if ($id) {
        return (int)$id;
    }
    $ins = $pdo->prepare('INSERT INTO municipio (municipio, id_entidad) VALUES (:mun, :ent)');
    $ins->execute(['mun' => $nombre, 'ent' => $entidadId]);
    return (int)$pdo->lastInsertId();
}

function seed_municipios_cdmx(PDO $pdo): void
{
    $cdmxId = get_or_create_id($pdo, 'entidad_federativa', 'id_entidad', 'entidad', 'Ciudad de México', []);
    $alcaldias = [
        'Álvaro Obregón','Azcapotzalco','Benito Juárez','Coyoacán','Cuajimalpa de Morelos',
        'Cuauhtémoc','Gustavo A. Madero','Iztacalco','Iztapalapa','La Magdalena Contreras',
        'Miguel Hidalgo','Milpa Alta','Tláhuac','Tlalpan','Venustiano Carranza','Xochimilco'
    ];

    foreach ($alcaldias as $mun) {
        get_or_create_municipio($pdo, $mun, $cdmxId);
    }
}
