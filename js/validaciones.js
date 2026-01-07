/**
 * Sistema de Inscripción CENDI - IPN
 * Equipo 4 - Tecnologías para la Web
 * Archivo de validaciones con expresiones regulares
 */

// ========== EXPRESIONES REGULARES ==========


/**
 * Validación de nombres: solo letras, espacios y acentos
 */
const regexNombre = /^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]{2,50}$/;

/**
 * Validación de teléfono: 10 dígitos
 */
const regexTelefono = /^\d{10}$/;

/**
 * Validación de CURP: 18 caracteres
 * - 4 primeras letras
 * - 6 siguientes números (AAMMDD)
 * - 6 siguientes letras
 * - 2 últimos alfanuméricos
 */
const regexCURP = /^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/;

/**
 * Validación de correo electrónico institucional del IPN
 * Debe terminar en @ipn.mx
 */
const regexCorreoInstitucional = /^[a-zA-Z0-9._-]+@ipn\.mx$/;

/**
 * Validación de correo electrónico general
 */
const regexCorreoGeneral = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

/**
 * Validación de contraseña:
 * - Mínimo 8 caracteres
 * - Al menos una mayúscula
 * - Al menos una minúscula
 * - Al menos un dígito
 * - Al menos un carácter especial
 */
const regexPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#._-])[A-Za-z\d@$!%*?&#._-]{8,}$/;

/**
 * Validación de código postal: 5 dígitos
 */
const regexCodigoPostal = /^\d{5}$/;

/**
 * Validación de grupo sanguíneo: A, B, AB u O con Rh +/-
 */
const regexGrupoSanguineo = /^(A|B|AB|O)[+-]$/;

/**
 * Validación de número de empleado: alfanumérico
 */
const regexNumeroEmpleado = /^[0-9]{5,10}$/i;

/**
 * Validación de domicilio: letras, números, espacios y caracteres especiales
 */
const regexDomicilio = /^[a-zA-Z0-9áéíóúñÁÉÍÓÚÑ\s,#.-]{5,100}$/;

/**
 * Validación de lugar: solo letras, espacios y acentos
 */
const regexLugar = /^[a-záéíóúñA-ZÁÉÍÓÚÑ\s,.-]{3,50}$/;

/**
 * Validación de hora: formato HH:MM de 24 horas
 */
const regexHora24 = /^([01]\d|2[0-3]):[0-5]\d$/;

// ========== FUNCIONES DE VALIDACIÓN ==========

/**
 * Valida un campo de nombre (apellidos o nombres)
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarNombre(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'Este campo es obligatorio' };
    }
    if (!regexNombre.test(valor.trim())) {
        return { valido: false, mensaje: 'Solo se permiten letras y espacios (2-50 caracteres)' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Bloquea la entrada de caracteres no permitidos en campos de nombre
 * Muestra un tooltip temporal cuando se intenta escribir un carácter inválido
 * @param {HTMLElement} campo - Elemento del campo de entrada
 */
function bloquearCaracteresInvalidosEnNombre(campo) {
    if (!campo) return;
    
    // Crear tooltip si no existe
    let tooltip = campo.parentElement.querySelector('.tooltip-caracteres-invalidos');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-caracteres-invalidos';
        tooltip.style.cssText = `
            position: absolute;
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            white-space: nowrap;
            animation: fadeInOut 2s ease-in-out;
        `;
        tooltip.textContent = '⚠️ Solo se permiten letras y espacios';
        campo.parentElement.style.position = 'relative';
        campo.parentElement.appendChild(tooltip);
        
        // Agregar animación CSS
        if (!document.getElementById('tooltip-animation-styles')) {
            const style = document.createElement('style');
            style.id = 'tooltip-animation-styles';
            style.textContent = `
                @keyframes fadeInOut {
                    0% { opacity: 0; transform: translateY(-5px); }
                    15% { opacity: 1; transform: translateY(0); }
                    85% { opacity: 1; transform: translateY(0); }
                    100% { opacity: 0; transform: translateY(-5px); }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Evento para bloquear caracteres inválidos
    campo.addEventListener('keypress', function(e) {
        const char = e.key;
        // Permitir teclas especiales (backspace, delete, tab, enter, etc.)
        if (e.ctrlKey || e.altKey || e.metaKey || 
            char === 'Backspace' || char === 'Delete' || 
            char === 'Tab' || char === 'Enter' || char === 'ArrowLeft' || char === 'ArrowRight') {
            return;
        }
        
        // Verificar si es letra, espacio o acento válido
        const esValido = /^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]$/.test(char);
        
        if (!esValido) {
            e.preventDefault(); // Bloquear la entrada
            
            // Mostrar tooltip
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            
            // Ocultar tooltip después de 2 segundos
            setTimeout(() => {
                tooltip.style.display = 'none';
            }, 2000);
        }
    });
    
    // También bloquear al pegar
    campo.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        // Filtrar solo caracteres válidos
        const textoLimpio = texto.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, '');
        
        if (texto !== textoLimpio) {
            // Mostrar tooltip si se filtraron caracteres
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => {
                tooltip.style.display = 'none';
            }, 2000);
        }
        
        // Insertar el texto limpio
        const inicio = this.selectionStart;
        const fin = this.selectionEnd;
        const valorActual = this.value;
        this.value = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
        this.selectionStart = this.selectionEnd = inicio + textoLimpio.length;
        
        // Disparar evento input para actualizar validaciones
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

/**
 * Valida un teléfono de 10 dígitos
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarTelefono(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'El teléfono es obligatorio' };
    }
    if (!regexTelefono.test(valor.trim())) {
        return { valido: false, mensaje: 'El teléfono debe tener exactamente 10 dígitos' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida un CURP de 18 caracteres
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarCURP(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'El CURP es obligatorio' };
    }
    const curpUpper = valor.trim().toUpperCase();
    if (curpUpper.length !== 18) {
        return { valido: false, mensaje: 'El CURP debe tener exactamente 18 caracteres' };
    }
    if (!regexCURP.test(curpUpper)) {
        return { valido: false, mensaje: 'El formato del CURP no es válido' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida correo institucional del IPN
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarCorreoInstitucional(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'El correo institucional es obligatorio' };
    }
    if (!regexCorreoInstitucional.test(valor.trim().toLowerCase())) {
        return { valido: false, mensaje: 'Debe ser un correo institucional válido (@ipn.mx)' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida correo electrónico general
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarCorreoGeneral(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'El correo personal es obligatorio' };
    }
    if (!regexCorreoGeneral.test(valor.trim().toLowerCase())) {
        return { valido: false, mensaje: 'El formato del correo no es válido' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida contraseña segura
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarPassword(valor) {
    if (!valor || valor.length === 0) {
        return { valido: false, mensaje: 'La contraseña es obligatoria' };
    }
    if (valor.length < 8) {
        return { valido: false, mensaje: 'La contraseña debe tener al menos 8 caracteres' };
    }
    if (!regexPassword.test(valor)) {
        return { valido: false, mensaje: 'Debe incluir mayúscula, minúscula, número y carácter especial' };
    }
    return { valido: true, mensaje: '' };
}

function validarConfirmacionPassword(pass, confirmPass) {
    const base = validarPassword(pass);
    if (!base.valido) return base;
    if (!confirmPass || confirmPass.length === 0) {
        return { valido: false, mensaje: 'Confirma la contraseña' };
    }
    if (pass !== confirmPass) {
        return { valido: false, mensaje: 'La confirmación no coincide' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida código postal de 5 dígitos
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarCodigoPostal(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'El código postal es obligatorio' };
    }
    if (!regexCodigoPostal.test(valor.trim())) {
        return { valido: false, mensaje: 'El código postal debe tener exactamente 5 dígitos' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida grupo sanguíneo
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarGrupoSanguineo(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'El grupo sanguíneo es obligatorio' };
    }
    const grupoUpper = valor.trim().toUpperCase();
    if (!regexGrupoSanguineo.test(grupoUpper)) {
        return { valido: false, mensaje: 'Formato inválido. Ejemplos válidos: A+, B-, AB+, O-' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida fecha de nacimiento (debe ser una fecha pasada)
 * @param {string} valor - Valor a validar (formato YYYY-MM-DD)
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarFechaNacimiento(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'La fecha de nacimiento es obligatoria' };
    }
    const fecha = new Date(valor);
    const hoy = new Date();
    
    if (isNaN(fecha.getTime())) {
        return { valido: false, mensaje: 'La fecha no es válida' };
    }
    if (fecha >= hoy) {
        return { valido: false, mensaje: 'La fecha debe ser anterior a hoy' };
    }
    // Validar que no sea una fecha muy antigua (más de 120 años)
    const hace120Years = new Date();
    hace120Years.setFullYear(hace120Years.getFullYear() - 120);
    if (fecha < hace120Years) {
        return { valido: false, mensaje: 'La fecha no puede ser mayor a 120 años' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida que un select tenga una opción seleccionada
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarSelect(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'Debe seleccionar una opción' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida un domicilio
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarDomicilio(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'El domicilio es obligatorio' };
    }
    if (!regexDomicilio.test(valor.trim())) {
        return { valido: false, mensaje: 'El domicilio debe tener entre 5 y 100 caracteres válidos' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida un lugar (ciudad, estado, etc.)
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarLugar(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'Este campo es obligatorio' };
    }
    if (!regexLugar.test(valor.trim())) {
        return { valido: false, mensaje: 'Solo se permiten letras, espacios y puntuación (3-50 caracteres)' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida número de empleado
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarNumeroEmpleado(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'El número de empleado es obligatorio' };
    }
    if (!regexNumeroEmpleado.test(valor.trim())) {
        return { valido: false, mensaje: 'Debe ser numérico de 5 a 10 dígitos' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida horario laboral con hora de entrada y salida
 * @param {string} horaEntrada
 * @param {string} horaSalida
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarHorasTrabajo(horaEntrada, horaSalida) {
    if (!horaEntrada || !horaSalida) {
        return { valido: false, mensaje: 'Debes capturar hora de entrada y salida' };
    }

    if (!regexHora24.test(horaEntrada.trim()) || !regexHora24.test(horaSalida.trim())) {
        return { valido: false, mensaje: 'Usa formato de 24 horas HH:MM' };
    }

    const [hEnt, mEnt] = horaEntrada.split(':').map(Number);
    const [hSal, mSal] = horaSalida.split(':').map(Number);
    const entradaMin = (hEnt * 60) + mEnt;
    const salidaMin = (hSal * 60) + mSal;
    const diffMin = salidaMin - entradaMin;

    if (diffMin <= 0) {
        return { valido: false, mensaje: 'La salida debe ser posterior a la entrada' };
    }
    if (diffMin !== 480) {
        return { valido: false, mensaje: 'Debe haber exactamente 8 horas entre entrada y salida' };
    }

    return { valido: true, mensaje: '' };
}

/**
 * Valida que al menos un radio button esté seleccionado
 * @param {string} name - Nombre del grupo de radio buttons
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarRadioGroup(name) {
    const radios = document.getElementsByName(name);
    let seleccionado = false;
    for (let i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            seleccionado = true;
            break;
        }
    }
    if (!seleccionado) {
        return { valido: false, mensaje: 'Debe seleccionar una opción' };
    }
    return { valido: true, mensaje: '' };
}

/**
 * Valida campo de escolaridad (texto libre)
 * @param {string} valor - Valor a validar
 * @returns {object} - {valido: boolean, mensaje: string}
 */
function validarEscolaridad(valor) {
    if (!valor || valor.trim().length === 0) {
        return { valido: false, mensaje: 'La escolaridad es obligatoria' };
    }
    if (valor.trim().length < 3 || valor.trim().length > 100) {
        return { valido: false, mensaje: 'Debe tener entre 3 y 100 caracteres' };
    }
    return { valido: true, mensaje: '' };
}

// ========== FUNCIONES AUXILIARES ==========

/**
 * Muestra un mensaje de error en un campo
 * @param {HTMLElement} campo - Elemento del campo
 * @param {string} mensaje - Mensaje de error
 */
function mostrarError(campo, mensaje) {
    campo.classList.add('is-invalid');
    campo.classList.remove('is-valid');
    
    // Buscar o crear div de feedback
    let feedback = campo.parentElement.querySelector('.invalid-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        campo.parentElement.appendChild(feedback);
    }
    feedback.textContent = mensaje;
}

/**
 * Muestra que un campo es válido
 * @param {HTMLElement} campo - Elemento del campo
 */
function mostrarValido(campo) {
    campo.classList.add('is-valid');
    campo.classList.remove('is-invalid');
    
    // Remover mensaje de error si existe
    const feedback = campo.parentElement.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = '';
    }
}

/**
 * Limpia la validación de un campo
 * @param {HTMLElement} campo - Elemento del campo
 */
function limpiarValidacion(campo) {
    campo.classList.remove('is-valid', 'is-invalid');
    const feedback = campo.parentElement.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = '';
    }
}

/**
 * Bloquea la entrada de caracteres no permitidos en campos de lugar
 * Muestra un tooltip temporal cuando se intenta escribir un carácter inválido
 * @param {HTMLElement} campo - Elemento del campo de entrada
 */
function bloquearCaracteresInvalidosEnLugar(campo) {
    if (!campo) return;
    
    // Crear tooltip si no existe
    let tooltip = campo.parentElement.querySelector('.tooltip-caracteres-invalidos');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-caracteres-invalidos';
        tooltip.style.cssText = `
            position: absolute;
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            white-space: nowrap;
            animation: fadeInOut 2s ease-in-out;
        `;
        tooltip.textContent = '⚠️ Solo se permiten letras, espacios y puntuación';
        campo.parentElement.style.position = 'relative';
        campo.parentElement.appendChild(tooltip);
    }
    
    // Evento para bloquear caracteres inválidos
    campo.addEventListener('keypress', function(e) {
        const char = e.key;
        if (e.ctrlKey || e.altKey || e.metaKey || 
            char === 'Backspace' || char === 'Delete' || 
            char === 'Tab' || char === 'Enter' || char === 'ArrowLeft' || char === 'ArrowRight') {
            return;
        }
        
        const esValido = /^[a-záéíóúñA-ZÁÉÍÓÚÑ\s,.-]$/.test(char);
        
        if (!esValido) {
            e.preventDefault();
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
    });
    
    campo.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const textoLimpio = texto.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s,.-]/g, '');
        
        if (texto !== textoLimpio) {
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        const inicio = this.selectionStart;
        const fin = this.selectionEnd;
        const valorActual = this.value;
        this.value = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
        this.selectionStart = this.selectionEnd = inicio + textoLimpio.length;
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

/**
 * Bloquea la entrada de caracteres no permitidos en número de empleado
 * Muestra un tooltip temporal cuando se intenta escribir un carácter inválido
 * @param {HTMLElement} campo - Elemento del campo de entrada
 */
function bloquearCaracteresInvalidosEnNumeroEmpleado(campo) {
    if (!campo) return;
    
    let tooltip = campo.parentElement.querySelector('.tooltip-caracteres-invalidos');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-caracteres-invalidos';
        tooltip.style.cssText = `
            position: absolute;
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            white-space: nowrap;
            animation: fadeInOut 2s ease-in-out;
        `;
        tooltip.textContent = '⚠️ Solo se permiten letras y números';
        campo.parentElement.style.position = 'relative';
        campo.parentElement.appendChild(tooltip);
    }
    
    campo.addEventListener('keypress', function(e) {
        const char = e.key;
        if (e.ctrlKey || e.altKey || e.metaKey || 
            char === 'Backspace' || char === 'Delete' || 
            char === 'Tab' || char === 'Enter' || char === 'ArrowLeft' || char === 'ArrowRight') {
            return;
        }
        
        const esValido = /^[a-zA-Z0-9]$/.test(char);
        
        if (!esValido) {
            e.preventDefault();
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
    });
    
    campo.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const textoLimpio = texto.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        
        if (texto !== textoLimpio) {
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        const inicio = this.selectionStart;
        const fin = this.selectionEnd;
        const valorActual = this.value;
        this.value = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
        this.selectionStart = this.selectionEnd = inicio + textoLimpio.length;
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

/**
 * Bloquea la entrada de caracteres no permitidos en campos de email
 * Muestra un tooltip temporal cuando se intenta escribir un carácter inválido
 * @param {HTMLElement} campo - Elemento del campo de entrada
 */
function bloquearCaracteresInvalidosEnEmail(campo) {
    if (!campo) return;
    
    let tooltip = campo.parentElement.querySelector('.tooltip-caracteres-invalidos');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-caracteres-invalidos';
        tooltip.style.cssText = `
            position: absolute;
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            white-space: nowrap;
            animation: fadeInOut 2s ease-in-out;
        `;
        tooltip.textContent = '⚠️ Caracteres no válidos para email';
        campo.parentElement.style.position = 'relative';
        campo.parentElement.appendChild(tooltip);
    }
    
    campo.addEventListener('keypress', function(e) {
        const char = e.key;
        if (e.ctrlKey || e.altKey || e.metaKey || 
            char === 'Backspace' || char === 'Delete' || 
            char === 'Tab' || char === 'Enter' || char === 'ArrowLeft' || char === 'ArrowRight') {
            return;
        }
        
        const esValido = /^[a-zA-Z0-9@._-]$/.test(char);
        
        if (!esValido) {
            e.preventDefault();
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
    });
    
    campo.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const textoLimpio = texto.replace(/[^a-zA-Z0-9@._-]/g, '').toLowerCase();
        
        if (texto !== textoLimpio) {
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        const inicio = this.selectionStart;
        const fin = this.selectionEnd;
        const valorActual = this.value;
        this.value = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
        this.selectionStart = this.selectionEnd = inicio + textoLimpio.length;
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

/**
 * Bloquea la entrada de caracteres no permitidos en campos de teléfono
 * Muestra un tooltip temporal cuando se intenta escribir un carácter inválido
 * @param {HTMLElement} campo - Elemento del campo de entrada
 */
function bloquearCaracteresInvalidosEnTelefono(campo) {
    if (!campo) return;
    
    let tooltip = campo.parentElement.querySelector('.tooltip-caracteres-invalidos');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-caracteres-invalidos';
        tooltip.style.cssText = `
            position: absolute;
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            white-space: nowrap;
            animation: fadeInOut 2s ease-in-out;
        `;
        tooltip.textContent = '⚠️ Solo se permiten números (10 dígitos)';
        campo.parentElement.style.position = 'relative';
        campo.parentElement.appendChild(tooltip);
    }
    
    campo.addEventListener('keypress', function(e) {
        const char = e.key;
        if (e.ctrlKey || e.altKey || e.metaKey || 
            char === 'Backspace' || char === 'Delete' || 
            char === 'Tab' || char === 'Enter' || char === 'ArrowLeft' || char === 'ArrowRight') {
            return;
        }
        
        const esValido = /^\d$/.test(char);
        
        if (!esValido) {
            e.preventDefault();
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        // Limitar a 10 dígitos
        if (this.value.length >= 10) {
            e.preventDefault();
        }
    });
    
    campo.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const textoLimpio = texto.replace(/\D/g, '').substring(0, 10);
        
        if (texto !== textoLimpio) {
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        const inicio = this.selectionStart;
        const fin = this.selectionEnd;
        const valorActual = this.value;
        const nuevoValor = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
        this.value = nuevoValor.substring(0, 10); // Limitar a 10 dígitos
        this.selectionStart = this.selectionEnd = Math.min(inicio + textoLimpio.length, 10);
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

/**
 * Bloquea la entrada de caracteres no permitidos en CURP
 * Muestra un tooltip temporal cuando se intenta escribir un carácter inválido
 * @param {HTMLElement} campo - Elemento del campo de entrada
 */
function bloquearCaracteresInvalidosEnCURP(campo) {
    if (!campo) return;
    
    let tooltip = campo.parentElement.querySelector('.tooltip-caracteres-invalidos');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-caracteres-invalidos';
        tooltip.style.cssText = `
            position: absolute;
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            white-space: nowrap;
            animation: fadeInOut 2s ease-in-out;
        `;
        tooltip.textContent = '⚠️ Solo letras y números (18 caracteres)';
        campo.parentElement.style.position = 'relative';
        campo.parentElement.appendChild(tooltip);
    }
    
    campo.addEventListener('keypress', function(e) {
        const char = e.key;
        if (e.ctrlKey || e.altKey || e.metaKey || 
            char === 'Backspace' || char === 'Delete' || 
            char === 'Tab' || char === 'Enter' || char === 'ArrowLeft' || char === 'ArrowRight') {
            return;
        }
        
        const esValido = /^[a-zA-Z0-9]$/.test(char);
        
        if (!esValido) {
            e.preventDefault();
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        // Limitar a 18 caracteres
        if (this.value.length >= 18) {
            e.preventDefault();
        }
    });
    
    campo.addEventListener('input', function() {
        // Convertir a mayúsculas automáticamente
        this.value = this.value.toUpperCase();
    });
    
    campo.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const textoLimpio = texto.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().substring(0, 18);
        
        if (texto.toUpperCase() !== textoLimpio) {
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        const inicio = this.selectionStart;
        const fin = this.selectionEnd;
        const valorActual = this.value;
        const nuevoValor = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
        this.value = nuevoValor.substring(0, 18);
        this.selectionStart = this.selectionEnd = Math.min(inicio + textoLimpio.length, 18);
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

/**
 * Bloquea la entrada de caracteres no permitidos en código postal
 * Muestra un tooltip temporal cuando se intenta escribir un carácter inválido
 * @param {HTMLElement} campo - Elemento del campo de entrada
 */
function bloquearCaracteresInvalidosEnCodigoPostal(campo) {
    if (!campo) return;
    
    let tooltip = campo.parentElement.querySelector('.tooltip-caracteres-invalidos');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-caracteres-invalidos';
        tooltip.style.cssText = `
            position: absolute;
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            white-space: nowrap;
            animation: fadeInOut 2s ease-in-out;
        `;
        tooltip.textContent = '⚠️ Solo se permiten números (5 dígitos)';
        campo.parentElement.style.position = 'relative';
        campo.parentElement.appendChild(tooltip);
    }
    
    campo.addEventListener('keypress', function(e) {
        const char = e.key;
        if (e.ctrlKey || e.altKey || e.metaKey || 
            char === 'Backspace' || char === 'Delete' || 
            char === 'Tab' || char === 'Enter' || char === 'ArrowLeft' || char === 'ArrowRight') {
            return;
        }
        
        const esValido = /^\d$/.test(char);
        
        if (!esValido) {
            e.preventDefault();
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        // Limitar a 5 dígitos
        if (this.value.length >= 5) {
            e.preventDefault();
        }
    });
    
    campo.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const textoLimpio = texto.replace(/\D/g, '').substring(0, 5);
        
        if (texto !== textoLimpio) {
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        const inicio = this.selectionStart;
        const fin = this.selectionEnd;
        const valorActual = this.value;
        const nuevoValor = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
        this.value = nuevoValor.substring(0, 5);
        this.selectionStart = this.selectionEnd = Math.min(inicio + textoLimpio.length, 5);
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
}

/**
 * Bloquea la entrada de caracteres no permitidos en grupo sanguíneo
 * Muestra un tooltip temporal cuando se intenta escribir un carácter inválido
 * @param {HTMLElement} campo - Elemento del campo de entrada
 */
function bloquearCaracteresInvalidosEnGrupoSanguineo(campo) {
    if (!campo) return;
    
    let tooltip = campo.parentElement.querySelector('.tooltip-caracteres-invalidos');
    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip-caracteres-invalidos';
        tooltip.style.cssText = `
            position: absolute;
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            white-space: nowrap;
            animation: fadeInOut 2s ease-in-out;
        `;
        tooltip.textContent = '⚠️ Formato: A+, B-, AB+, O-';
        campo.parentElement.style.position = 'relative';
        campo.parentElement.appendChild(tooltip);
    }
    
    campo.addEventListener('keypress', function(e) {
        const char = e.key;
        if (e.ctrlKey || e.altKey || e.metaKey || 
            char === 'Backspace' || char === 'Delete' || 
            char === 'Tab' || char === 'Enter' || char === 'ArrowLeft' || char === 'ArrowRight') {
            return;
        }
        
        const esValido = /^[ABOabo+\-]$/.test(char);
        
        if (!esValido) {
            e.preventDefault();
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        // Limitar a 3 caracteres
        if (this.value.length >= 3) {
            e.preventDefault();
        }
    });
    
    campo.addEventListener('input', function() {
        // Convertir a mayúsculas automáticamente
        this.value = this.value.toUpperCase();
    });
    
    campo.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const textoLimpio = texto.replace(/[^ABOabo+\-]/g, '').toUpperCase().substring(0, 3);
        
        if (texto.toUpperCase() !== textoLimpio) {
            tooltip.style.display = 'block';
            tooltip.style.left = '0';
            tooltip.style.top = '-35px';
            setTimeout(() => { tooltip.style.display = 'none'; }, 2000);
        }
        
        const inicio = this.selectionStart;
        const fin = this.selectionEnd;
        const valorActual = this.value;
        const nuevoValor = valorActual.substring(0, inicio) + textoLimpio + valorActual.substring(fin);
        this.value = nuevoValor.substring(0, 3);
        this.selectionStart = this.selectionEnd = Math.min(inicio + textoLimpio.length, 3);
        this.dispatchEvent(new Event('input', { bubbles: true }));
    });
}










$(document).ready(function () {
    console.log("validaciones.js cargado correctamente");

    // === APLICAR BLOQUEOS Y VALIDACIONES EN TIEMPO REAL ===

    // --- Campos del Niño/a ---
    bloquearCaracteresInvalidosEnNombre(document.getElementById('APP'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('APM'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('NOMBRES'));

    bloquearCaracteresInvalidosEnLugar(document.getElementById('LUGARNINO'));
    bloquearCaracteresInvalidosEnCURP(document.getElementById('CURPNINO'));
    bloquearCaracteresInvalidosEnTelefono(document.getElementById('CONTACTONINO'));
    bloquearCaracteresInvalidosEnGrupoSanguineo(document.getElementById('SANGRE'));
    bloquearCaracteresInvalidosEnCodigoPostal(document.getElementById('CPNINO'));
    bloquearCaracteresInvalidosEnLugar(document.getElementById('DOMICILIONINO')); // o usar regexDomicilio si quieres más permisivo

    // --- Campos de la Trabajadora ---
    bloquearCaracteresInvalidosEnNombre(document.getElementById('APPT'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('APMT'));
    bloquearCaracteresInvalidosEnNombre(document.getElementById('NOMBREST'));

    bloquearCaracteresInvalidosEnLugar(document.getElementById('trab_lugar_nacimiento'));
    bloquearCaracteresInvalidosEnCURP(document.getElementById('CURPT'));
    bloquearCaracteresInvalidosEnEmail(document.getElementById('CIT'));
    bloquearCaracteresInvalidosEnEmail(document.getElementById('CPT'));
    bloquearCaracteresInvalidosEnNumeroEmpleado(document.getElementById('NumE'));

    // Validación en tiempo real (al escribir o cambiar)
    $('input, select').on('input change', function() {
        const id = this.id;
        const valor = this.value;

        let resultado;

        switch(id) {
            // Niño/a
            case 'APP':
            case 'APM':
            case 'NOMBRES':
                resultado = validarNombre(valor);
                break;
            case 'CONTACTONINO':
                resultado = validarTelefono(valor);
                break;
            case 'CURPNINO':
                resultado = validarCURP(valor);
                break;
            case 'SANGRE':
                resultado = validarGrupoSanguineo(valor);
                break;
            case 'CPNINO':
                resultado = validarCodigoPostal(valor);
                break;
            case 'DOMICILIONINO':
                resultado = validarDomicilio(valor);
                break;
            case 'LUGARNINO':
                resultado = validarLugar(valor);
                break;

            // Trabajadora
            case 'APPT':
            case 'APMT':
            case 'NOMBREST':
                resultado = validarNombre(valor);
                break;
            case 'trab_lugar_nacimiento':
                resultado = validarLugar(valor);
                break;
            case 'CURPT':
                resultado = validarCURP(valor);
                break;
            case 'CIT':
                resultado = validarCorreoInstitucional(valor);
                break;
            case 'CPT':
                resultado = validarCorreoGeneral(valor);
                break;
            case 'password':
            case 'password_confirm':
                resultado = validarConfirmacionPassword(
                    document.getElementById('password')?.value || '',
                    document.getElementById('password_confirm')?.value || ''
                );
                break;
            case 'NumE':
                resultado = validarNumeroEmpleado(valor);
                break;
            case 'HORA_ENTRADA':
            case 'HORA_SALIDA':
                resultado = validarHorasTrabajo(
                    document.getElementById('HORA_ENTRADA')?.value || '',
                    document.getElementById('HORA_SALIDA')?.value || ''
                );
                break;
            case 'ESCOLARIDAD':
                resultado = validarEscolaridad(valor);
                break;
            case 'ADSCRIPCION':
                resultado = validarLugar(valor); // o crea una específica
                break;

            // Selects
            case 'GRUPO':
            case 'enidad':
            case 'CENDININO':
            case 'OCUPACION':
                resultado = validarSelect(valor);
                break;

            // Fechas
            case 'FECHANNINO':
            case 'FECHAT':
                resultado = validarFechaNacimiento(valor);
                break;

            default:
                return;
        }

        if (id === 'HORA_ENTRADA' || id === 'HORA_SALIDA') {
            const entrada = document.getElementById('HORA_ENTRADA');
            const salida = document.getElementById('HORA_SALIDA');

            if (resultado.valido) {
                mostrarValido(entrada);
                mostrarValido(salida);
            } else {
                mostrarError(entrada, resultado.mensaje);
                mostrarError(salida, resultado.mensaje);
            }
            return;
        }

        if (id === 'password' || id === 'password_confirm') {
            const pass = document.getElementById('password');
            const passConf = document.getElementById('password_confirm');
            if (resultado.valido) {
                mostrarValido(pass);
                mostrarValido(passConf);
            } else {
                mostrarError(pass, resultado.mensaje);
                mostrarError(passConf, resultado.mensaje);
            }
            return;
        }

        if (resultado.valido) {
            mostrarValido(this);
        } else {
            mostrarError(this, resultado.mensaje);
        }
    });

});