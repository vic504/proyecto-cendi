/**
 * Sistema de Inscripción CENDI - IPN
 * Equipo 4 - Tecnologías para la Web
 * Lógica del formulario de inscripción
 */
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('lugar_select');
    const input = document.getElementById('lugar_input');

    select.addEventListener('change', () => {
        if (select.value === 'otro') {
            select.classList.add('d-none');
            input.classList.remove('d-none');

            select.removeAttribute('name');
            input.setAttribute('name', 'lugar_nacimiento');

            input.focus();
        }
    });

    input.addEventListener('blur', () => {
        if (input.value.trim() === '') {
            input.classList.add('d-none');
            select.classList.remove('d-none');

            input.removeAttribute('name');
            select.setAttribute('name', 'lugar_nacimiento');

            select.value = '';
        }
    });
});

// ========== VARIABLES GLOBALES ==========
let formulario;
let camposValidados = new Map();

// ========== FUNCIONES DE UTILIDAD ==========

/**
 * Función para mostrar campo "Otro" en caso de no ser alcaldía de CDMX
 */ 
function mostrarOtro() {
    const select = document.getElementById("GRUPO");
    const otro = document.getElementById("otro-container");

    if (select && otro) {
        if (select.value === "otro") {
            otro.style.display = "block";
        } else {
            otro.style.display = "none";
        }
    }
}

/**
 * Calcula la edad a partir de una fecha de nacimiento
 * @param {string} fechaNacimiento - Fecha en formato YYYY-MM-DD
 * @returns {number} - Edad en años
 */
function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();
    
    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }
    return edad;
}

/**
 * Determina el género a partir del CURP
 * @param {string} curp - CURP de 18 caracteres
 * @returns {string} - "Hombre" o "Mujer"
 */
function obtenerGeneroDelCURP(curp) {
    if (curp.length >= 11) {
        return curp.charAt(10).toUpperCase() === 'H' ? 'Hombre' : 'Mujer';
    }
    return 'No especificado';
}

// ========== VALIDACIÓN EN TIEMPO REAL ==========

/**
 * Configura la validación en tiempo real para un campo
 * @param {string} idCampo - ID del campo
 * @param {Function} funcionValidacion - Función de validación a aplicar
 */
function configurarValidacionTiempoReal(idCampo, funcionValidacion) {
    const campo = document.getElementById(idCampo);
    if (!campo) return;

    campo.addEventListener('blur', function() {
        const resultado = funcionValidacion(this.value);
        if (resultado.valido) {
            mostrarValido(this);
            camposValidados.set(idCampo, true);
        } else {
            mostrarError(this, resultado.mensaje);
            camposValidados.set(idCampo, false);
        }
    });

    campo.addEventListener('input', function() {
        if (this.classList.contains('is-invalid') || this.classList.contains('is-valid')) {
            const resultado = funcionValidacion(this.value);
            if (resultado.valido) {
                mostrarValido(this);
                camposValidados.set(idCampo, true);
            } else {
                mostrarError(this, resultado.mensaje);
                camposValidados.set(idCampo, false);
            }
        }
    });
}

function configurarValidacionHorarioLaboral() {
    const entrada = document.getElementById('HORA_ENTRADA');
    const salida = document.getElementById('HORA_SALIDA');
    if (!entrada || !salida) return;

    const validar = () => {
        const resultado = validarHorasTrabajo(entrada.value, salida.value);
        if (resultado.valido) {
            mostrarValido(entrada);
            mostrarValido(salida);
        } else {
            mostrarError(entrada, resultado.mensaje);
            mostrarError(salida, resultado.mensaje);
        }
    };

    ['input', 'change', 'blur'].forEach(evt => {
        entrada.addEventListener(evt, validar);
        salida.addEventListener(evt, validar);
    });

    if (entrada.value || salida.value) {
        validar();
    }
}

function configurarValidacionPassword() {
    const pass = document.getElementById('password');
    const passConf = document.getElementById('password_confirm');
    if (!pass || !passConf) return;

    const validar = () => {
        const resultado = validarConfirmacionPassword(pass.value, passConf.value);
        if (resultado.valido) {
            mostrarValido(pass);
            mostrarValido(passConf);
        } else {
            mostrarError(pass, resultado.mensaje);
            mostrarError(passConf, resultado.mensaje);
        }
    };

    ['input', 'change', 'blur'].forEach(evt => {
        pass.addEventListener(evt, validar);
        passConf.addEventListener(evt, validar);
    });
}

/**
 * Inicializa todas las validaciones en tiempo real
 */
function inicializarValidaciones() {
    // Datos del Niño/Niña
    configurarValidacionTiempoReal('APP', validarNombre);
    configurarValidacionTiempoReal('APM', validarNombre);
    configurarValidacionTiempoReal('NOMBRES', validarNombre);
    configurarValidacionTiempoReal('LUGARNINO', validarLugar);
    configurarValidacionTiempoReal('FECHANNINO', validarFechaNacimiento);
    configurarValidacionTiempoReal('CURPNINO', validarCURP);
    configurarValidacionTiempoReal('SANGRE', validarGrupoSanguineo);
    configurarValidacionTiempoReal('CONTACTONINO', validarTelefono);
    configurarValidacionTiempoReal('DOMICILIONINO', validarDomicilio);
    configurarValidacionTiempoReal('CPNINO', validarCodigoPostal);
    configurarValidacionTiempoReal('DOM_ENTIDAD', validarSelect);
    configurarValidacionTiempoReal('DOM_MUNICIPIO', validarSelect);
    
    // Datos del Trabajador/a
    configurarValidacionTiempoReal('APPT', validarNombre);
    configurarValidacionTiempoReal('APMT', validarNombre);
    configurarValidacionTiempoReal('NOMBREST', validarNombre);
    configurarValidacionTiempoReal('trab_lugar_nacimiento', validarLugar);
    configurarValidacionTiempoReal('FECHAT', validarFechaNacimiento);
    configurarValidacionTiempoReal('CURPT', validarCURP);
    configurarValidacionTiempoReal('CIT', validarCorreoInstitucional);
    configurarValidacionTiempoReal('CPT', validarCorreoGeneral);
    configurarValidacionPassword();
    configurarValidacionTiempoReal('NumE', validarNumeroEmpleado);
    configurarValidacionTiempoReal('ESCOLARIDAD', validarEscolaridad);
    configurarValidacionTiempoReal('ADSCRIPCION', validarLugar);
    configurarValidacionHorarioLaboral();
    
    // Bloquear caracteres inválidos en campos de nombre
    bloquearCaracteresInvalidosEnNombre(document.getElementById('APP'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('APM'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('NOMBRES'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('APPT'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('APMT'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('NOMBREST'));
    
    // Bloquear caracteres inválidos en campos de lugar
    bloquearCaracteresInvalidosEnLugar(document.getElementById('LUGARNINO'));
    bloquearCaracteresInvalidosEnLugar(document.getElementById('trab_lugar_nacimiento'));
    bloquearCaracteresInvalidosEnLugar(document.getElementById('ADSCRIPCION'));
    
    // Bloquear caracteres inválidos en teléfonos
    bloquearCaracteresInvalidosEnTelefono(document.getElementById('CONTACTONINO'));
    
    // Bloquear caracteres inválidos en CURP
    bloquearCaracteresInvalidosEnCURP(document.getElementById('CURPNINO'));
    bloquearCaracteresInvalidosEnCURP(document.getElementById('CURPT'));
    
    // Bloquear caracteres inválidos en código postal
    bloquearCaracteresInvalidosEnCodigoPostal(document.getElementById('CPNINO'));
    
    // Bloquear caracteres inválidos en grupo sanguíneo
    bloquearCaracteresInvalidosEnGrupoSanguineo(document.getElementById('SANGRE'));
    
    // Bloquear caracteres inválidos en número de empleado
    bloquearCaracteresInvalidosEnNumeroEmpleado(document.getElementById('NumE'));
    
    // Bloquear caracteres inválidos en emails
    bloquearCaracteresInvalidosEnEmail(document.getElementById('CIT'));
    bloquearCaracteresInvalidosEnEmail(document.getElementById('CPT'));
}

// ========== RECOPILACIÓN DE DATOS ==========

/**
 * Obtiene el valor seleccionado de un grupo de radio buttons
 * @param {string} name - Nombre del grupo
 * @returns {string} - Valor seleccionado
 */
function obtenerValorRadio(name) {
    const radios = document.getElementsByName(name);
    for (let radio of radios) {
        if (radio.checked) {
            return radio.value;
        }
    }
    return '';
}

/**
 * Obtiene el texto de la opción seleccionada en un select
 * @param {string} id - ID del select
 * @returns {string} - Texto de la opción seleccionada
 */
function obtenerTextoSelect(id) {
    const select = document.getElementById(id);
    if (select && select.selectedIndex >= 0) {
        return select.options[select.selectedIndex].text;
    }
    return '';
}

/**
 * Recopila todos los datos del formulario
 * @returns {Object} - Objeto con todos los datos del formulario
 */
function recopilarDatos() {
    const horaEntrada = document.getElementById('HORA_ENTRADA')?.value || '';
    const horaSalida = document.getElementById('HORA_SALIDA')?.value || '';

    const datos = {
        nino: {
            apellidoPaterno: document.getElementById('APP')?.value || '',
            apellidoMaterno: document.getElementById('APM')?.value || '',
            nombres: document.getElementById('NOMBRES')?.value || '',
            lugarNacimiento: document.getElementById('LUGARNINO')?.value || '',
            fechaNacimiento: document.getElementById('FECHANNINO')?.value || '',
            edad: document.getElementById('FECHANNINO')?.value ? calcularEdad(document.getElementById('FECHANNINO').value) : 0,
            curp: document.getElementById('CURPNINO')?.value.toUpperCase() || '',
            grupoSanguineo: document.getElementById('SANGRE')?.value.toUpperCase() || '',
            telefono: document.getElementById('CONTACTONINO')?.value || '',
            grupo: obtenerTextoSelect('GRUPO'),
            domicilio: document.getElementById('DOMICILIONINO')?.value || '',
            alcaldia: obtenerTextoSelect('GRUPO'),
            entidadFederativa: obtenerTextoSelect('enidad'),
            codigoPostal: document.getElementById('CPNINO')?.value || '',
            cendiAdscripcion: obtenerTextoSelect('CENDININO')
        },
        trabajador: {
            apellidoPaterno: document.getElementById('APPT')?.value || '',
            apellidoMaterno: document.getElementById('APMT')?.value || '',
            nombres: document.getElementById('NOMBREST')?.value || '',
            lugarNacimiento: document.getElementById('trab_lugar_nacimiento')?.value || '',
            fechaNacimiento: document.getElementById('FECHAT')?.value || '',
            edad: document.getElementById('FECHAT')?.value ? calcularEdad(document.getElementById('FECHAT').value) : 0,
            curp: document.getElementById('CURPT')?.value.toUpperCase() || '',
            genero: document.getElementById('CURPT')?.value ? obtenerGeneroDelCURP(document.getElementById('CURPT').value) : '',
            correoInstitucional: document.getElementById('CIT')?.value || '',
            correoPersonal: document.getElementById('CPT')?.value || '',
            ocupacion: obtenerTextoSelect('OCUPACION'),
            numeroEmpleado: document.getElementById('NumE')?.value || '',
            escolaridad: document.getElementById('ESCOLARIDAD')?.value || '',
            adscripcion: document.getElementById('ADSCRIPCION')?.value || '',
            horario: horaEntrada && horaSalida ? `${horaEntrada} - ${horaSalida}` : '',
            estadoCivil: obtenerValorRadio('EC')
        }
    };

    return datos;
}

// ========== VALIDACIÓN COMPLETA DEL FORMULARIO ==========

/**
 * Valida todos los campos del formulario
 * @returns {boolean} - True si todos los campos son válidos
 */
function validarFormularioCompleto() {
    let esValido = true;
    const camposAValidar = [
        // Datos del Niño
        { id: 'APP', validador: validarNombre },
        { id: 'APM', validador: validarNombre },
        { id: 'NOMBRES', validador: validarNombre },
        { id: 'LUGARNINO', validador: validarLugar },
        { id: 'FECHANNINO', validador: validarFechaNacimiento },
        { id: 'CURPNINO', validador: validarCURP },
        { id: 'SANGRE', validador: validarGrupoSanguineo },
        { id: 'CONTACTONINO', validador: validarTelefono },
        { id: 'GRUPO', validador: validarSelect },
        { id: 'DOMICILIONINO', validador: validarDomicilio },
        { id: 'DOM_ENTIDAD', validador: validarSelect },
        { id: 'DOM_MUNICIPIO', validador: validarSelect },
        { id: 'CPNINO', validador: validarCodigoPostal },
        { id: 'CENDININO', validador: validarSelect },
        
        // Datos del Trabajador
        { id: 'APPT', validador: validarNombre },
        { id: 'APMT', validador: validarNombre },
        { id: 'NOMBREST', validador: validarNombre },
        { id: 'trab_lugar_nacimiento', validador: validarLugar },
        { id: 'FECHAT', validador: validarFechaNacimiento },
        { id: 'CURPT', validador: validarCURP },
        { id: 'CIT', validador: validarCorreoInstitucional },
        { id: 'CPT', validador: validarCorreoGeneral },
        { id: 'password', validador: valor => validarConfirmacionPassword(valor, document.getElementById('password_confirm')?.value || '') },
        { id: 'password_confirm', validador: valor => validarConfirmacionPassword(document.getElementById('password')?.value || '', valor) },
        { id: 'OCUPACION', validador: validarSelect },
        { id: 'NumE', validador: validarNumeroEmpleado },
        { id: 'ESCOLARIDAD', validador: validarEscolaridad },
        { id: 'ADSCRIPCION', validador: validarLugar }
    ];

    // Validar todos los campos
    camposAValidar.forEach(campo => {
        const elemento = document.getElementById(campo.id);
        if (elemento) {
            const resultado = campo.validador(elemento.value);
            if (!resultado.valido) {
                mostrarError(elemento, resultado.mensaje);
                esValido = false;
            } else {
                mostrarValido(elemento);
            }
        }
    });

    const horaEntradaEl = document.getElementById('HORA_ENTRADA');
    const horaSalidaEl = document.getElementById('HORA_SALIDA');
    const passEl = document.getElementById('password');
    const passConfEl = document.getElementById('password_confirm');
    const resultadoHorario = validarHorasTrabajo(horaEntradaEl?.value || '', horaSalidaEl?.value || '');
    if (horaEntradaEl && horaSalidaEl) {
        if (resultadoHorario.valido) {
            mostrarValido(horaEntradaEl);
            mostrarValido(horaSalidaEl);
        } else {
            mostrarError(horaEntradaEl, resultadoHorario.mensaje);
            mostrarError(horaSalidaEl, resultadoHorario.mensaje);
            esValido = false;
        }
    }

    if (passEl && passConfEl) {
        const resultadoPass = validarConfirmacionPassword(passEl.value, passConfEl.value);
        if (resultadoPass.valido) {
            mostrarValido(passEl);
            mostrarValido(passConfEl);
        } else {
            mostrarError(passEl, resultadoPass.mensaje);
            mostrarError(passConfEl, resultadoPass.mensaje);
            esValido = false;
        }
    }

    // Validar estado civil (radio buttons)
    const resultadoEC = validarRadioGroup('EC');
    if (!resultadoEC.valido) {
        const radioContainer = document.querySelector('input[name="EC"]')?.parentElement?.parentElement;
        if (radioContainer) {
            // Crear o actualizar mensaje de error para radio buttons
            let errorMsg = radioContainer.querySelector('.text-danger');
            if (!errorMsg) {
                errorMsg = document.createElement('div');
                errorMsg.className = 'text-danger small mt-1';
                radioContainer.appendChild(errorMsg);
            }
            errorMsg.textContent = resultadoEC.mensaje;
        }
        esValido = false;
    }

    return esValido;
}

// ========== MANEJO DEL FORMULARIO ==========

/**
 * Maneja el envío del formulario de inscripción
 * @param {Event} event - Evento del formulario
 */
function manejarEnvioFormulario(event) {
    // Validar todos los campos
    const formularioValido = validarFormularioCompleto();

    if (!formularioValido) {
        mostrarAviso('Por favor, corrige los errores marcados antes de continuar.', 'danger');
        event.preventDefault();
        const primerError = document.querySelector('.is-invalid');
        if (primerError) {
            primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            primerError.focus();
        }
        return;
    }

    // Todo válido: permitir envío normal al servidor (PHP manejará confirmación)
    limpiarAviso();
}

/**
 * Limpia todos los campos del formulario
 */
function limpiarFormulario() {
    if (!formulario) return;
    formulario.reset();

    const campos = formulario.querySelectorAll('.form-control, .form-select');
    campos.forEach(campo => limpiarValidacion(campo));

    camposValidados.clear();

    const otroContainer = document.getElementById('otro-container');
    if (otroContainer) {
        otroContainer.style.display = 'none';
    }

    document.querySelectorAll('.text-danger.small').forEach(msg => msg.remove());
    limpiarAviso();
}

/**
 * Muestra el resumen de datos ingresados
 * @param {Object} datos - Datos del formulario
 */
// Ya no se usa modal de confirmación; la confirmación se hará del lado servidor.
function mostrarResumen() {
    return;
}

// ========== INICIALIZACIÓN ==========

/**
 * Inicializa el formulario cuando el DOM está listo
 */
document.addEventListener('DOMContentLoaded', function() {
    // Obtener referencia al formulario
    formulario = document.querySelector('form');
    
    if (formulario) {
        // Configurar validaciones en tiempo real
        inicializarValidaciones();
        
        // Agregar event listener para el envío del formulario
        formulario.addEventListener('submit', manejarEnvioFormulario);
        
        // Agregar event listener para el botón de limpiar
        const botonLimpiar = formulario.querySelector('button[type="reset"]');
        if (botonLimpiar) {
            botonLimpiar.addEventListener('click', function(e) {
                e.preventDefault();
                limpiarFormulario();
            });
        }
    }
    
    console.log('Sistema de Inscripción CENDI - Formulario inicializado');
});

function mostrarAviso(texto, tipo = 'info') {
    if (!formulario) return;
    let alertBox = document.getElementById('form-alert');
    if (!alertBox) {
        alertBox = document.createElement('div');
        alertBox.id = 'form-alert';
        formulario.parentElement.insertBefore(alertBox, formulario);
    }
    alertBox.className = `alert alert-${tipo}`;
    alertBox.textContent = texto;
}

function limpiarAviso() {
    const alertBox = document.getElementById('form-alert');
    if (alertBox) alertBox.remove();
}

