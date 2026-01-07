<?php
$pageTitle = 'Inscripción CENDI';
$pageScripts = ['js/validaciones.js?v=2', 'js/formulario.js?v=2'];
include __DIR__ . '/../partials/head.php';

$formData = $_SESSION['form_data'] ?? [];
$formErrors = $_SESSION['form_errors'] ?? [];
$previewData = $_SESSION['solicitud_preview'] ?? null;
$keepData = isset($_GET['return']);

if (!$keepData) {
    unset($_SESSION['form_data'], $_SESSION['form_errors']);
}

function old(string $key, $default = '') {
    global $formData, $previewData;
    return $formData[$key] ?? ($previewData[$key] ?? $default);
}

function is_selected(string $key, string $value): string {
    return old($key) === $value ? 'selected' : '';
}

function is_checked(string $key, string $value): string {
    return old($key) === $value ? 'checked' : '';
}
?>

<div class="container my-5">
    <h1 class="mb-4 text-center" id="titulo-form">Inscripción CENDI</h1>

    <?php if (!empty($formErrors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($formErrors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($previewData): ?>
        <div class="alert alert-info">Revisa los datos, puedes modificarlos antes de enviar.</div>
    <?php endif; ?>

        <form method="POST" action="<?= asset('actions/inscripcion_guardar.php'); ?>">
            <input type="hidden" name="preview" value="1">
            <fieldset class="border p-4 mb-4 rounded shadow-sm">
                <legend class="legend-style">Datos de la Niña o del Niño</legend>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="APP" class="form-label">Primer Apellido</label>
                        <input type="text" class="form-control" id="APP" name="APP" value="<?= htmlspecialchars(old('APP'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="APM" class="form-label">Segundo Apellido</label>
                        <input type="text" class="form-control" id="APM" name="APM" value="<?= htmlspecialchars(old('APM'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="NOMBRES" class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" id="NOMBRES" name="NOMBRES" value="<?= htmlspecialchars(old('NOMBRES'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label for="CURPNINO" class="form-label">CURP</label>
                        <input type="text" class="form-control" id="CURPNINO" name="CURPNINO" maxlength="18" value="<?= htmlspecialchars(old('CURPNINO'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="FECHANNINO" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="FECHANNINO" name="FECHANNINO" value="<?= htmlspecialchars(old('FECHANNINO'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lugar de Nacimiento</label>
                        <div class="contenedor-lugar">
                            <select id="lugar_select" name="LUGAR_NAC_NINO" class="form-select" onchange="verificarSeleccion(this)">
                                <option value="" selected disabled>Seleccione una entidad...</option>
                                <option value="Aguascalientes" <?= is_selected('LUGAR_NAC_NINO','Aguascalientes'); ?>>Aguascalientes</option>
                                <option value="Baja California" <?= is_selected('LUGAR_NAC_NINO','Baja California'); ?>>Baja California</option>
                                <option value="Baja California Sur" <?= is_selected('LUGAR_NAC_NINO','Baja California Sur'); ?>>Baja California Sur</option>
                                <option value="Campeche" <?= is_selected('LUGAR_NAC_NINO','Campeche'); ?>>Campeche</option>
                                <option value="Chiapas" <?= is_selected('LUGAR_NAC_NINO','Chiapas'); ?>>Chiapas</option>
                                <option value="Chihuahua" <?= is_selected('LUGAR_NAC_NINO','Chihuahua'); ?>>Chihuahua</option>
                                <option value="Ciudad de México" <?= is_selected('LUGAR_NAC_NINO','Ciudad de México'); ?>>Ciudad de México</option>
                                <option value="Coahuila de Zaragoza" <?= is_selected('LUGAR_NAC_NINO','Coahuila de Zaragoza'); ?>>Coahuila de Zaragoza</option>
                                <option value="Colima" <?= is_selected('LUGAR_NAC_NINO','Colima'); ?>>Colima</option>
                                <option value="Durango" <?= is_selected('LUGAR_NAC_NINO','Durango'); ?>>Durango</option>
                                <option value="Estado de México" <?= is_selected('LUGAR_NAC_NINO','Estado de México'); ?>>Estado de México</option>
                                <option value="Guanajuato" <?= is_selected('LUGAR_NAC_NINO','Guanajuato'); ?>>Guanajuato</option>
                                <option value="Guerrero" <?= is_selected('LUGAR_NAC_NINO','Guerrero'); ?>>Guerrero</option>
                                <option value="Hidalgo" <?= is_selected('LUGAR_NAC_NINO','Hidalgo'); ?>>Hidalgo</option>
                                <option value="Jalisco" <?= is_selected('LUGAR_NAC_NINO','Jalisco'); ?>>Jalisco</option>
                                <option value="Michoacán de Ocampo" <?= is_selected('LUGAR_NAC_NINO','Michoacán de Ocampo'); ?>>Michoacán de Ocampo</option>
                                <option value="Morelos" <?= is_selected('LUGAR_NAC_NINO','Morelos'); ?>>Morelos</option>
                                <option value="Nayarit" <?= is_selected('LUGAR_NAC_NINO','Nayarit'); ?>>Nayarit</option>
                                <option value="Nuevo León" <?= is_selected('LUGAR_NAC_NINO','Nuevo León'); ?>>Nuevo León</option>
                                <option value="Oaxaca" <?= is_selected('LUGAR_NAC_NINO','Oaxaca'); ?>>Oaxaca</option>
                                <option value="Puebla" <?= is_selected('LUGAR_NAC_NINO','Puebla'); ?>>Puebla</option>
                                <option value="Querétaro" <?= is_selected('LUGAR_NAC_NINO','Querétaro'); ?>>Querétaro</option>
                                <option value="Quintana Roo" <?= is_selected('LUGAR_NAC_NINO','Quintana Roo'); ?>>Quintana Roo</option>
                                <option value="San Luis Potosí" <?= is_selected('LUGAR_NAC_NINO','San Luis Potosí'); ?>>San Luis Potosí</option>
                                <option value="Sinaloa" <?= is_selected('LUGAR_NAC_NINO','Sinaloa'); ?>>Sinaloa</option>
                                <option value="Sonora" <?= is_selected('LUGAR_NAC_NINO','Sonora'); ?>>Sonora</option>
                                <option value="Tabasco" <?= is_selected('LUGAR_NAC_NINO','Tabasco'); ?>>Tabasco</option>
                                <option value="Tamaulipas" <?= is_selected('LUGAR_NAC_NINO','Tamaulipas'); ?>>Tamaulipas</option>
                                <option value="Tlaxcala" <?= is_selected('LUGAR_NAC_NINO','Tlaxcala'); ?>>Tlaxcala</option>
                                <option value="Veracruz de Ignacio de la Llave" <?= is_selected('LUGAR_NAC_NINO','Veracruz de Ignacio de la Llave'); ?>>Veracruz de Ignacio de la Llave</option>
                                <option value="Yucatán" <?= is_selected('LUGAR_NAC_NINO','Yucatán'); ?>>Yucatán</option>
                                <option value="Zacatecas" <?= is_selected('LUGAR_NAC_NINO','Zacatecas'); ?>>Zacatecas</option>
                                <option value="otro">Otro (Extranjero)</option>
                                </select>
                            <div class="wrapper-input-otro d-none mt-2 d-flex gap-2">
                                <input type="text" class="form-control" placeholder="Especifique lugar" value="<?= htmlspecialchars(old('LUGAR_NAC_NINO'), ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="button" class="btn btn-outline-secondary" onclick="regresarASelect(this)">✕</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Grupo Sanguíneo y Rh</label>
                        <div class="d-flex gap-2">
                            <select id="grupo_sanguineo" name="grupo_sanguineo" class="form-select" required>
                                <option value="" <?= old('grupo_sanguineo') ? '' : 'selected'; ?> disabled>Grupo...</option>
                                <option value="O" <?= is_selected('grupo_sanguineo','O'); ?>>O</option>
                                <option value="A" <?= is_selected('grupo_sanguineo','A'); ?>>A</option>
                                <option value="B" <?= is_selected('grupo_sanguineo','B'); ?>>B</option>
                                <option value="AB" <?= is_selected('grupo_sanguineo','AB'); ?>>AB</option>
                            </select>
                            <select id="rh_factor" name="rh_factor" class="form-select" required style="width: 100px;">
                                <option value="" <?= old('rh_factor') ? '' : 'selected'; ?> disabled>Rh...</option>
                                <option value="positivo" <?= is_selected('rh_factor','positivo'); ?>>+</option>
                                <option value="negativo" <?= is_selected('rh_factor','negativo'); ?>>-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="CONTACTONINO" class="form-label">Teléfono (Contacto)</label>
                        <input type="tel" class="form-control" id="CONTACTONINO" name="CONTACTONINO" pattern="[0-9]{10}" value="<?= htmlspecialchars(old('CONTACTONINO'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Grupo (asignado automáticamente)</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($previewData['grupo_asignado'] ?? old('grupo_asignado', 'Pendiente'), ENT_QUOTES, 'UTF-8'); ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Domicilio</label>
                        <div class="row g-2">
                            <div class="col-8">
                                <input type="text" name="calle" class="form-control" placeholder="Calle" value="<?= htmlspecialchars(old('calle'), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-4">
                                <input type="text" name="numero" class="form-control" placeholder="Núm." value="<?= htmlspecialchars(old('numero'), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
    <div class="col-md-6">
        <label class="form-label">Entidad Federativa</label>
        <div class="contenedor-dinamico">
            <select class="form-select" id="DOM_ENTIDAD" onchange="gestionarSeleccionOtro(this)" name="DOM_ENTIDAD" required>
                <option value="" selected disabled>Seleccione...</option>
                <option value="Aguascalientes" <?= is_selected('DOM_ENTIDAD','Aguascalientes'); ?>>Aguascalientes</option>
                <option value="Baja California" <?= is_selected('DOM_ENTIDAD','Baja California'); ?>>Baja California</option>
                <option value="Baja California Sur" <?= is_selected('DOM_ENTIDAD','Baja California Sur'); ?>>Baja California Sur</option>
                <option value="Campeche" <?= is_selected('DOM_ENTIDAD','Campeche'); ?>>Campeche</option>
                <option value="Chiapas" <?= is_selected('DOM_ENTIDAD','Chiapas'); ?>>Chiapas</option>
                <option value="Chihuahua" <?= is_selected('DOM_ENTIDAD','Chihuahua'); ?>>Chihuahua</option>
                <option value="Ciudad de México" <?= is_selected('DOM_ENTIDAD','Ciudad de México'); ?>>Ciudad de México</option>
                <option value="Coahuila de Zaragoza" <?= is_selected('DOM_ENTIDAD','Coahuila de Zaragoza'); ?>>Coahuila de Zaragoza</option>
                <option value="Colima" <?= is_selected('DOM_ENTIDAD','Colima'); ?>>Colima</option>
                <option value="Durango" <?= is_selected('DOM_ENTIDAD','Durango'); ?>>Durango</option>
                <option value="Estado de México" <?= is_selected('DOM_ENTIDAD','Estado de México'); ?>>Estado de México</option>
                <option value="Guanajuato" <?= is_selected('DOM_ENTIDAD','Guanajuato'); ?>>Guanajuato</option>
                <option value="Guerrero" <?= is_selected('DOM_ENTIDAD','Guerrero'); ?>>Guerrero</option>
                <option value="Hidalgo" <?= is_selected('DOM_ENTIDAD','Hidalgo'); ?>>Hidalgo</option>
                <option value="Jalisco" <?= is_selected('DOM_ENTIDAD','Jalisco'); ?>>Jalisco</option>
                <option value="Michoacán de Ocampo" <?= is_selected('DOM_ENTIDAD','Michoacán de Ocampo'); ?>>Michoacán de Ocampo</option>
                <option value="Morelos" <?= is_selected('DOM_ENTIDAD','Morelos'); ?>>Morelos</option>
                <option value="Nayarit" <?= is_selected('DOM_ENTIDAD','Nayarit'); ?>>Nayarit</option>
                <option value="Nuevo León" <?= is_selected('DOM_ENTIDAD','Nuevo León'); ?>>Nuevo León</option>
                <option value="Oaxaca" <?= is_selected('DOM_ENTIDAD','Oaxaca'); ?>>Oaxaca</option>
                <option value="Puebla" <?= is_selected('DOM_ENTIDAD','Puebla'); ?>>Puebla</option>
                <option value="Querétaro" <?= is_selected('DOM_ENTIDAD','Querétaro'); ?>>Querétaro</option>
                <option value="Quintana Roo" <?= is_selected('DOM_ENTIDAD','Quintana Roo'); ?>>Quintana Roo</option>
                <option value="San Luis Potosí" <?= is_selected('DOM_ENTIDAD','San Luis Potosí'); ?>>San Luis Potosí</option>
                <option value="Sinaloa" <?= is_selected('DOM_ENTIDAD','Sinaloa'); ?>>Sinaloa</option>
                <option value="Sonora" <?= is_selected('DOM_ENTIDAD','Sonora'); ?>>Sonora</option>
                <option value="Tabasco" <?= is_selected('DOM_ENTIDAD','Tabasco'); ?>>Tabasco</option>
                <option value="Tamaulipas" <?= is_selected('DOM_ENTIDAD','Tamaulipas'); ?>>Tamaulipas</option>
                <option value="Tlaxcala" <?= is_selected('DOM_ENTIDAD','Tlaxcala'); ?>>Tlaxcala</option>
                <option value="Veracruz de Ignacio de la Llave" <?= is_selected('DOM_ENTIDAD','Veracruz de Ignacio de la Llave'); ?>>Veracruz de Ignacio de la Llave</option>
                <option value="Yucatán" <?= is_selected('DOM_ENTIDAD','Yucatán'); ?>>Yucatán</option>
                <option value="Zacatecas" <?= is_selected('DOM_ENTIDAD','Zacatecas'); ?>>Zacatecas</option>
                <option value="otro">Otro</option>
            </select>
            <div class="wrapper-input-otro d-none mt-2 d-flex gap-2">
                <input type="text" class="form-control" placeholder="Especifique entidad" name="DOM_ENTIDAD_OTRO" value="<?= htmlspecialchars(old('DOM_ENTIDAD_OTRO'), ENT_QUOTES, 'UTF-8'); ?>">
                <button type="button" class="btn btn-outline-danger" onclick="cancelarSeleccionOtro(this)">✕</button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Alcaldía o municipio</label>
        <div class="contenedor-dinamico">
            <select class="form-select" id="DOM_MUNICIPIO" onchange="gestionarSeleccionOtro(this)" name="DOM_MUNICIPIO" required>
                <option value="" selected disabled>Seleccione...</option>
                <option value="Álvaro Obregón" <?= is_selected('DOM_MUNICIPIO','Álvaro Obregón'); ?>>Álvaro Obregón</option>
                <option value="Azcapotzalco">Azcapotzalco</option>
                <option value="Benito Juárez">Benito Juárez</option>
                <option value="Coyoacán">Coyoacán</option>
                <option value="Cuajimalpa de Morelos">Cuajimalpa de Morelos</option>
                <option value="Cuauhtémoc">Cuauhtémoc</option>
                <option value="Gustavo A. Madero">Gustavo A. Madero</option>
                <option value="Iztacalco">Iztacalco</option>
                <option value="Iztapalapa">Iztapalapa</option>
                <option value="La Magdalena Contreras">La Magdalena Contreras</option>
                <option value="Miguel Hidalgo">Miguel Hidalgo</option>
                <option value="Milpa Alta">Milpa Alta</option>
                <option value="Tláhuac">Tláhuac</option>
                <option value="Tlalpan">Tlalpan</option>
                <option value="Venustiano Carranza">Venustiano Carranza</option>
                <option value="Xochimilco">Xochimilco</option>
                <option value="otro">Otro</option>
            </select>
            <div class="wrapper-input-otro d-none mt-2 d-flex gap-2">
                <input type="text" class="form-control" placeholder="Especifique alcaldía" name="DOM_MUNICIPIO_OTRO" value="<?= htmlspecialchars(old('DOM_MUNICIPIO_OTRO'), ENT_QUOTES, 'UTF-8'); ?>">
                <button type="button" class="btn btn-outline-danger" onclick="cancelarSeleccionOtro(this)">✕</button>
            </div>
        </div>
    </div>

                    <div class="col-md-4">
                        <label for="CPNINO" class="form-label">C.P.</label>
                        <input type="text" class="form-control" id="CPNINO" name="CPNINO" pattern="[0-9]{5}" value="<?= htmlspecialchars(old('CPNINO'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="col-4">
                        <label for="CENDININO" class="form-label">CENDI de Adscripción</label>
                        <select id="CENDININO" name="CENDININO" class="form-select" required>
                            <option value="" disabled <?= old('CENDININO') ? '' : 'selected'; ?>>-- Seleccione uno de los 5 CENDI --</option>
                            <option value="amalia_solorzano" <?= is_selected('CENDININO','amalia_solorzano'); ?>>CENDI “Amalia Solórzano de Cárdenas”</option>
                            <option value="clementina_batalla" <?= is_selected('CENDININO','clementina_batalla'); ?>>CENDI “Clementina Batalla de Bassols”</option>
                            <option value="eva_samano" <?= is_selected('CENDININO','eva_samano'); ?>>CENDI “Eva Sámano de López Mateos”</option>
                            <option value="laura_perez" <?= is_selected('CENDININO','laura_perez'); ?>>CENDI “Laura Pérez de Bátiz”</option>
                            <option value="margarita_salazar" <?= is_selected('CENDININO','margarita_salazar'); ?>>CENDI “Margarita Salazar de Erro”</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border p-4 mb-4 rounded shadow-sm">
                <legend class="legend-style">Datos del o de la Trabajadora</legend>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="APPT" class="form-label">Primer Apellido</label>
                        <input type="text" class="form-control" id="APPT" name="APPT" value="<?= htmlspecialchars(old('APPT'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="APMT" class="form-label">Segundo Apellido</label>
                        <input type="text" class="form-control" id="APMT" name="APMT" value="<?= htmlspecialchars(old('APMT'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="NOMBREST" class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" id="NOMBREST" name="NOMBREST" value="<?= htmlspecialchars(old('NOMBREST'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label for="CURPT" class="form-label">CURP</label>
                        <input type="text" class="form-control" id="CURPT" name="CURPT" maxlength="18" value="<?= htmlspecialchars(old('CURPT'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="FECHAT" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="FECHAT" name="FECHAT" value="<?= htmlspecialchars(old('FECHAT'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                        <div class="col-md-4">
        <label class="form-label">Lugar de nacimiento</label>
        <div class="contenedor-dinamico">
            <select class="form-select" onchange="gestionarSeleccionOtro(this)" name="ENTIDAD_NAC_TRAB">
                <option value="" selected disabled>Seleccione...</option>
                <option value="Aguascalientes" <?= is_selected('ENTIDAD_NAC_TRAB','Aguascalientes'); ?>>Aguascalientes</option>
                <option value="Baja California" <?= is_selected('ENTIDAD_NAC_TRAB','Baja California'); ?>>Baja California</option>
                <option value="Baja California Sur">Baja California Sur</option>
                <option value="Campeche">Campeche</option>
                <option value="Chiapas">Chiapas</option>
                <option value="Chihuahua">Chihuahua</option>
                <option value="Ciudad de México">Ciudad de México</option>
                <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                <option value="Colima">Colima</option>
                <option value="Durango">Durango</option>
                <option value="Estado de México">Estado de México</option>
                <option value="Guanajuato">Guanajuato</option>
                <option value="Guerrero">Guerrero</option>
                <option value="Hidalgo">Hidalgo</option>
                <option value="Jalisco">Jalisco</option>
                <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                <option value="Morelos">Morelos</option>
                <option value="Nayarit">Nayarit</option>
                <option value="Nuevo León">Nuevo León</option>
                <option value="Oaxaca">Oaxaca</option>
                <option value="Puebla">Puebla</option>
                <option value="Querétaro">Querétaro</option>
                <option value="Quintana Roo">Quintana Roo</option>
                <option value="San Luis Potosí">San Luis Potosí</option>
                <option value="Sinaloa">Sinaloa</option>
                <option value="Sonora">Sonora</option>
                <option value="Tabasco">Tabasco</option>
                <option value="Tamaulipas">Tamaulipas</option>
                <option value="Tlaxcala">Tlaxcala</option>
                <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                <option value="Yucatán">Yucatán</option>
                <option value="Zacatecas">Zacatecas</option>
                <option value="otro">Otro</option>
            </select>
            <div class="wrapper-input-otro d-none mt-2 d-flex gap-2">
                <input type="text" class="form-control" placeholder="Especifique entidad" name="ENTIDAD_NAC_TRAB_OTRO" value="<?= htmlspecialchars(old('ENTIDAD_NAC_TRAB_OTRO'), ENT_QUOTES, 'UTF-8'); ?>">
                <button type="button" class="btn btn-outline-danger" onclick="cancelarSeleccionOtro(this)">✕</button>
            </div>
        </div>
    </div>

                    <div class="col-md-6">
                        <label for="CIT" class="form-label">Correo Institucional</label>
                        <input type="email" class="form-control" id="CIT" name="CIT"
                               placeholder="email@ipn.mx"
                               value="<?= htmlspecialchars(old('CIT', ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="CPT" class="form-label">Correo Personal</label>
                        <input type="email" class="form-control" id="CPT" name="CPT"
                               placeholder="email@example.com"
                               value="<?= htmlspecialchars(old('CPT', ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Contraseña de acceso</label>
                        <input type="password" class="form-control" id="password" name="password" aria-describedby="passHelp"
                               placeholder="Mínimo 8 caracteres, combina mayúsculas, minúsculas, número y símbolo" required>
                        <div id="passHelp" class="form-text">Se usará para iniciar sesión en Acceso.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirm" class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="OCUPACION" class="form-label">Ocupación</label>
                        <select id="OCUPACION" name="trab_ocupacion" class="form-select" required>
                            <option value="" disabled <?= old('trab_ocupacion') ? '' : 'selected'; ?>>-- Selecciona --</option>
                            <option value="docente" <?= is_selected('trab_ocupacion','docente'); ?>>Docente</option>
                            <option value="paae" <?= is_selected('trab_ocupacion','paae'); ?>>PAAE</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="NumE" class="form-label">Número de Empleado</label>
                        <input type="text" class="form-control" id="NumE" name="NumE" value="<?= htmlspecialchars(old('NumE'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="ESCOLARIDAD" class="form-label">Escolaridad</label>
                        <select name="escolaridad_select" class="form-select" required>
                            <option value="" disabled <?= old('escolaridad_select') ? '' : 'selected'; ?>>-- Seleccione --</option>
                            <option value="sin_escolaridad" <?= is_selected('escolaridad_select','sin_escolaridad'); ?>>Sin escolaridad</option>
                            <option value="preescolar" <?= is_selected('escolaridad_select','preescolar'); ?>>Preescolar</option>
                            <option value="primaria" <?= is_selected('escolaridad_select','primaria'); ?>>Primaria</option>
                            <option value="secundaria" <?= is_selected('escolaridad_select','secundaria'); ?>>Secundaria</option>
                            <option value="bachillerato" <?= is_selected('escolaridad_select','bachillerato'); ?>>Bachillerato</option>
                            <option value="tecnico" <?= is_selected('escolaridad_select','tecnico'); ?>>Técnico</option>
                            <option value="tecnico_superior_universitario" <?= is_selected('escolaridad_select','tecnico_superior_universitario'); ?>>Técnico Superior Universitario</option>
                            <option value="licenciatura" <?= is_selected('escolaridad_select','licenciatura'); ?>>Licenciatura</option>
                            <option value="especialidad" <?= is_selected('escolaridad_select','especialidad'); ?>>Especialidad</option>
                            <option value="maestria" <?= is_selected('escolaridad_select','maestria'); ?>>Maestría</option>
                            <option value="doctorado" <?= is_selected('escolaridad_select','doctorado'); ?>>Doctorado</option>
                            <option value="posdoctorado" <?= is_selected('escolaridad_select','posdoctorado'); ?>>Posdoctorado</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="ADSCRIPCION" class="form-label">Tipo de Adscripción</label>
                  <div class="form-group">
                    <select name="tipo_institucion" id="tipo_institucion" class="form-select" onchange="cambiarAdscripcion()">
                        <option value="" disabled <?= old('tipo_institucion') ? '' : 'selected'; ?>>-- Selecciona el tipo --</option>
                        <option value="medio_superior" <?= is_selected('tipo_institucion','medio_superior'); ?>>Nivel Medio Superior (CECyT / CET)</option>
                        <option value="superior" <?= is_selected('tipo_institucion','superior'); ?>>Nivel Superior (Escuelas / UPII)</option>
                        <option value="investigacion" <?= is_selected('tipo_institucion','investigacion'); ?>>Centros de Investigación</option>
                        <option value="cendi_apoyo" <?= is_selected('tipo_institucion','cendi_apoyo'); ?>>CENDI y Centros de Apoyo</option>
                    </select>
                   
                    </div>
                   
                    </div>

                <div class="col-md-6">
                        <div class="form-group ">
                        <label for="adscripcion" class="form-label">Adscripción</label>
                        <select name="adscripcion" id="adscripcion" class="form-select">
                            <option value="">-- Selecciona primero el tipo --</option>
                        </select>
                    </div>
                </div>

                    <div class="col-md-4">
                        <label for="HORA_ENTRADA" class="form-label">Hora de entrada</label>
                        <input type="time" class="form-control" id="HORA_ENTRADA" name="HORA_ENTRADA" value="<?= htmlspecialchars(old('HORA_ENTRADA'), ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="HORA_SALIDA" class="form-label">Hora de salida</label>
                        <input type="time" class="form-control" id="HORA_SALIDA" name="HORA_SALIDA" value="<?= htmlspecialchars(old('HORA_SALIDA'), ENT_QUOTES, 'UTF-8'); ?>" required>
                        <div class="form-text">Debe haber exactamente 8 horas entre entrada y salida.</div>
                    </div>
                    
                    <div class="col-6 mt-3 ">
                        <label class="form-label d-block">Estado Civil</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="casado" name="EC" value="casado" <?= is_checked('EC','casado'); ?> required>
                            <label class="form-check-label" for="casado">Casado(a)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="soltero" name="EC" value="soltero" <?= is_checked('EC','soltero'); ?>>
                            <label class="form-check-label" for="soltero">Soltero(a)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="union_libre" name="EC" value="union_libre" <?= is_checked('EC','union_libre'); ?>>
                            <label class="form-check-label" for="union_libre">Unión Libre</label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="row g-4 mt-2">
                <div class="col-md-6 d-grid">
                    <button class="btn btn-primary btn-lg btn-form" type="submit">Registrar</button>
                </div>
                <div class="col-md-6 d-grid">
                    <button class="btn btn-secondary btn-lg btn-form" type="reset">Limpiar</button>
                </div>
            </div>
    </form>
</div>

    <script>
        function verificarSeleccion(select) {
            if (select.value === 'otro') {
                const contenedor = select.closest('.contenedor-lugar');
                const wrapper = contenedor.querySelector('.wrapper-input-otro');
                const input = wrapper.querySelector('input');

                const selectName = select.getAttribute('name');
                select.classList.add('d-none');
                wrapper.classList.remove('d-none');
                input.setAttribute('name', selectName);
                select.removeAttribute('name');
                input.focus();
            }
        }

        function regresarASelect(btn) {
            const wrapper = btn.closest('.wrapper-input-otro');
            const contenedor = wrapper.closest('.contenedor-lugar');
            const select = contenedor.querySelector('select');
            const input = wrapper.querySelector('input');

            const inputName = input.getAttribute('name');
            wrapper.classList.add('d-none');
            select.classList.remove('d-none');
            select.setAttribute('name', inputName);
            input.removeAttribute('name');
            select.value = "";
            input.value = "";
        }
    </script>
    <script>
        function gestionarSeleccionOtro(selectElement) {
    // Buscamos el contenedor padre de este campo específico
    const contenedor = selectElement.closest('.contenedor-dinamico');
    const wrapper = contenedor.querySelector('.wrapper-input-otro');
    const input = wrapper.querySelector('input');

    if (selectElement.value === 'otro') {
        const selectName = selectElement.getAttribute('name');
        selectElement.classList.add('d-none');
        wrapper.classList.remove('d-none');
        selectElement.removeAttribute('required');
        selectElement.removeAttribute('name');
        input.setAttribute('name', selectName);
        input.setAttribute('required', 'required');
        input.focus();
    }
}

function cancelarSeleccionOtro(botonElement) {
    // Buscamos el contenedor padre desde el botón
    const contenedor = botonElement.closest('.contenedor-dinamico');
    const select = contenedor.querySelector('select');
    const wrapper = contenedor.querySelector('.wrapper-input-otro');
    const input = wrapper.querySelector('input');

    const inputName = input.getAttribute('name');
    const selectName = select.getAttribute('name') || inputName;

    wrapper.classList.add('d-none');
    select.classList.remove('d-none');

    input.removeAttribute('required');
    input.removeAttribute('name');
    select.setAttribute('required', 'required');
    select.setAttribute('name', selectName);

    select.value = "";
    input.value = "";
}
    </script>

    <script>
// Diccionario de opciones
const opcionesAdscripcion = {
  "medio_superior": [
    { val: "cecyt1", text: 'CECyT 1 "Gonzalo Vázquez Vela"' },
    { val: "cecyt2", text: 'CECyT 2 "Miguel Bernard"' },
    { val: "cecyt3", text: 'CECyT 3 "Estanislao Ramírez Ruiz"' },
    { val: "cecyt4", text: 'CECyT 4 "Lázaro Cárdenas"' },
    { val: "cecyt5", text: 'CECyT 5 "Benito Juárez"' },
    { val: "cecyt6", text: 'CECyT 6 "Miguel Othón de Mendizábal"' },
    { val: "cecyt7", text: 'CECyT 7 "Cuauhtémoc"' },
    { val: "cecyt8", text: 'CECyT 8 "Narciso Bassols"' },
    { val: "cecyt9", text: 'CECyT 9 "Juan de Dios Bátiz"' },
    { val: "cecyt10", text: 'CECyT 10 "Carlos Vallejo Márquez"' },
    { val: "cecyt11", text: 'CECyT 11 "Wilfrido Massieu"' },
    { val: "cecyt12", text: 'CECyT 12 "José María Morelos"' },
    { val: "cecyt13", text: 'CECyT 13 "Ricardo Flores Magón"' },
    { val: "cecyt14", text: 'CECyT 14 "Luis Enrique Erro"' },
    { val: "cecyt15", text: 'CECyT 15 "Diódoro Antúnez Echegaray"' },
    { val: "cecyt16", text: 'CECyT 16 "Hidalgo"' },
    { val: "cecyt17", text: 'CECyT 17 "Guanajuato"' },
    { val: "cecyt18", text: 'CECyT 18 "Zacatecas"' },
    { val: "cecyt19", text: 'CECyT 19 "Leona Vicario"' },
    { val: "cet1", text: 'CET 1 "Walter Cross Buchanan"' }
  ],
  "superior": [
    { val: "escom", text: "ESCOM - Escuela Superior de Cómputo" },
    { val: "esfm", text: "ESFM - Escuela Superior de Física y Matemáticas" },
    { val: "esime_zac", text: "ESIME Zacatenco" },
    { val: "upiita", text: "UPIITA - Tecnologías Avanzadas" },
    { val: "upiicsa", text: "UPIICSA - Ing. y Ciencias Soc. y Admin." },
    { val: "esca_sto", text: "ESCA Santo Tomás" }
    // Puedes agregar más aquí...
  ],
  "investigacion": [
    { val: "cic", text: "CIC - Centro de Investigación en Computación" },
    { val: "cidetec", text: "CIDETEC - Desarrollo Tecnológico" },
    { val: "cinvestav", text: "CINVESTAV" }
  ],
  "cendi_apoyo": [
    { val: "cendi1", text: 'CENDI 1 "Amalia Solórzano de Cárdenas"' },
    { val: "cendi2", text: 'CENDI 2 "Margarita Maza de Juárez"' },
    { val: "cendi3", text: 'CENDI 3 "Carmen Serdán"' },
    { val: "cendi4", text: 'CENDI 4 "Clementina Batalla de Bassols"' },
    { val: "cendi5", text: 'CENDI 5 "Laura Pérez de Gleasson"' }
  ]
};

function cambiarAdscripcion() {
  const tipoSeleccionado = document.getElementById("tipo_institucion").value;
  const selectAdscripcion = document.getElementById("adscripcion");

  // 1. Limpiar opciones actuales
  selectAdscripcion.innerHTML = '<option value="">-- Selecciona una unidad --</option>';

  // 2. Verificar si hay opciones para lo seleccionado
  if (tipoSeleccionado && opcionesAdscripcion[tipoSeleccionado]) {
    // 3. Recorrer el array y crear los <option>
    opcionesAdscripcion[tipoSeleccionado].forEach(function(item) {
      let nuevaOption = document.createElement("option");
      nuevaOption.value = item.val;
      nuevaOption.text = item.text;
      selectAdscripcion.appendChild(nuevaOption);
    });
  }

    const prev = "<?= addslashes(old('adscripcion')); ?>";
    if (prev) {
        selectAdscripcion.value = prev;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const tipoPrev = "<?= addslashes(old('tipo_institucion')); ?>";
    if (tipoPrev) {
        const selectTipo = document.getElementById('tipo_institucion');
        selectTipo.value = tipoPrev;
        cambiarAdscripcion();
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>