const uid = () =>
  String(Date.now().toString(32) + Math.random().toString(16)).replace(
    /\./g,
    ""
  );

/* VER MAS TABLA */
function verMasTabla(event, descripcion_ver_mas) {
  event.preventDefault();
  $("#descripcion_ver_mas").html(descripcion_ver_mas.replace(/\n/g, "<br />"));
  $("#modalVerMas").modal("show");
}
/* VER MAS TABLA */

/*  FUNCION TABLA   */

function tabla(
  div,
  url,
  datos,
  columnsTable,
  recargar = false,
  function_Call_Back
) {
  let columns = [];
  columnsTable.map((column) => {
    columns.push({ data: column });
  });

  let tabla = $(`#${div}`).DataTable({
    drawCallback: function (settings) {
      function_Call_Back();
    },
    processing: true,
    serverMethod: "post",
    searching: true,
    ajax: {
      url: `${url_api}${url}`,
      data: datos,
    },
    columns,
    language: { url: `${url_app}js/config_tabla.json` },

    retrieve: true,
    order: [[0, "desc"]],
    responsive: true,
    autoWidth: false,
    pageLength: 10,
  });
  if (recargar == true) {
    tabla.ajax.reload();
  }
}

function tabla_async(
  div,
  url,
  datos,
  columnsTable,
  recargar = false,
  funcion_Call_Back
) {
  return new Promise((resolve, reject) => {
    let columns = [];
    columnsTable.map((column) => {
      columns.push({ data: column });
    });
    let tabla = $(`#${div}`).DataTable({
      drawCallback: function (settings) {
        funcion_Call_Back();
      },
      processing: true,
      serverMethod: "post",
      searching: true,
      ajax: {
        url: `${url_api}${url}`,
        data: datos,
      },
      columns,
      language: { url: `${url_app}js/config_tabla.json` },

      retrieve: true,
      order: [[0, "desc"]],
      responsive: true,
      autoWidth: false,
      pageLength: 10,
    });
    if (recargar == true) {
      tabla.ajax.reload();
    }
    resolve(true);
  });
}

/*  FUNCION TABLA  */

/*  FUNCION TABLA DE TIPO NORMAL */
function tablaNormal(div, url, columns, recargar = false) {

  let tabla = div.DataTable({

    'processing': true,
    'serverMethod': 'get',
    'searching': true,
    'ajax': {
      'url': url,
    },
    columns: columns,
    "language": { url: `${url_app}js/config_tabla.json` },

    retrieve: true,
    order: [[0, 'desc']],
    responsive: true,
    autoWidth: false,
    pageLength: 10,

  });
  if (recargar == true) {
    tabla.ajax.reload();
  }

}

/*  OBETENER DATOS POR URL */
function getUrl(sParam) {
  let sPageURL = window.location.search.substring(1),
    sURLVariables = sPageURL.split("&"),
    sParameterName,
    i;

  for (i = 0; i < sURLVariables.length; i++) {
    sParameterName = sURLVariables[i].split("=");

    if (sParameterName[0] === sParam) {
      return sParameterName[1] === undefined
        ? true
        : decodeURIComponent(sParameterName[1]);
    }
  }
  return false;
}
/*  OBETENER DATOS POR URL */

/*   MODAL */
function cerrarModal(div) {
  $(`#${div}`).modal("hide");
}

function abrirModal(div) {
  $(`#${div}`).modal("show");
}

function abrirModalStatic(div) {
  $(`#${div}`).modal({ backdrop: "static", keyboard: false }).modal("show");
}

/*   MODAL */

/* PARTIAL/VIEWS*/
function cargarPartial(tipo, partial) {
  $.get(`${url_app_views}/${tipo}/${partial}.html`, function (data) {
    $(`#${partial}`).html(data);
  });
}

function cargarPartialAsync(tipo, partial, id_div = false) {
  let div = id_div != false ? id_div : partial;
  $.get(`${url_app_views}/${tipo}/${partial}.html`, function (data) {
    $(`#${div}`).html(data);
  });
}

function cargarPartialViewsAsync(tipo, partial, id_div = false) {
  let div = id_div != false ? id_div : partial;
  $.get(`${url_app}/views/${tipo}/${partial}.html`, function (data) {
    $(`#${div}`).html(data);
  });
}
/* PARTIAL/VIEWS*/

/* PRIMERA LETRA A MAYUSCULAS */
function primeraLetraAMayusculas(cadena) {
  return cadena
    .charAt(0)
    .toUpperCase()
    .concat(cadena.substring(1, cadena.length));
}
/* PRIMERA LETRA A MAYUSCULAS */

/* IMAGENES */
function quitarImagenes(imagen, event) {
  event.preventDefault();
  $(`#${imagen}`).val("");
}
/* IMAGENES */

/* SCROLL */
function scroll(div) {
  $("html, body").animate({
    scrollTop: $(`#${div}`).offset().top,
  });
}
/* SCROLL */

/* INPUTS FUNCIONES */
function limpiarCampos(arrayInputs) {
  arrayInputs.forEach((input) => {
    $(`#${input}`).val("");
  });
}
/* INPUTS FUNCIONES */

/* FECHA ACTUAL */
function fechaActual() {
  const fecha = new Date();
  const anio = fecha.getFullYear();
  const dia = fecha.getDate();
  const mes = fecha.getMonth() + 1;

  return { dia: dia, mes: mes, anio: anio };
}

function setFechaActualMayor(input) {
  let fecha_actual = fechaActual();
  $(`${input}`).attr({
    max: `${fecha_actual.anio - 18}-${fecha_actual.mes}-${fecha_actual.dia}`,
    min: `${fecha_actual.anio - 120}-${fecha_actual.mes}-${fecha_actual.dia}`,
  });
}
/* FECHA ACTUAL */

/*    SWEET ALERT FUNCIONES */
function correcto(mensaje = false) {
  Swal.fire(
    "Exito!",
    !mensaje ? "La operación se realizó correctamente" : mensaje,
    "success"
  );
}

function correcto_pasajero(mensaje) {
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })

  Toast.fire({
    icon: 'success',
    title: mensaje
  })
}


function success(mensaje = false) {
  correcto(mensaje);
}
function error(
  mensaje = "Ha ocurrido un error, contacte al administrador",
  titulo = "Error!"
) {
  Swal.fire(titulo, mensaje, "error");
}

function warning(mensaje = false) {
  Swal.fire(
    "",
    !mensaje ? "Ha ocurrido un error, contacte al administrador" : mensaje,
    "warning"
  );
}

function cargando(opcion = "M", mensaje = null) {
  if (opcion === "M") {
    $loader = Swal.fire({
      icon: "info",
      title: "Cargando...",
      html: mensaje,
      allowEscapeKey: false,
      allowOutsideClick: false,
    });
    Swal.showLoading();
  } else {
    Swal.hideLoading();
    Swal.close();
  }
}

function showLoading(title = "Cargando...") {
  Swal.fire({
    title,
    allowEscapeKey: false,
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

function hideLoading() {
  Swal.close();
}

function mostrarLoader(opcion = "M") {
  $loader =
    opcion == "M"
      ? Swal.fire({
        icon: "info",
        title: "Cargando...",
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
          swal.showLoading();
        },
      })
      : $loader.close();
}

function confirmar(mensaje) {
  let conf = Swal.fire({
    title: mensaje,
    showDenyButton: true,
    confirmButtonText: "Aceptar",
    denyButtonText: `Cancelar`,
  });
  return conf;
}

function btnFooter(btn_footer) {
  $(`#${btn_footer.div}`).html(
    `<button class='btn btn-warning' onClick= ${btn_footer.funcion} > ${btn_footer.text}</button>`
  );
}

function deshabilitarBoton(idBtn) {
  $(`#${idBtn}`).prop("disabled", true);
}

function habilitarBoton(idBtn) {
  $(`#${idBtn}`).removeAttr("disabled");
}

function mostrarErrorInput(div, mensaje) {
  $(`${div}`).html(`<div style='color:red'> ${mensaje}</div>`);
}

function ocultarErrorInput(div) {
  $(`${div}`).html("");
}

function redirectTo(url) {
  window.location.href = url;
}

function remplazar(div, datos) {
  return new Promise(function (resolve, reject) {
    for (let key in datos) {
      if ($(`${div}:contains('{{${key}}}')`)) {
        let regex = new RegExp(`{{${key}}}`, "gi");
        $(`${div}`).html($(`${div}`).html().replace(regex, datos[key]));
      }
    }
  });
}

function showHidePassword() {
  const idInput = $(this).data("input");
  const $inputPassword = $(`#${idInput}`);
  if ($inputPassword.attr("type") == "text") {
    $inputPassword.attr("type", "password");
    $(this).find("i").removeClass("fas fa-eye").addClass("fas fa-eye-slash");
  } else if ($inputPassword.attr("type") == "password") {
    $inputPassword.attr("type", "text");
    $(this).find("i").removeClass("fas fa-eye-slash").addClass("fas fa-eye");
  }
}

function mostrarBotonesForm() {
  $("#botonesForm").removeClass("d-none").addClass("d-flex");
}

function ocultarSecciones() {
  $(".seccion-afiliacion").addClass("d-none");
}

function mostrarSeccion(idSeccion) {
  $(`#${idSeccion}`).parent().removeClass("d-none");
}

function setTituloHeader(titulo) {
  $("#headerTitulo").html(`<h2>${titulo}</h2>`);
}

function selectFechasForm() {
  const fecha = new Date();
  const anio = fecha.getFullYear();
  for (let i = anio; i <= anio + 15; i++) {
    $("#anio_vencimiento").append(` <option value="${i}">${i}</option>`);
  }

  for (let j = 1; j <= 12; j++) {
    $("#mes_vencimiento").append(` <option value="0${j}">${j}</option>`);
  }
}

/* Agregar Imagenes */
function agregarImagen(imagenes) {
  for (let imagen of imagenes) {
    $(`#${imagen.div}`).attr("src", `${url_imagenes}${imagen.nombre}`);
  }
}

function vaciar_inputs(campos) {
  campos.forEach((campo) => {
    campo == "txt_form_patologia" || campo == "txt_form_movilizacion"
      ? $("#" + campo)
        //.val("0")
        .val("")
        .change()
      : $("#" + campo).val("");
  });
}

function copiarLink() {
  let url = $("#url_para_pagar").val();
  $("#url_para_pagar").focus();
  let textArea = document.createElement("textarea");
  textArea.value = url;
  textArea.style.top = "0";
  textArea.style.left = "0";
  textArea.style.position = "fixed";
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();
  try {
    let successful = navigator.clipboard.writeText(textArea.value);
    if (successful) {
      Swal.fire("Correcto", "Link copiado correctamente!", "success");
    } else {
      Swal.fire("Error!", "Error copiando el enlace", "error");
    }
  } catch (err) { }
  document.body.removeChild(textArea);
}

function abrirLink() {
  let url = $("#url_para_pagar").val();
  window.open(url, "_blank");
}

function Contador(clase, cantidad) {
  new PureCounter({
    // Setting that can't' be overriden on pre-element
    selector: "." + clase, // HTML query selector for spesific element

    // Settings that can be overridden on per-element basis, by `data-purecounter-*` attributes:
    start: 0, // Starting number [uint]
    end: cantidad, // End number [uint]
    duration: 3, // The time in seconds for the animation to complete [seconds]
    delay: 10, // The delay between each iteration (the default of 10 will produce 100 fps) [miliseconds]
    once: true, // Counting at once or recount when the element in view [boolean]
    pulse: false, // Repeat count for certain time [boolean:false|seconds]
    decimals: 0, // How many decimal places to show. [uint]
    legacy: true, // If this is true it will use the scroll event listener on browsers
    filesizing: false, // This will enable/disable File Size format [boolean]
    currency: false, // This will enable/disable Currency format. Use it for set the symbol too [boolean|char|string]
    formater: "us-US", // Number toLocaleString locale/formater, by default is "en-US" [string|boolean:false]
    separator: false // This will enable/disable comma separator for thousands. Use it for set the symbol too [boolean|char|string]
  });
}