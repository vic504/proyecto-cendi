<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . page_url('inscripcion.php'));
    exit;
}

$form = $_POST;
$isPreview = isset($form['preview']);
$isConfirm = isset($form['confirmar']);

// Si es confirmación, recupera datos previos de sesión para evitar tampering
if ($isConfirm && isset($_SESSION['form_data'])) {
    $form = $_SESSION['form_data'];
}

$_SESSION['form_data'] = $form;
$errors = [];
$horaEntrada = null;
$horaSalida = null;


$required = [
    'APP','APM','NOMBRES','CURPNINO','FECHANNINO','CONTACTONINO','CPNINO','CENDININO',
    'APPT','APMT','NOMBREST','CURPT','FECHAT','CIT','CPT','trab_ocupacion','NumE','escolaridad_select',
    'tipo_institucion','HORA_ENTRADA','HORA_SALIDA','EC','password','password_confirm'
];

foreach ($required as $field) {
    if (empty(trim((string)($form[$field] ?? '')))) {
        $errors[] = "El campo {$field} es obligatorio.";
    }
}

$munSel = trim((string)($form['DOM_MUNICIPIO'] ?? ''));
$munOtro = trim((string)($form['DOM_MUNICIPIO_OTRO'] ?? ''));
if ($munSel === '' && $munOtro === '') {
    $errors[] = 'Debes seleccionar o capturar un municipio/alcaldía.';
}

$fechaNino = DateTime::createFromFormat('Y-m-d', $form['FECHANNINO'] ?? '');
if (!$fechaNino) {
    $errors[] = 'Fecha de nacimiento del niño no es válida.';
}
$fechaTrab = DateTime::createFromFormat('Y-m-d', $form['FECHAT'] ?? '');
if (!$fechaTrab) {
    $errors[] = 'Fecha de nacimiento del trabajador no es válida.';
}

$horaEntrada = DateTime::createFromFormat('H:i', (string)($form['HORA_ENTRADA'] ?? '')) ?: null;
$horaSalida = DateTime::createFromFormat('H:i', (string)($form['HORA_SALIDA'] ?? '')) ?: null;
if (!$horaEntrada || !$horaSalida) {
    $errors[] = 'Las horas de entrada y salida deben tener formato HH:MM.';
} else {
    $entradaMin = ((int)$horaEntrada->format('H') * 60) + (int)$horaEntrada->format('i');
    $salidaMin = ((int)$horaSalida->format('H') * 60) + (int)$horaSalida->format('i');
    $diffMin = $salidaMin - $entradaMin;

    if ($diffMin <= 0) {
        $errors[] = 'La hora de salida debe ser posterior a la de entrada.';
    } elseif ($diffMin !== 480) {
        $errors[] = 'El horario laboral debe ser exactamente de 8 horas.';
    }
}

$horarioLaboral = ($horaEntrada && $horaSalida)
    ? $horaEntrada->format('H:i') . ' - ' . $horaSalida->format('H:i')
    : '';

$password = (string)($form['password'] ?? '');
$passwordConfirm = (string)($form['password_confirm'] ?? '');
$regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{8,}$/';

if (!preg_match($regexPassword, $password)) {
    $errors[] = 'La contraseña debe tener mínimo 8 caracteres e incluir mayúscula, minúscula, número y carácter especial.';
}
if ($password !== $passwordConfirm) {
    $errors[] = 'La confirmación de contraseña no coincide.';
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

if ($errors) {
    $_SESSION['form_errors'] = $errors;
    header('Location: ' . page_url('inscripcion.php'));
    exit;
}

// Paso de previsualización: no guardar, solo mostrar resumen para confirmación
if ($isPreview) {
    $edadMesesPreview = months_between($fechaNino, new DateTime());
    $grupoAsignadoPreview = asignar_grupo_por_edad($edadMesesPreview);

    $_SESSION['solicitud_preview'] = [
        'grupo_asignado' => $grupoAsignadoPreview,
        'nino_nombre' => $form['NOMBRES'] . ' ' . $form['APP'] . ' ' . $form['APM'],
        'nino_curp' => $form['CURPNINO'],
        'cendi' => $form['CENDININO'] ?? '',
        'trabajador_nombre' => $form['NOMBREST'] . ' ' . $form['APPT'] . ' ' . $form['APMT'],
        'trabajador_curp' => $form['CURPT'],
        'num_empleado' => $form['NumE'] ?? '',
        'horario' => $horarioLaboral,
    ];

    header('Location: ' . page_url('confirmacion.php'));
    exit;
}

$edadMeses = months_between($fechaNino, new DateTime());
$grupoAsignado = asignar_grupo_por_edad($edadMeses);

try {
    $pdo = db();
    seed_catalogs();
    $pdo->beginTransaction();

    // Catálogos básicos
    [$cendiId, $cendiNombre] = map_cendi($pdo, $form['CENDININO']);
    $grupoId = map_grupo($pdo, $grupoAsignado);
    $grupoSangId = get_or_create_id($pdo, 'grupo_sang', 'id_grupo_sang', 'grupo_sang', strtoupper((string)$form['grupo_sanguineo']), []);
    $rhVal = strtolower((string)$form['rh_factor']) === 'negativo' ? '-' : '+';
    $rhId = get_or_create_id($pdo, 'rh', 'id_rh', 'rh', $rhVal, []);

    $entNacNino = resolve_entidad($pdo, $form['LUGAR_NAC_NINO'] ?? '');
    $entDom = resolve_entidad($pdo, $form['DOM_ENTIDAD_OTRO'] ?? ($form['DOM_ENTIDAD'] ?? ''));
    $munDom = resolve_municipio($pdo, $form['DOM_MUNICIPIO_OTRO'] ?? ($form['DOM_MUNICIPIO'] ?? ''), $entDom);

    $domicilioId = create_domicilio($pdo, [
        'calle' => trim((string)($form['calle'] ?? '')),
        'num' => trim((string)($form['numero'] ?? '')),
        'cp' => trim((string)($form['CPNINO'] ?? '')),
        'id_entidad' => $entDom,
        'id_municipio' => $munDom,
    ]);

    $entNacTrab = resolve_entidad($pdo, $form['ENTIDAD_NAC_TRAB_OTRO'] ?? ($form['ENTIDAD_NAC_TRAB'] ?? ''));
    $ocupacionId = map_ocupacion($pdo, $form['trab_ocupacion'] ?? '');
    $escolaridadId = map_escolaridad($pdo, $form['escolaridad_select'] ?? '');
    $tipoAdsId = map_tipo_adscripcion($pdo, $form['tipo_institucion'] ?? '');
    $edoCivilId = map_edo_civil($pdo, $form['EC'] ?? '');

    $trabajadorId = create_trabajador($pdo, [
        'curp' => trim((string)$form['CURPT']),
        'apPat' => trim((string)$form['APPT']),
        'apMat' => trim((string)$form['APMT']),
        'nombres' => trim((string)$form['NOMBREST']),
        'fecha_nac' => $fechaTrab->format('Y-m-d'),
        'correo_institucional' => trim((string)$form['CIT']),
        'correo_personal' => trim((string)$form['CPT']),
        'num_empleado' => trim((string)$form['NumE']),
        'password_hash' => $passwordHash,
        'horario_laboral' => $horarioLaboral,
        'id_entidad_nac' => $entNacTrab,
        'id_ocupacion' => $ocupacionId,
        'id_escolaridad' => $escolaridadId,
        'id_tipo_adscripcion' => $tipoAdsId,
        'id_edo_civil' => $edoCivilId,
    ]);

    $ninoId = create_nino($pdo, [
        'curp' => trim((string)$form['CURPNINO']),
        'apPat' => trim((string)$form['APP']),
        'apMat' => trim((string)$form['APM']),
        'nombres' => trim((string)$form['NOMBRES']),
        'fecha_nac' => $fechaNino->format('Y-m-d'),
        'tel' => trim((string)$form['CONTACTONINO']),
        'id_grupo_sang' => $grupoSangId,
        'id_rh' => $rhId,
        'id_entidad_nac' => $entNacNino,
        'id_cendi' => $cendiId,
        'id_grupo' => $grupoId,
        'id_trabajador' => $trabajadorId,
        'id_domicilio' => $domicilioId,
    ]);

    $pdo->commit();

    unset($_SESSION['form_data'], $_SESSION['solicitud_preview']);
    flash('success', 'Inscripción guardada correctamente. Ahora puedes iniciar sesión.');
    header('Location: ' . page_url('acceso.php'));
    exit;
} catch (Throwable $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['form_errors'] = ['Error al guardar: ' . $e->getMessage()];
    header('Location: ' . page_url('inscripcion.php'));
    exit;
}

function months_between(DateTime $from, DateTime $to): int
{
    $diff = $from->diff($to);
    return ($diff->y * 12) + $diff->m;
}

function asignar_grupo_por_edad(int $meses): string
{
    if ($meses < 12) return 'Lactantes';
    if ($meses < 24) return 'Maternal A';
    if ($meses < 36) return 'Maternal B';
    return 'Preescolar';
}

function map_cendi(PDO $pdo, string $slug): array
{
    $map = [
        'amalia_solorzano' => 'CENDI "Amalia Solorzano de Cardenas"',
        'clementina_batalla' => 'CENDI "Clementina Batalla de Bassols"',
        'eva_samano' => 'CENDI "Eva Samano de Lopez Mateos"',
        'laura_perez' => 'CENDI "Laura Perez de Batiz"',
        'margarita_salazar' => 'CENDI "Margarita Salazar de Erro"',
    ];
    $name = $map[$slug] ?? $slug;
    $id = get_or_create_id($pdo, 'cendi', 'id_cendi', 'cendi', $name, []);
    return [$id, $name];
}

function map_grupo(PDO $pdo, string $grupo): int
{
    return get_or_create_id($pdo, 'grupo', 'id_grupo', 'grupo', $grupo, []);
}

function resolve_entidad(PDO $pdo, string $nombre): int
{
    $nombre = trim($nombre);
    if ($nombre === '') {
        return get_or_create_id($pdo, 'entidad_federativa', 'id_entidad', 'entidad', 'No especificado', []);
    }
    return get_or_create_id($pdo, 'entidad_federativa', 'id_entidad', 'entidad', $nombre, []);
}

function resolve_municipio(PDO $pdo, string $nombre, int $entidadId): ?int
{
    $nombre = trim($nombre);
    if ($nombre === '') {
        return null;
    }
    if ($entidadId <= 0) {
        return null;
    }
    return get_or_create_municipio($pdo, $nombre, $entidadId);
}

function create_domicilio(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare('INSERT INTO domicilio (calle, num, cp, id_entidad, id_municipio) VALUES (:calle, :num, :cp, :id_entidad, :id_municipio)');
    $stmt->execute([
        'calle' => $data['calle'] ?: null,
        'num' => $data['num'] ?: null,
        'cp' => $data['cp'] ?: null,
        'id_entidad' => $data['id_entidad'] ?: null,
        'id_municipio' => $data['id_municipio'] ?: null,
    ]);
    return (int)$pdo->lastInsertId();
}

function map_ocupacion(PDO $pdo, string $slug): int
{
    $map = ['docente' => 'Docente', 'paae' => 'PAAE'];
    $name = $map[strtolower($slug)] ?? $slug;
    return get_or_create_id($pdo, 'ocupacion', 'id_ocupacion', 'ocupacion', $name, []);
}

function map_escolaridad(PDO $pdo, string $slug): int
{
    $map = [
        'sin_escolaridad' => 'Sin escolaridad',
        'preescolar' => 'Preescolar',
        'primaria' => 'Primaria',
        'secundaria' => 'Secundaria',
        'bachillerato' => 'Bachillerato',
        'tecnico' => 'Tecnico',
        'tecnico_superior_universitario' => 'Tecnico Superior Universitario',
        'licenciatura' => 'Licenciatura',
        'especialidad' => 'Especialidad',
        'maestria' => 'Maestria',
        'doctorado' => 'Doctorado',
        'posdoctorado' => 'Posdoctorado',
    ];
    $name = $map[$slug] ?? $slug;
    return get_or_create_id($pdo, 'escolaridad', 'id_escolaridad', 'escolaridad', $name, []);
}

function map_tipo_adscripcion(PDO $pdo, string $slug): int
{
    $map = [
        'medio_superior' => 'Nivel Medio Superior',
        'superior' => 'Nivel Superior',
        'investigacion' => 'Centro de Investigacion',
        'cendi_apoyo' => 'CENDI y Centros de Apoyo',
    ];
    $name = $map[$slug] ?? $slug;
    return get_or_create_id($pdo, 'tipo_adscripcion', 'id_tipo_adscripcion', 'tipo_adscripcion', $name, []);
}

function map_edo_civil(PDO $pdo, string $slug): int
{
    $map = [
        'casado' => 'Casado',
        'soltero' => 'Soltero',
        'union_libre' => 'Union Libre',
    ];
    $name = $map[$slug] ?? $slug;
    return get_or_create_id($pdo, 'edo_civil', 'id_edo_civil', 'edo_civil', $name, []);
}

function create_trabajador(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare('INSERT INTO trabajador (curp, apPat, apMat, nombres, fecha_nac, correo_institucional, correo_personal, num_empleado, password_hash, horario_laboral, id_entidad_nac, id_ocupacion, id_escolaridad, id_tipo_adscripcion, id_edo_civil) VALUES (:curp, :apPat, :apMat, :nombres, :fecha_nac, :correo_institucional, :correo_personal, :num_empleado, :password_hash, :horario_laboral, :id_entidad_nac, :id_ocupacion, :id_escolaridad, :id_tipo_adscripcion, :id_edo_civil)');
    $stmt->execute($data);
    return (int)$pdo->lastInsertId();
}

function create_nino(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare('INSERT INTO nino (curp, apPat, apMat, nombres, fecha_nac, tel, id_grupo_sang, id_rh, id_entidad_nac, id_cendi, id_grupo, id_trabajador, id_domicilio) VALUES (:curp, :apPat, :apMat, :nombres, :fecha_nac, :tel, :id_grupo_sang, :id_rh, :id_entidad_nac, :id_cendi, :id_grupo, :id_trabajador, :id_domicilio)');
    $stmt->execute($data);
    return (int)$pdo->lastInsertId();
}
