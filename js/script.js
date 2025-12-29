/**
 * Sistema de Inscripción CENDI - IPN
 * Equipo 4 - Tecnologías para la Web
 * Script general para funcionalidades compartidas
 */

// ========== CONSTANTES ==========
const NOMBRE_SISTEMA = 'Sistema de Inscripción CENDI - IPN';
const VERSION = '1.0.0';
const EQUIPO = 'Equipo 4';

// ========== FUNCIONES GLOBALES ==========

/**
 * Muestra información del sistema en la consola
 */
function mostrarInfoSistema() {
    console.log('═══════════════════════════════════════');
    console.log(`${NOMBRE_SISTEMA}`);
    console.log(`Versión: ${VERSION}`);
    console.log(`Desarrollado por: ${EQUIPO}`);
    console.log(`Materia: Tecnologías para la Web`);
    console.log('═══════════════════════════════════════');
}

/**
 * Formatea una fecha en formato legible en español
 * @param {Date|string} fecha - Fecha a formatear
 * @returns {string} - Fecha formateada
 */
function formatearFecha(fecha) {
    const f = typeof fecha === 'string' ? new Date(fecha) : fecha;
    const opciones = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    return f.toLocaleDateString('es-MX', opciones);
}

/**
 * Formatea un teléfono a formato (XXX) XXX-XXXX
 * @param {string} telefono - Número de teléfono de 10 dígitos
 * @returns {string} - Teléfono formateado
 */
function formatearTelefono(telefono) {
    const limpio = telefono.replace(/\D/g, '');
    if (limpio.length === 10) {
        return `(${limpio.substring(0, 3)}) ${limpio.substring(3, 6)}-${limpio.substring(6)}`;
    }
    return telefono;
}

/**
 * Capitaliza la primera letra de cada palabra
 * @param {string} texto - Texto a capitalizar
 * @returns {string} - Texto capitalizado
 */
function capitalizarTexto(texto) {
    return texto
        .toLowerCase()
        .split(' ')
        .map(palabra => palabra.charAt(0).toUpperCase() + palabra.slice(1))
        .join(' ');
}

/**
 * Sanitiza un string para evitar inyección de código
 * @param {string} texto - Texto a sanitizar
 * @returns {string} - Texto sanitizado
 */
function sanitizarTexto(texto) {
    const elemento = document.createElement('div');
    elemento.textContent = texto;
    return elemento.innerHTML;
}

/**
 * Muestra un mensaje de carga
 * @param {string} mensaje - Mensaje a mostrar
 */
function mostrarCargando(mensaje = 'Cargando...') {
    console.log(`⏳ ${mensaje}`);
}

/**
 * Muestra un mensaje de éxito
 * @param {string} mensaje - Mensaje a mostrar
 */
function mostrarExito(mensaje) {
    console.log(`✅ ${mensaje}`);
}

/**
 * Muestra un mensaje de error
 * @param {string} mensaje - Mensaje a mostrar
 */
function mostrarErrorConsola(mensaje) {
    console.error(` ${mensaje}`);
}

/**
 * Valida que un valor no esté vacío
 * @param {any} valor - Valor a validar
 * @returns {boolean} - True si no está vacío
 */
function noVacio(valor) {
    return valor !== null && valor !== undefined && valor.toString().trim() !== '';
}

/**
 * Debounce - Retrasa la ejecución de una función
 * @param {Function} func - Función a ejecutar
 * @param {number} wait - Tiempo de espera en ms
 * @returns {Function} - Función con debounce
 */
function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Convierte un objeto a query string
 * @param {Object} obj - Objeto a convertir
 * @returns {string} - Query string
 */
function objetoAQueryString(obj) {
    return Object.keys(obj)
        .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(obj[key])}`)
        .join('&');
}

/**
 * Genera un ID único
 * @returns {string} - ID único
 */
function generarIdUnico() {
    return `${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
}

/**
 * Guarda datos en localStorage de forma segura
 * @param {string} clave - Clave del dato
 * @param {any} valor - Valor a guardar
 * @returns {boolean} - True si se guardó correctamente
 */
function guardarEnLocalStorage(clave, valor) {
    try {
        const valorString = typeof valor === 'object' ? JSON.stringify(valor) : valor;
        localStorage.setItem(clave, valorString);
        return true;
    } catch (error) {
        mostrarErrorConsola(`Error al guardar en localStorage: ${error.message}`);
        return false;
    }
}

/**
 * Obtiene datos de localStorage de forma segura
 * @param {string} clave - Clave del dato
 * @param {any} valorPorDefecto - Valor por defecto si no existe
 * @returns {any} - Valor almacenado o valor por defecto
 */
function obtenerDeLocalStorage(clave, valorPorDefecto = null) {
    try {
        const valor = localStorage.getItem(clave);
        if (valor === null) return valorPorDefecto;
        
        // Intentar parsear como JSON
        try {
            return JSON.parse(valor);
        } catch {
            return valor;
        }
    } catch (error) {
        mostrarErrorConsola(`Error al obtener de localStorage: ${error.message}`);
        return valorPorDefecto;
    }
}

/**
 * Elimina un dato de localStorage
 * @param {string} clave - Clave del dato a eliminar
 * @returns {boolean} - True si se eliminó correctamente
 */
function eliminarDeLocalStorage(clave) {
    try {
        localStorage.removeItem(clave);
        return true;
    } catch (error) {
        mostrarErrorConsola(`Error al eliminar de localStorage: ${error.message}`);
        return false;
    }
}

/**
 * Copia texto al portapapeles
 * @param {string} texto - Texto a copiar
 * @returns {Promise<boolean>} - Promise que resuelve true si se copió correctamente
 */
async function copiarAlPortapapeles(texto) {
    try {
        if (navigator.clipboard) {
            await navigator.clipboard.writeText(texto);
            return true;
        } else {
            // Fallback para navegadores antiguos
            const textarea = document.createElement('textarea');
            textarea.value = texto;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            return true;
        }
    } catch (error) {
        mostrarErrorConsola(`Error al copiar al portapapeles: ${error.message}`);
        return false;
    }
}

/**
 * Detecta si el usuario está en un dispositivo móvil
 * @returns {boolean} - True si es móvil
 */
function esDispositivoMovil() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

/**
 * Obtiene la fecha y hora actual formateada
 * @returns {string} - Fecha y hora formateada
 */
function obtenerFechaHoraActual() {
    const ahora = new Date();
    return ahora.toLocaleString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}

/**
 * Scroll suave a un elemento
 * @param {string} selector - Selector del elemento
 * @param {number} offset - Offset en pixeles
 */
function scrollSuaveA(selector, offset = 0) {
    const elemento = document.querySelector(selector);
    if (elemento) {
        const posicion = elemento.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({
            top: posicion,
            behavior: 'smooth'
        });
    }
}

/**
 * Valida conexión a internet
 * @returns {boolean} - True si hay conexión
 */
function hayConexion() {
    return navigator.onLine;
}

/**
 * Maneja errores globales
 */
function configurarManejadorErrores() {
    window.addEventListener('error', function(event) {
        mostrarErrorConsola(`Error global: ${event.message} en ${event.filename}:${event.lineno}`);
    });

    window.addEventListener('unhandledrejection', function(event) {
        mostrarErrorConsola(`Promise rechazada: ${event.reason}`);
    });
}

// ========== EVENTOS DE CONEXIÓN ==========

/**
 * Maneja eventos de conexión/desconexión
 */
function configurarEventosConexion() {
    window.addEventListener('online', function() {
        console.log('🌐 Conexión a internet restaurada');
    });

    window.addEventListener('offline', function() {
        console.warn('⚠️ Sin conexión a internet');
    });
}

// ========== INICIALIZACIÓN ==========

/**
 * Inicializa el script general
 */
function inicializarScript() {
    mostrarInfoSistema();
    configurarManejadorErrores();
    configurarEventosConexion();
    
    // Log información del navegador
    console.log(`Navegador: ${navigator.userAgent}`);
    console.log(`Dispositivo móvil: ${esDispositivoMovil() ? 'Sí' : 'No'}`);
    console.log(`Conexión: ${hayConexion() ? 'En línea' : 'Sin conexión'}`);
    console.log(`Fecha/Hora: ${obtenerFechaHoraActual()}`);
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarScript);
} else {
    inicializarScript();
}

// Exportar funciones para uso global
window.CENDI = {
    version: VERSION,
    formatearFecha,
    formatearTelefono,
    capitalizarTexto,
    sanitizarTexto,
    mostrarCargando,
    mostrarExito,
    mostrarErrorConsola,
    noVacio,
    debounce,
    objetoAQueryString,
    generarIdUnico,
    guardarEnLocalStorage,
    obtenerDeLocalStorage,
    eliminarDeLocalStorage,
    copiarAlPortapapeles,
    esDispositivoMovil,
    obtenerFechaHoraActual,
    scrollSuaveA,
    hayConexion
};