<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/session.php';

require_login('trabajador');
$user = current_user();
$pdo = db();

function fetch_child(PDO $pdo, int $trabajadorId): ?array {
    $stmt = $pdo->prepare('SELECT n.id_nino, n.curp, n.nombres, n.apPat, n.apMat, n.fecha_nac, n.id_cendi, n.id_grupo, g.grupo, c.cendi
        FROM nino n
        LEFT JOIN grupo g ON g.id_grupo = n.id_grupo
        LEFT JOIN cendi c ON c.id_cendi = n.id_cendi
        WHERE n.id_trabajador = :id
        ORDER BY n.id_nino DESC
        LIMIT 1');
    $stmt->execute(['id' => $trabajadorId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

$child = fetch_child($pdo, (int)$user['id']);
if (!$child) {
    flash('form_errors', ['No se encontró un registro para generar el acuse.']);
    header('Location: ' . page_url('panel.php'));
    exit;
}

$cendiCatalog = [
    'CENDI "Amalia Solorzano de Cardenas"' => ['direccion' => 'Dirección no disponible'],
    'CENDI "Clementina Batalla de Bassols"' => ['direccion' => 'Dirección no disponible'],
    'CENDI "Eva Samano de Lopez Mateos"' => ['direccion' => 'Dirección no disponible'],
    'CENDI "Laura Perez de Batiz"' => ['direccion' => 'Dirección no disponible'],
    'CENDI "Margarita Salazar de Erro"' => ['direccion' => 'Dirección no disponible'],
];
$cendiInfo = $cendiCatalog[$child['cendi']] ?? ['direccion' => 'Dirección no disponible'];

require_once __DIR__ . '/../Ejemplos_FPDF/fpdf/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Acuse de inscripción'), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, utf8_decode('Trabajador(a):'));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode($user['nombre'] ?? ''), 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, 'CURP hija/o:');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode($child['curp']), 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, 'Nombre hija/o:');
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode(trim($child['nombres'] . ' ' . $child['apPat'] . ' ' . $child['apMat'])), 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, utf8_decode('Fecha de nacimiento:'));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode($child['fecha_nac']), 0, 1);

$pdf->Ln(4);
$pdf->SetFillColor(230, 240, 255);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, 'CENDI:', 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode($child['cendi'] ?? 'Pendiente'), 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, utf8_decode('Dirección CENDI:'), 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->MultiCell(0, 8, utf8_decode($cendiInfo['direccion']), 0, 'L');

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 8, 'Grupo:', 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode($child['grupo'] ?? 'Pendiente'), 0, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 6, utf8_decode('Este documento confirma el registro en el sistema CENDI. Guarde este acuse para futuros trámites.'));

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="acuse_inscripcion.pdf"');
$pdf->Output('I', 'acuse_inscripcion.pdf');
exit;
