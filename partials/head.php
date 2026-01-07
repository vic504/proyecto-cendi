<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session.php';

$pageTitle = $pageTitle ?? 'CENDI IPN';
$pageScripts = $pageScripts ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="<?= asset('js/jquery-3.7.1.min.js'); ?>" defer></script>
    <script src="<?= asset('js/main.js'); ?>" defer></script>
</head>
<body>
<?php include __DIR__ . '/nav.php'; ?>
<main class="page-content">
