$(document).ready(function () {

    validarSesion();

});


function validarSesion() {
    let id = localStorage.getItem("id");
    let usuario = localStorage.getItem("usuario");
    let password = localStorage.getItem("password");
    let hash = localStorage.getItem("hash");
    let activo = localStorage.getItem("activo");

    $.ajax({
        type: "POST",
        url: `${url_app_ajax}/verificar_usuario.php`,
        data: {
            id: id,
            usuario: usuario,
            password: password,
            hash: hash,
            activo: activo
        },
        dataType: "JSON",
        success: function (response) {
            if (response.error === false) {
                correcto_pasajero(response.mensaje);
                location.href = "index.html";
            } else {
                gestionarLocalStorage();
            }
        }
    });
}


function ingresar() {
    let usuario = $("#txt_usuario").val();
    let password = $("#txt_password").val();

    $.ajax({
        type: "POST",
        url: `${url_app_ajax}/login.php`,
        data: {
            usuario: usuario,
            password: password
        },
        dataType: "JSON",
        success: function (response) {
            if (response.error === false) {
                correcto(response.mensaje);

                let datos = response.datos;
                gestionarLocalStorage(datos);

                location.href = "index.html";
            } else {
                error(response.mensaje);
            }
        }
    });
}


function gestionarLocalStorage(datos = null) {
    if (datos === null) {
        localStorage.clear();
    } else {
        let id = datos["id"];
        let usuario = datos["usuario"];
        let password = datos["password"];
        let hash = datos["hash"];
        let activo = datos["activo"];

        localStorage.setItem("id", id);
        localStorage.setItem("usuario", usuario);
        localStorage.setItem("password", password);
        localStorage.setItem("hash", hash);
        localStorage.setItem("activo", activo);
    }

}