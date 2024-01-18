/*FUNCIONES DE SESION */

/* COMPROBAR SESION */
 
const verificarSesionCliente = async () => {
    return new Promise((resolve, reject) => {
        if ((localStorage.getItem("id_usuario") == "null" || localStorage.getItem("id_usuario") == null) || (localStorage.getItem("token") == "null" || localStorage.getItem("token") == null)) {
            window.location.replace("login.html");
            resolve();
        }
    });
}

const verificarSesionClienteLogin = async () => {
    return new Promise((resolve, reject) => {
        if ((localStorage.getItem("id_usuario") != "null" || localStorage.getItem("id_usuario") != null) || (localStorage.getItem("token") != "null" || localStorage.getItem("token") != null)) {
            window.location.replace("index.html");
            resolve();
        }
    });
}
/* COMPROBAR SESION */

/* CERRAR SESION  */
const CerrarSesion = async (event = null) => {
    if (event != null) event.preventDefault();
    localStorage.removeItem("id");
    localStorage.removeItem("token");
    window.location.replace("login.html");
}
/* CERRAR SESION   */

/*LOGIN */
const Login = async (event) => {
    event.preventDefault();
    showLoading();
    const respuesta = await http("login.php", "POST", $("#login").serialize());
    hideLoading();
    if (respuesta.success) {
        localStorage.setItem("app-id_usuario", respuesta.id);
        localStorage.setItem("app-token", respuesta.token);
        window.location.replace("index.html");
    }
    else error(respuesta.mensaje);
}
/* LOGIN */



