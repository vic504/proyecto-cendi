<?php
$pageTitle = 'Acceso';
$pageScripts = ['js/validaciones.js', 'js/acceso.js'];
include __DIR__ . '/../partials/head.php';

$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);
$success = flash('success');
?>

<div class="container my-5">
    <div class="mx-auto" style="max-width: 500px;">
        <h1 class="mb-4 text-center " id="titulo-form">Acceso</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (!empty($formErrors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($formErrors as $err): ?>
                        <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="formAcceso" method="POST" action="<?= asset('actions/login.php'); ?>">
            <fieldset class="border p-4 mb-4 rounded shadow-sm">
                <legend>Inicia Sesión</legend>

                <div class="row g-3">

                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"></path>
                                </svg>
                            </span>
                            <input type="text" id="inputUsuario" name="usuario" class="form-control" placeholder="Ingresa correo o número de empleado" autocomplete="username" required>
                        </div>
                    </div>



                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4M4.5 7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7zM8 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"></path>
                                </svg>
                            </span>
                            <input type="password" id="inputPassword" name="password" class="form-control" aria-describedby="passwordHelpBlock" autocomplete="current-password" required>
                            <div id="passwordHelpBlock" class="form-text">
                                Su contraseña debe incluir mínimo 8 caracteres, una mayúscula, una minúscula, un dígito y un carácter especial.
                            </div>
                        </div>
                    </div>

                </div>
            </fieldset>

            <div class="row g-4">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary btn-lg btn-form" type="submit">Acceder</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>