function openModal_UploaderBajas() {
    $("#file-progress-bar").width('0%');
    const boton_enviar = document.getElementById("enviar_archivo");
    subiendoArchivo_verde(boton_enviar);

    abrirModal("modal_uploaderBajas");
}



function enviar_archivo() {

    var barra_estado = document.getElementById("file-progress-bar");
    barra_estado.className += "progress-bar progress-bar-striped progress-bar-animated bg-primary";
    const boton_enviar = document.getElementById("enviar_archivo");


    if ($("#archivo_cargado").val() === "") {
        Swal.fire('Error!', 'Debe de seleccionar un archivo', 'error');
        $("#file-progress-bar").width('0%');
        subiendoArchivo_verde(boton_enviar);
        document.getElementById("archivo_cargado").value = "";
    } else {
        const form_data = new FormData();
        const file_data = $("#archivo_cargado").prop("files")[0];
        form_data.append("file", file_data);

        $.ajax({
            xhr: () => {
                showLoading("Aguarde por favor, puede tardar hasta 1 minuto aprox. el proceso");
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (element) {
                    if (element.lengthComputable) {
                        var percentComplete = ((element.loaded / element.total) * 100);
                        $("#file-progress-bar").width(percentComplete + '%');
                        $("#file-progress-bar").html(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            beforeSend: function () {
                $("#file-progress-bar").width('0%');
                subiendoArchivo_celeste(boton_enviar);
            },
            cache: false,
            contentType: false,
            data: form_data,
            dataType: 'JSON',
            enctype: 'multipart/form-data',
            processData: false,
            method: "POST",
            url: `${url_app_ajax}/uploader_bajas.php`,
            success: (data) => {
                if (data.success) {
                    hideLoading();
                    success(data.mensaje);
                    barra_estado.className += "progress-bar progress-bar-striped progress-bar-animated bg-success";
                    subiendoArchivo_completado(boton_enviar);
                    document.getElementById("archivo_cargado").value = "";

                } else {
                    hideLoading();
                    error(data.mensaje);
                    barra_estado.className += "progress-bar progress-bar-striped progress-bar-animated bg-danger";
                    subiendoArchivo_rojo(boton_enviar);
                }
            }, error: (err) => {
                error("Error al intentar subir y guardar el archivo");
                barra_estado.className += "progress-bar progress-bar-striped progress-bar-animated bg-danger";
                subiendoArchivo_rojo(boton_enviar);
                hideLoading();
                console.log(err);
            }

        });
    }

}



$(document).on('change', 'input[type="file"]', function () {

    $("#file-progress-bar").width('0%');
    const boton_enviar = document.getElementById("enviar_archivo");
    subiendoArchivo_verde(boton_enviar);
    const fileName = this.files[0].name;
    const fileSize = this.files[0].size;

    let ext = fileName.split('.');
    // ahora obtenemos el ultimo valor despues el punto
    // obtenemos el length por si el archivo lleva nombre con mas de 2 puntos
    ext = ext[ext.length - 1];

    switch (ext) {
        case 'xlsx':
            $('#archivo_cargado').text(fileSize + " bytes en " + ext);
            break;
        case 'xls':
            $('#archivo_cargado').text(fileSize + " bytes en " + ext);
            break;
        case 'csv':
            $('#archivo_cargado').text(fileSize + " bytes " + ext);
            break;
        default:
            Swal.fire('Error de extención!', 'La extención del archivo solo puede ser <span style="color:red; font-weight: bold">xlsx</span> o <span style="color:red; font-weight: bold">csv</span>', 'error');
            document.getElementById("archivo_cargado").value = "";
            break;
    }
});





//Botón verde con texto "Cargar Archivo"
function subiendoArchivo_verde(boton_enviar) {
    boton_enviar.className = "btn btn-success";
    boton_enviar.innerHTML = "Cargar archivo";
}

//Botón celeste deshabilitado con texto "Subiendo archivo ..."
function subiendoArchivo_celeste(boton_enviar) {
    boton_enviar.className = "btn btn-info disabled";
    boton_enviar.innerHTML = "Subiendo archivo ...";
}

//Botón rojo con texto "Reintentar"
function subiendoArchivo_rojo(boton_enviar) {
    boton_enviar.className = "btn btn-danger";
    boton_enviar.innerHTML = "Reintentar";
}

//Botón verde deshabilitado con texto "¡Cargado!"
function subiendoArchivo_completado(boton_enviar) {
    boton_enviar.className = "btn btn-success disabled";
    boton_enviar.innerHTML = "¡Cargado!";
}