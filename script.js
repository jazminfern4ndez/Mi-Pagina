//Cookies
function setCookie(c_name, value, expiredays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = c_name + "=" + encodeURIComponent(value) +
        ";expires=" + exdate.toUTCString() + ";path=/";
}

function getCookie(c_name) {
    var cookies = document.cookie.split("; ");
    for (var i = 0; i < cookies.length; i++) {
        var par = cookies[i].split("=");
        if (par[0] === c_name) {
            return decodeURIComponent(par[1]);
        }
    }
    return "";
}

//Cookies creativas: última visita y contador (el nombre se guarda en Contacto)
function registrarVisita() {
    var ultima = getCookie("ultimaVisita");
    var visitas = parseInt(getCookie("visitas")) || 0;
    var caja = document.getElementById("visitas");

    setCookie("ultimaVisita", new Date().toLocaleString("es-MX"), 365);
    setCookie("visitas", visitas + 1, 365);

    if (getCookie("visitas") === "") {
        caja.innerHTML = "Tu navegador no está guardando cookies (prueba abriendo el sitio desde un servidor).";
        return;
    }
    if (ultima === "") {
        caja.innerHTML = "✦ Es tu primera visita, ¡bienvenido! ✦";
    } else {
        caja.innerHTML = "Tu última visita fue el " + ultima + " · Visita número " + (visitas + 1);
    }
}

//Fecha, día y hora actual
var dias = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];
var meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio",
             "agosto", "septiembre", "octubre", "noviembre", "diciembre"];

function dosDigitos(n) {
    return n < 10 ? "0" + n : n;
}

function actualizarReloj() {
    var ahora = new Date();
    var h = ahora.getHours();
    var saludo = "Buenas noches.";
    if (h < 12) { saludo = "Buenos días."; }
    else if (h < 19) { saludo = "Buenas tardes."; }

    document.getElementById("fecha").innerHTML = dias[ahora.getDay()] + ", " +
        ahora.getDate() + " de " + meses[ahora.getMonth()] + " de " + ahora.getFullYear();
    document.getElementById("hora").innerHTML = dosDigitos(h) + ":" +
        dosDigitos(ahora.getMinutes()) + ":" + dosDigitos(ahora.getSeconds());
    var nombre = getCookie("nombre");
    if (nombre !== "") { saludo += " Qué gusto verte de nuevo, " + nombre + " ♡"; }
    document.getElementById("saludo").innerHTML = saludo;

    setTimeout(actualizarReloj, 1000);
}

//Convertidor a pesos
function cambiarTasa() {
    document.getElementById("tasa").value = document.getElementById("moneda").value;
}

function convertir() {
    var monto = parseFloat(document.getElementById("monto").value);
    var tasa = parseFloat(document.getElementById("tasa").value);
    var lista = document.getElementById("moneda");
    var nombre = lista.options[lista.selectedIndex].text;
    var salida = document.getElementById("resultado-conv");

    if (isNaN(monto) || monto < 0 || isNaN(tasa) || tasa <= 0) {
        salida.innerHTML = "Escribe una cantidad y un tipo de cambio válidos.";
        return;
    }
    salida.innerHTML = monto.toFixed(2) + " " + nombre + " = <strong>$" +
        (monto * tasa).toFixed(2) + " MXN</strong>";
}

//Calculadora
var primero = 0;
var operacion = "";
var nuevo = true;   // true = el siguiente dígito empieza un número nuevo

function pulsar(digito) {
    var pantalla = document.getElementById("pantalla");
    if (nuevo || pantalla.value === "0") {
        pantalla.value = digito;
        nuevo = false;
    } else {
        pantalla.value += digito;
    }
}

function punto() {
    var pantalla = document.getElementById("pantalla");
    if (nuevo) {
        pantalla.value = "0.";
        nuevo = false;
    } else if (pantalla.value.indexOf(".") === -1) {
        pantalla.value += ".";
    }
}

function operar(op) {
    var pantalla = document.getElementById("pantalla");
    if (operacion !== "" && !nuevo) { calcular(); }
    if (isNaN(parseFloat(pantalla.value))) { return; }
    primero = parseFloat(pantalla.value);
    operacion = op;
    nuevo = true;
}

function calcular() {
    var pantalla = document.getElementById("pantalla");
    var segundo = parseFloat(pantalla.value);
    var r;
    if (operacion === "") { return; }

    if (operacion === "+") { r = primero + segundo; }
    else if (operacion === "-") { r = primero - segundo; }
    else if (operacion === "*") { r = primero * segundo; }
    else {
        if (segundo === 0) {
            pantalla.value = "No se divide entre 0";
            operacion = "";
            nuevo = true;
            return;
        }
        r = primero / segundo;
    }
    pantalla.value = Math.round(r * 100000000) / 100000000;
    operacion = "";
    nuevo = true;
}

function alCuadrado() {  
    var pantalla = document.getElementById("pantalla");
    var n = parseFloat(pantalla.value);
    if (isNaN(n)) { return; }
    pantalla.value = Math.round(n * n * 100000000) / 100000000;
    nuevo = true;
}

function limpiarCalc() {
    document.getElementById("pantalla").value = "0";
    primero = 0;
    operacion = "";
    nuevo = true;
}

//Cuestionario autocalificado
function valorRadio(nombre) {
    var r = document.getElementsByName(nombre);
    for (var i = 0; i < r.length; i++) {
        if (r[i].checked) { return r[i].value; }
    }
    return "";
}

function calificar(num, esCorrecta, respuesta) {
    var fb = document.getElementById("fb" + num);
    if (esCorrecta) {
        fb.className = "resp bien";
        fb.innerHTML = "¡Correcto!";
        return 1;
    }
    fb.className = "resp mal";
    fb.innerHTML = "No es correcto. Respuesta: " + respuesta;
    return 0;
}

function calificarQuiz() {
    var puntos = 0;

    puntos += calificar(1, valorRadio("p1") === "5 de junio", "5 de junio");
    puntos += calificar(2, document.getElementById("p2").value === "Administración de Empresas",
                        "Administración de Empresas");

    var cancion = document.getElementById("p3").value.trim().toLowerCase();
    puntos += calificar(3, cancion === "feel", "Feel");

    var musicales = document.getElementsByName("p4");
    var bien = true;
    for (var i = 0; i < musicales.length; i++) {
        var debeEstar = (musicales[i].value !== "Wicked");
        if (musicales[i].checked !== debeEstar) { bien = false; }
    }
    puntos += calificar(4, bien, "Heather, Hadestown y Epic: The Musical");

    puntos += calificar(5, valorRadio("p5") === "Aprendiz de todo, maestro de nada",
                        "Aprendiz de todo, maestro de nada");

    var mensaje = "Tu puntaje: <strong>" + puntos + " de 5</strong>. ";
    if (puntos === 5) { mensaje += "¡Me conoces de maravilla!"; }
    else if (puntos >= 3) { mensaje += "¡Nada mal!"; }
    else { mensaje += "Vuelve a leer mi autobiografía y reintenta ♡"; }

    var mejor = parseInt(getCookie("mejorPuntaje")) || 0;
    if (puntos > mejor) {
        setCookie("mejorPuntaje", puntos, 365);
        mejor = puntos;
    }
    if (getCookie("mejorPuntaje") !== "") {
        mensaje += "<br>Tu mejor puntaje en este navegador: " + mejor + " de 5";
    }
    document.getElementById("resultado-quiz").innerHTML = mensaje;
}

function reiniciarQuiz() {
    document.getElementById("quiz").reset();
    for (var i = 1; i <= 5; i++) {
        document.getElementById("fb" + i).innerHTML = "";
    }
    document.getElementById("resultado-quiz").innerHTML = "";
}

//Validación del formulario de contacto
var primerError = null;

function ponerError(id, mensaje) {
    document.getElementById("error-" + id).innerHTML = mensaje;
    return mensaje === "";
}

function revisar(esValido, idParaFoco) {
    if (!esValido && primerError === null) { primerError = idParaFoco; }
}

function validarTexto(id) {  
    var v = document.getElementById(id).value.trim();
    var msg = "";
    if (v === "") { msg = "Este campo es obligatorio"; }
    else if (v.length < 2) { msg = "Escribe al menos 2 caracteres"; }
    else if (!/^[A-Za-zÁÉÍÓÚÜáéíóúüÑñ ]+$/.test(v)) { msg = "Usa solo letras y espacios"; }
    return ponerError(id, msg);
}

function validarCorreo() {
    var v = document.getElementById("correo").value.trim();
    var msg = "";
    if (v === "") { msg = "Este campo es obligatorio"; }
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) { msg = "Introduce una dirección de correo electrónico válida"; }
    return ponerError("correo", msg);
}

function validarGenero() {
    return ponerError("genero", valorRadio("genero") === "" ? "Selecciona una opción" : "");
}

function validarIntereses() {
    var cajas = document.getElementsByName("interes");
    var alguno = false;
    for (var i = 0; i < cajas.length; i++) {
        if (cajas[i].checked) { alguno = true; }
    }
    return ponerError("intereses", alguno ? "" : "Marca al menos un interés");
}

function validarEstudios() {
    var v = document.getElementById("estudios").value;
    return ponerError("estudios", v === "" ? "Elige tu nivel de estudios" : "");
}

function validarMensaje() {
    var v = document.getElementById("mensaje").value.trim();
    var msg = "";
    if (v === "") { msg = "Este campo es obligatorio"; }
    else if (v.length < 10) { msg = "El mensaje debe tener al menos 10 caracteres"; }
    return ponerError("mensaje", msg);
}

function validarCondiciones() {
    var ok = document.getElementById("condiciones").checked;
    return ponerError("condiciones", ok ? "" : "Debes aceptar para continuar");
}

function contar() {
    var n = document.getElementById("mensaje").value.length;
    document.getElementById("contador").innerHTML = n + " caracteres";
}

function validarFormulario() {
    primerError = null;
    revisar(validarTexto("nombre"), "nombre");
    revisar(validarTexto("apellidos"), "apellidos");
    revisar(validarCorreo(), "correo");
    revisar(validarGenero(), "genero-f");
    revisar(validarIntereses(), "interes-lectura");
    revisar(validarEstudios(), "estudios");
    revisar(validarMensaje(), "mensaje");
    revisar(validarCondiciones(), "condiciones");

    if (primerError !== null) {
        document.getElementById(primerError).focus();   
        document.getElementById("resultado-form").innerHTML = "";
        return false;
    }

    var nombre = document.getElementById("nombre").value.trim();
    setCookie("nombre", nombre, 365);   // la portada te saludará por tu nombre

    var boton = document.getElementById("enviar");     // evita envíos duplicados
    boton.disabled = true;
    boton.value = "Enviado ✓";
    document.getElementById("resultado-form").innerHTML =
        "<div class='exito'>¡Gracias, " + nombre + "! Tu mensaje pasó la validación. " +
        "(Este sitio es de práctica: el formulario no se envía a ningún servidor.)</div>";
    return false;
}

function limpiarFormulario() {
    var campos = ["nombre", "apellidos", "correo", "genero", "intereses", "estudios", "mensaje", "condiciones"];
    for (var i = 0; i < campos.length; i++) {
        document.getElementById("error-" + campos[i]).innerHTML = "";
    }
    document.getElementById("resultado-form").innerHTML = "";
    document.getElementById("contador").innerHTML = "0 caracteres";
    var boton = document.getElementById("enviar");
    boton.disabled = false;
    boton.value = "Enviar mensaje";
}

//Módulo de usuarios: validación de login, registro y modificación
//(reutiliza ponerError, revisar, validarTexto y validarCorreo de arriba)
function validarVacio(id) {
    var v = document.getElementById(id).value.trim();
    return ponerError(id, v === "" ? "Este campo es obligatorio" : "");
}

function validarUsuario() {
    var v = document.getElementById("usuario").value.trim();
    var msg = "";
    if (v === "") { msg = "Este campo es obligatorio"; }
    else if (!/^[A-Za-z0-9_]{4,20}$/.test(v)) { msg = "De 4 a 20 caracteres: letras, números o guion bajo (sin espacios ni acentos)"; }
    return ponerError("usuario", msg);
}

function validarClave(obligatoria) {
    var v = document.getElementById("clave").value;
    var msg = "";
    if (v === "") {
        if (obligatoria) { msg = "Este campo es obligatorio"; }
    }
    else if (v.length < 8) { msg = "La contraseña debe tener al menos 8 caracteres"; }
    else if (!/[A-Za-z]/.test(v) || !/[0-9]/.test(v)) { msg = "Combina letras y números"; }
    return ponerError("clave", msg);
}

function validarReclave() {
    var clave = document.getElementById("clave").value;
    var re = document.getElementById("reclave").value;
    var msg = "";
    if (clave !== "" && re === "") { msg = "Vuelve a escribir la contraseña"; }
    else if (clave !== re) { msg = "Las contraseñas no coinciden"; }
    return ponerError("reclave", msg);
}

function medirClave() {
    var v = document.getElementById("clave").value;
    var caja = document.getElementById("fuerza");
    var puntos = 0;
    if (v.length >= 8) { puntos++; }
    if (/[A-Z]/.test(v) && /[a-z]/.test(v)) { puntos++; }
    if (/[0-9]/.test(v)) { puntos++; }
    if (/[^A-Za-z0-9]/.test(v)) { puntos++; }

    var textos = ["", "Débil", "Regular", "Buena", "Muy segura"];
    caja.className = "fuerza f" + puntos;
    caja.innerHTML = v === "" ? "" : "Seguridad: " + textos[puntos];
    if (document.getElementById("reclave").value !== "") { validarReclave(); }
}

function verClaves(casilla) {
    var tipo = casilla.checked ? "text" : "password";
    var ids = ["clave", "reclave"];
    for (var i = 0; i < ids.length; i++) {
        var campo = document.getElementById(ids[i]);
        if (campo) { campo.type = tipo; }
    }
}

function validarLogin() {
    primerError = null;
    revisar(validarVacio("usuario"), "usuario");
    revisar(validarVacio("clave"), "clave");
    if (primerError !== null) { document.getElementById(primerError).focus(); return false; }
    return true;
}

function validarCuenta(claveObligatoria) {
    primerError = null;
    revisar(validarTexto("nombre"), "nombre");
    revisar(validarTexto("apellidos"), "apellidos");
    revisar(validarCorreo(), "correo");
    revisar(validarUsuario(), "usuario");
    revisar(validarClave(claveObligatoria), "clave");
    revisar(validarReclave(), "reclave");
    if (primerError !== null) { document.getElementById(primerError).focus(); return false; }
    return true;
}

function validarRegistro() { return validarCuenta(true); }
function validarEdicion() { return validarCuenta(false); }

function limpiarErrores() {
    var errores = document.getElementsByClassName("error");
    for (var i = 0; i < errores.length; i++) { errores[i].innerHTML = ""; }
    var fuerza = document.getElementById("fuerza");
    if (fuerza) { fuerza.innerHTML = ""; }
}

function confirmarEliminar(usuario) {
    return confirm("¿Seguro que quieres eliminar al usuario «" + usuario + "»? Esta acción no se puede deshacer.");
}

//Arranque: solo corre lo que exista en cada página
if (document.getElementById("hora")) {
    actualizarReloj();
    registrarVisita();
}
